@extends('client.profile')
@section('content')
    <style>
        .validation-message {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .validation-message.show {
            display: block;
            opacity: 1;
        }

        .validation-message.hide {
            opacity: 0;
        }
    </style>

    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto  max-lg:mt-14">
        <div class="m-2">

            <h2 class=" font-bold text-2xl mb-2 max-md:text-lg">Payment History for <br> Dr.
                {{ $appointment->dentist->dentist_last_name }}
                {{ $appointment->dentist->dentist_first_name }} Appointment</h2>

            <div class="w-full flex justify-between mb-2 ">
                <div class="w-1/2 text-left">
                    <h4 class="max-md:text-xs mb-1">Patient: </h4>
                    <h4 class="max-md:text-xs mb-1"> Procedure: </h4>
                    <h4 class="max-md:text-xs mb-1"> Appointment Date: </h4>
                    <h4 class="max-md:text-xs mb-1"> Total Amount Due: </h4>
                    <h4 class="max-md:text-xs mb-1"> Total Paid: </h4>
                    <h4 class="max-md:text-xs mb-1"> Remaining Balance: </h4>
                </div>
                <div class=" w-1/2 text-right">
                    <h2 class="max-md:text-xs font-semibold mb-1">
                        {{ $appointment->patient->last_name }} {{ $appointment->patient->first_name }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">
                        {{ $appointment->procedure->name }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">
                        {{ $appointment->appointment_date }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">&#8369
                        {{ number_format($appointment->procedure->price, 2) }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">&#8369
                        {{ number_format($totalPaid, 2) }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">&#8369
                        {{ number_format($balanceRemaining, 2) }}</h2>
                </div>
            </div>

            <table class="table w-full">
                <thead>
                    <tr class="pb-2 border-black border-b-2 text-center max-lg:text-xs">
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentHistory as $payment)
                        <tr>
                            <td class="border-b pb-2 px-2 max-xl:text-xs">{{ $payment->created_at->format('Y-m-d') }}</td>
                            <td class="border-b pb-2 px-2 max-xl:text-xs">&#8369;
                                {{ number_format($payment->paid_amount, 2) }}</td>
                            <td class="border-b pb-2 px-2 max-xl:text-xs">{{ ucfirst($payment->payment_method) }}</td>
                            <td class="border-b pb-2 px-2 max-xl:text-xs">{{ $payment->remarks ?? 'N/A' }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No payment history available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="w-full flex justify-between gap-2 mt-4">
                <a href=" {{ route('client.overview', $appointment->patient_id) }} "
                    class="flex w-full justify-center items-center py-2 px-16 text-center max-md:py-2 max-md:px-12 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                    type="reset">
                    Return
                </a>
            </div>
        </div>
    </section>
@endsection
