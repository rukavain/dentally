<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{

    public function pay(Request $request, $appointmentId){
        $appointment = Appointment::with(['patient', 'procedure', 'dentist'])->find($appointmentId);

        $paid_amount = $request->input('paid_amount'); // User-inputted amount
        $payment_method = $request->input('payment_method');
        $name = $appointment->procedure->name; // User-inputted name

        $data = [
            'data' => [
                'attributes' => [
                        'line_items' => [
                                [
                                    'currency' => 'PHP',
                                    'amount' => $paid_amount * 100, //10000 = 100PESOS
                                    'description' => $name,
                                    'name' => $name,
                                    'quantity' => 1,
                                ]
                            ],
                        'payment_method_types' => [
                            'card',
                        ],
                        // 'success_url' => 'https://toothimpressionsdentalclinic.xyz/success',
                        // 'cancel_url' => 'https://toothimpressionsdentalclinic.xyz',
                        // 'description' => $name,
                        'success_url' => 'http://127.0.0.1:8000/success',
                        'cancel_url' => 'http://127.0.0.1:8000/',
                        'description' => $name,
                    ]
                ]

            ];


        $response = Curl::to("https://api.paymongo.com/v1/checkout_sessions")
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic c2tfdGVzdF9NMU5EOUpzRWtCNVQyNUxCZ0hySEwyZmk6')
            ->withData($data)
            ->asJson()
            ->withOption('SSL_VERIFYHOST', false)
            ->withOption('SSL_VERIFYPEER', false)
            ->post();

            $payment = Payment::where('appointment_id', $appointment->id)->first();
            $totalPaid = 0;
            $balanceRemaining = $appointment->procedure->price;

            if ($payment) {
                $totalPaid = $payment->total_paid;
                $balanceRemaining = $payment->balance_remaining;

                if ($request->paid_amount > $balanceRemaining) {
                    return response()->json(['success' => false, 'message' => 'Payment exceeds the remaining balance of $' . number_format($balanceRemaining, 2)]);
                }

                $totalPaid += $request->paid_amount;
                $balanceRemaining -= $request->paid_amount;

                $status = $balanceRemaining <= 0 ? 'Paid' : 'Pending';

                $payment->update([
                    'total_paid' => $totalPaid,
                    'balance_remaining' => $balanceRemaining,
                    'status' => $status,
                ]);
            } else {
                if ($request->paid_amount > $balanceRemaining) {
                    return response()->json(['success' => false, 'message' => 'Payment exceeds the total amount due of $' . number_format($balanceRemaining, 2)]);
                }

                // Create a new payment record
                $payment = Payment::create([
                    'appointment_id' => $request->appointment_id,
                    'amount_due' => $appointment->procedure->price,
                    'total_paid' => $request->paid_amount,
                    'balance_remaining' => $balanceRemaining - $request->paid_amount,
                    'status' => 'Pending', // Initial status for new payment
                ]);
            }

            // Create a payment history record for tracking
            PaymentHistory::create([
                'payment_id' => $payment->id,
                'paid_amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'remarks' => $request->remarks ?? null, // Optional remarks
            ]);

            // Log the action
            AuditLog::create([
                'action' => 'Payment',
                'model_type' => 'New payment added',
                'model_id' => $payment->id,
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'changes' => json_encode($request->all()),
            ]);

            // Store session ID if needed
            Session::put('session_id', $response->data->id);

        return redirect()->to($response->data->attributes->checkout_url);
    }

    public function testPay(Request $request, $appointmentId){
        $appointment = Appointment::with(['patient', 'procedure', 'dentist'])->find($appointmentId);

        $paid_amount = $request->input('paid_amount'); // User-inputted amount
        $payment_method = $request->input('payment_method');
        $name = $appointment->procedure->name; // User-inputted name

        $data = [
            'data' => [
                'attributes' => [
                        'line_items' => [
                                [
                                    'currency' => 'PHP',
                                    'amount' => $paid_amount * 100, //10000 = 100PESOS
                                    'description' => $name,
                                    'name' => $name,
                                    'quantity' => 1,
                                ]
                            ],
                        'payment_method_types' => [
                            'card',
                        ],
                        // 'success_url' => 'https://toothimpressionsdentalclinic.xyz/success',
                        // 'cancel_url' => 'https://toothimpressionsdentalclinic.xyz',
                        // 'description' => $name,
                        'success_url' => 'http://127.0.0.1:8000/success',
                        'cancel_url' => 'http://127.0.0.1:8000/',
                        'description' => $name,
                    ]
                ]

            ];


        $response = Curl::to("https://api.paymongo.com/v1/checkout_sessions")
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic c2tfdGVzdF9NMU5EOUpzRWtCNVQyNUxCZ0hySEwyZmk6')
            ->withData($data)
            ->asJson()
            ->withOption('SSL_VERIFYHOST', false)
            ->withOption('SSL_VERIFYPEER', false)
            ->post();

            $payment = Payment::where('appointment_id', $appointment->id)->first();
            $totalPaid = 0;
            $balanceRemaining = $appointment->procedure->price;

            if ($payment) {
                $totalPaid = $payment->total_paid;
                $balanceRemaining = $payment->balance_remaining;

                if ($request->paid_amount > $balanceRemaining) {
                    return response()->json(['success' => false, 'message' => 'Payment exceeds the remaining balance of $' . number_format($balanceRemaining, 2)]);
                }

                $totalPaid += $request->paid_amount;
                $balanceRemaining -= $request->paid_amount;

                $status = $balanceRemaining <= 0 ? 'Paid' : 'Pending';

                $payment->update([
                    'total_paid' => $totalPaid,
                    'balance_remaining' => $balanceRemaining,
                    'status' => $status,
                ]);
            } else {
                if ($request->paid_amount > $balanceRemaining) {
                    return response()->json(['success' => false, 'message' => 'Payment exceeds the total amount due of $' . number_format($balanceRemaining, 2)]);
                }

                // Create a new payment record
                $payment = Payment::create([
                    'appointment_id' => $request->appointment_id,
                    'amount_due' => $appointment->procedure->price,
                    'total_paid' => $request->paid_amount,
                    'balance_remaining' => $balanceRemaining - $request->paid_amount,
                    'status' => 'Pending', // Initial status for new payment
                ]);
            }

            // Create a payment history record for tracking
            PaymentHistory::create([
                'payment_id' => $payment->id,
                'paid_amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'remarks' => $request->remarks ?? null, // Optional remarks
            ]);

            // Log the action
            AuditLog::create([
                'action' => 'Payment',
                'model_type' => 'New payment added',
                'model_id' => $payment->id,
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'changes' => json_encode($request->all()),
            ]);

            // Store session ID if needed
            Session::put('session_id', $response->data->id);

        return redirect()->to($response->data->attributes->checkout_url);
    }

    public function success(){
        $user_id = Auth::user();

        $sessionId = Session::get('session_id');

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions/'.$sessionId)
            ->withHeader('Content-Type: application/json')
            ->withHeader('Authorization: Basic c2tfdGVzdF9NMU5EOUpzRWtCNVQyNUxCZ0hySEwyZmk6')
            ->asJson()
            ->get();



            return redirect()->route('client.overview', Auth::user()->patient_id)->with('success', 'Payment Successful!');
    }

}
