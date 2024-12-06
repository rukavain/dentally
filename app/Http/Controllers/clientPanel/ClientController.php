<?php

namespace App\Http\Controllers\clientPanel;

use Auth;
use App\Models\User;
use App\Models\Image;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Appointment;
use App\Models\TemporaryPayment;
use App\Models\PaymentHistory;
use App\Models\ToothRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ClientPendingPayment;
use App\Notifications\ClientCancelledAppointment;

class ClientController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif (Auth::user()->role === 'dentist') {
            return redirect()->route('dentist.dashboard');
        } else {
            return redirect()->route('client.overview', Auth::user()->patient_id);
        }
    }


    public function profileOverview($id)
    {
        // Retrieve patient ID from session
        // Fetch the patient's details from the database
        $patient = Patient::find($id);

        $appointments = Appointment::where('patient_id', $id)
            // ->where('status', '!=', 'cancelled')
            ->with('procedure')
            ->paginate(7);

        $appointmentIds = $appointments->pluck('id');

        // Fetch payments related to the patient's appointments
        $payments = Appointment::where('patient_id', $id)
            ->where('pending', 'Approved')
            ->where('status', '!=', 'Cancelled')
            ->with(['procedure', 'dentist', 'payment'])
            ->paginate(7);
        // Pass the patient data to the profile view
        return view('client.contents.overview', compact('patient', 'appointments', 'payments'));
    }

    public function clientRecords($id)
    {
        // Get X-ray images
        $xrayImages = Image::where('patient_id', $id)
            ->where('image_type', 'xray')
            ->get();

        // Get contract image
        $contractImage = Image::where('patient_id', $id)
            ->where('image_type', 'contract')
            ->first();

        // Get payment proof images
        $paymentProof = Image::where('patient_id', $id)
            ->where('image_type', 'proof_of_payment')
            ->get();

        // Get dental records
        $teethRecords = ToothRecord::with('note')
            ->where('patient_id', $id)
            ->get()
            ->keyBy('tooth_number');

        // Ensure all 32 teeth are represented with proper object structure
        $completeTeeth = [];
        for ($i = 1; $i <= 32; $i++) {
            if (isset($teethRecords[$i])) {
                $completeTeeth[$i] = [
                    'tooth_number' => $i,
                    'status' => $teethRecords[$i]->status,
                    'note' => $teethRecords[$i]->note ? ['note_text' => $teethRecords[$i]->note->note_text] : null
                ];
            } else {
                $completeTeeth[$i] = [
                    'tooth_number' => $i,
                    'status' => 'normal',
                    'note' => null
                ];
            }
        }

        return view('client.contents.client-records', compact(
            'xrayImages',
            'contractImage',
            'paymentProof',
            'completeTeeth'
        ));
    }

    public function createClientPayment($appointmentId)
    {
        // Retrieve the appointment with related patient and procedure data
        $appointment = Appointment::with(['patient', 'procedure', 'dentist'])->find($appointmentId);


        // Retrieve payment record for the appointment
        $payment = Payment::where('appointment_id', $appointment->id)->first();

        // Calculate total paid and balance remaining
        $totalPaid = $payment ? $payment->total_paid : 0;
        $balanceRemaining = $appointment->procedure->price - $totalPaid;

        return view('client.contents.client-payment-form', compact('appointment', 'payment', 'totalPaid', 'balanceRemaining'));
    }

    //temporary payment
    public function storeClientPartialPayment(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'paid_amount' => 'required|numeric|min:0',
            'password' => 'required|string',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for payment proof
        ]);

        // Retrieve the appointment
        $appointment = Appointment::with(['procedure', 'patient'])->find($request->appointment_id);

        // Check if the password is correct for the patient
        if (!Hash::check($request->password, $appointment->patient->password)) {
            return response()->json(['success' => false, 'message' => 'Incorrect password. Please try again.']);
        }

        $existingPendingPayment = TemporaryPayment::where('payment_id', $appointment->payment->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPendingPayment) {
            return response()->json(['success' => false, 'message' => 'There is already a pending payment for this appointment. Please wait for it to be approved before submitting another payment.']);
        }

        // Handle payment proof upload if provided
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('temp_images', 'public');
        }

        // Store the payment details in the temporary table
        TemporaryPayment::create([
            'payment_id' => $appointment->payment->id,
            'paid_amount' => $request->paid_amount,
            'payment_method' => $request->payment_method,
            'remarks' => $request->remarks ?? null, // Optional remarks
            'payment_proof' => $paymentProofPath, // Store the path of the uploaded proof
            'status' => 'pending',
        ]);

        // Send notification to admin and staff users
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        foreach ($users as $user) {
            $user->notify(new ClientPendingPayment($appointment));
        }

        return response()->json(['success' => true, 'message' => 'Payment submitted for review.']);
    }

    public function showClientPaymentHistory($appointmentId)
    {
        // Retrieve the appointment with related patient and procedure data
        $appointment = Appointment::with(['patient', 'procedure'])->find($appointmentId);

        // Check if the appointment exists
        if (!$appointment) {
            return redirect()->route('appointments.index')->with('error', 'Appointment not found.');
        }

        // Retrieve payment history for the appointment
        $paymentHistory = PaymentHistory::whereHas('payment', function ($query) use ($appointmentId) {
            $query->where('appointment_id', $appointmentId);
        })->get();

        // Calculate total paid and balance remaining
        $totalPaid = $paymentHistory->sum('paid_amount');
        $balanceRemaining = $appointment->procedure->price - $totalPaid;

        return view('client.contents.client-payment-history', compact('appointment', 'paymentHistory', 'totalPaid', 'balanceRemaining'));
    }

    public function cancelAppointment($appointmentId)
    {
        $appointment = Appointment::with(['patient', 'procedure', 'dentist'])->find($appointmentId);

        $appointment->status = 'Cancelled';
        $appointment->pending = 'Declined';

        $appointment->save();

        $users = User::whereIn('role', ['admin', 'staff'])->get();
        foreach ($users as $user) {
            $user->notify(new ClientCancelledAppointment($appointment));
        }

        return redirect()->route('client.overview', $appointment->patient_id)->with('success', 'Appointment cancelled');
    }
}
