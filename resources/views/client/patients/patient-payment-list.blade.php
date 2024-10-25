@extends('admin.dashboard')
@section('content')
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class=" m-4 p-4 bg-white shadow-lg rounded-md max-lg:mt-14">
        <div class="flex flex-col items-start gap-3 py-2  max-lg:flex-wrap">
            <div class="">
                <a href="{{ route('show.patient', $patient->id) }}"
                    class="flex justify-start font-semibold max-lg:text-xs border-gray-600 py-1 max-lg:px-2 w-max gap-2"><img
                        class="h-6" src="{{ asset('assets/images/arrow-back.png') }}" alt=""> Back</a>
            </div>
            <label class="flex items-center gap-2" for="time">
                <h1 class="font-bold text-3xl mr-4 max-md:mr-0 max-md:text-2xl">Payment List</h1>
            </label>
        </div>
        <table class="w-full table-auto mb-2 overflow-hidden">
            @if ($payments->isEmpty())
                <div class=" rounded-md my-3 flex flex-1 justify-center items-center flex-col gap-8 py-4">

                    <img class="h-56" src="{{ asset('assets/images/payment.png') }}" alt="">
                    <div class="text-center flex flex-col justify-center items-center gap-2 ">
                        <h1 class="text-4xl font-semibold">No payments currently. </h1>
                        <h1 class="text-sm">There are no payments currently. </h1>
                    </div>
                </div>
            @else
                <thead>
                    <tr class="bg-green-200 text-green-700">
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Procedure name</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Appointment to</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Amount due</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Status</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Actions</th>
                    </tr>
                </thead>

                <tbody class="text-center">

                    @foreach ($payments as $payment)
                        <tr class="border-b-2">
                            {{-- <td class="border px-4 py-2 max-md:py-1 max-md"> {{ $payment->id }}</td> --}}
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $payment->procedure->name }}
                            </td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs"> Dr.
                                {{ $payment->dentist->dentist_last_name . ' ' . $payment->dentist->dentist_first_name }}
                            </td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $payment->procedure->price }}
                            </td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                                @if (is_null($payment->payment))
                                    <h1 class="text-xs  font-semibold bg-red-200 text-red-700 rounded-full py-1">
                                        &#9679; No Payment</h1>
                                @elseif ($payment->payment->status === 'Paid')
                                    <h1 class="text-xs  font-semibold bg-green-200 text-green-700 rounded-full py-1">
                                        &#9679; Paid</h1>
                                @elseif ($payment->payment->status === 'Pending')
                                    <h1 class="text-xs  font-semibold bg-slate-200 text-slate-700 rounded-full py-1">
                                        &#9679; Pending</h1>
                                @endif
                            </td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs max-md:flex">
                                @if (is_null($payment->payment))
                                    <div class=" flex gap-2 justify-center flex-wrap items-center">
                                        <a class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-300 transition-all"
                                            href="{{ route('payments.form', $payment->id) }}">
                                            <h1 class=" text-xs text-gray-700 text-center">Add Payment</h1>
                                        </a>
                                    </div>
                                @elseif ($payment->payment->status === 'Pending')
                                    <div class=" flex gap-2 justify-center flex-wrap items-center">
                                        <a class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-300 transition-all"
                                            href="{{ route('payments.form', $payment->id) }}">
                                            <h1 class=" text-xs text-gray-700 text-center">Add Payment</h1>
                                        </a>
                                    </div>
                                @elseif ($payment->payment->status === 'Paid')
                                    <div class=" flex gap-2 justify-center flex-wrap items-center">
                                        <a class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-300 transition-all"
                                            href="{{ route('payments.history', $payment->id) }}">
                                            <h1 class=" text-xs text-gray-700 text-center">Payment Record</h1>
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
        <div>
            <form method="GET" action="{{ route('payments.pending', $patient->id) }}">
                @csrf
                <button
                    class="flex justify-center items-center gap-2  rounded-md py-2 px-4 min-w-max border-2 border-gray-600 hover:shadow-md hover:border-green-700 font-semibold text-gray-800 transition-all max-md:px-2">
                    <span class="max-md:text-xs">
                        <span
                            class=" bg-green-200 text-green-700 rounded-full py-1 px-3">{{ $pendingPayments > 0 ? $pendingPayments : '' }}
                        </span>
                        Pending payment
                    </span>
                </button>
            </form>
        </div>
        @endif

    </section>
    <script>
        document.querySelectorAll('[id^="delete_modal_"]').forEach((modal) => {
            if (modal) {
                const modalId = modal.id;
                const button = document.querySelector(
                    `[onclick="document.getElementById('${modalId}').showModal()"]`);

                if (button) {
                    button.addEventListener('click', () => {
                        modal.showModal();
                    });
                }
            }
        });
    </script>
@endsection
