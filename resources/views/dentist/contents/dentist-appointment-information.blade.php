@extends('admin.dashboard')
@section('content')
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4">
        @include('components.search')
    </div>
    <section class="flex flex-col justify-center items-start bg-white border">
        <section class="p-6 border-b flex flex-1 justify-center items-start max-xl:mt-12">
            @if ($appointment->pending === 'Approved')
                <div class="flex justify-center items-center gap-3">
                    <img class="h-10" src="{{ asset('assets/images/check-icon.png') }}" alt="">
                    <div>
                        <h1 class="font-bold text-xl text-green-600 max-xl:text-md">Appointment Approved!</h1>
                        <h1 class="text-sm text-green-600">This appointment for {{ $appointment->patient->last_name }}
                            {{ $appointment->patient->first_name }} has been approved by Dr.
                            {{ $appointment->dentist->dentist_last_name }}
                            {{ $appointment->dentist->dentist_first_name }}</h1>
                    </div>
                </div>
            @elseif($appointment->pending === 'Declined')
                <div class="flex justify-center items-center gap-3">
                    <img class="h-10" src="{{ asset('assets/images/decline-icon.png') }}" alt="">
                    <div>
                        <h1 class="font-bold text-xl text-red-600 max-xl:text-md"> Appointment Declined!</h1>
                        <h1 class="text-sm text-red-600">This appointment for {{ $appointment->patient->last_name }}
                            {{ $appointment->patient->first_name }} has been declined by Dr.
                            {{ $appointment->dentist->dentist_last_name }}
                            {{ $appointment->dentist->dentist_first_name }}</h1>
                    </div>
                </div>
            @else
                <div class="flex flex-col justify-center items-start gap-4">
                    <div>
                        <img class="h-10" src="{{ asset('assets/images/pending.png') }}" alt="">
                    <div>
                        <h1 class="font-bold text-xl text-gray-900 max-xl:text-md"> Appointment Pending</h1>
                        <h1 class="text-sm ">This appointment for {{ $appointment->patient->last_name }}
                            {{ $appointment->patient->first_name }} is waiting for its response.</h1>
                    </div>
                    </div>
                    <div class="flex justify-center m-0 p-0 items-center gap-2 ">
                        <form method="POST" action="{{ route('appointments.approve', $appointment->id) }}">
                            @csrf
                            <div class="tooltip">
                                <button type="submit"
                                    class="border-2 rounded-md border-green-600 text-sm font-semibold py-2 px-6">
                                    Approve
                                </button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('appointments.decline', $appointment->id) }}">
                            @csrf
                            <div class="tooltip">
                                <button type="submit"
                                    class="border-2 rounded-md border-red-600 text-sm font-semibold py-2 px-6">
                                    Decline
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            @endif
        </section>
        <section class="flex flex-wrap justify-between w-full">
            <section
                class="p-8 max-xl:p-3 border-2 m-3 max-xl:m-1 rounded-md w-full max-w-3xl flex flex-col flex-1 justify-center items-start">
                <h1 class="font-semibold text-gray-400 text-sm pb-3">Appointment</h1>
                <h1 class="font-semibold text-2xl max-lg:text-lg mb-10 text-left max-w-1xl min-w-2xl ">Appointment Details
                    for
                    <span class="font-bold text-2xl max-lg:text-lg">
                        {{ $appointment->patient->last_name }}
                        {{ $appointment->patient->first_name }}</span>
                </h1>
                <div class="flex justify-between items-between w-full ">
                    <div
                        class="flex gap-4 flex-col justify-start items-start text-gray-500 font max-xl:text-xs max-xl:gap-8">
                        <h1 class="border-b w-full max-lg:border-none">Dentist:</h1>
                        <h1 class="border-b w-full max-lg:border-none">Birth date:</h1>
                        <h1 class="border-b w-full max-lg:border-none">Email:</h1>
                        <h1 class="border-b w-full max-lg:border-none">Branch:</h1>
                        <h1 class="border-b w-full max-lg:border-none">Appointment Date:</h1>
                        <h1 class="border-b w-full max-lg:border-none">Preferred Time:</h1>
                        <h1 class="border-b w-full max-lg:border-none">Phone number:</h1>
                    </div>
                    <div
                        class="flex gap-4  flex-col text-right justify-start items-end font-semibold max-xl:text-xs max-xl:gap-8">
                        <h1 class="border-b w-full max-lg:border-none"> Dr. {{ $appointment->dentist->dentist_last_name }}
                            {{ $appointment->dentist->dentist_first_name }}</h1>
                        <h1 class="border-b w-full max-lg:border-none"> {{ $appointment->patient->date_of_birth }}</h1>
                        <h1 class="border-b w-full max-lg:border-none">{{ $appointment->patient->email }}</h1>
                        <h1 class="border-b w-full max-lg:border-none"> {{ $appointment->branch->branch_loc }}</h1>
                        <h1 class="border-b w-full max-lg:border-none">{{ $appointment->appointment_date }}</h1>
                        <h1 class="border-b w-full max-lg:border-none">{{ $appointment->preferred_time }}</h1>
                        <h1 class="border-b w-full max-lg:border-none">{{ $appointment->patient->phone_number }}</h1>
                    </div>
                </div>
            </section>
            <section
                class="p-5 border-2 rounded-md m-3 max-xl:m-1 w-full max-w-3xl flex flex-1  flex-col justify-center items-start">
                <h1 class="font-semibold text-gray-400 text-sm pb-3">Payment</h1>
                <div class="mb-10 flex gap-2 flex-wrap text-2xl max-lg:text-xl">
                    <h1 class="font-semibold  max-lg:text-lg text-left max-w-1xl min-w-2xl ">Payment Details
                        for :
                    </h1>
                    <h1 class="font-bold max-lg:text-lg">
                        {{ $appointment->patient->last_name }}
                        {{ $appointment->patient->first_name }}</h1>
                </div>
                {{-- asdadasddddddddddddddddddddddddasdasddssdasdasdasdasd --}}
                <div class="flex flex-col justify-between w-full max-2xl:flex-wrap p-2 max-md:mb-4">
                    <div class="flex justify-between items-between w-full ">
                        <div
                            class="flex gap-4 flex-col justify-start items-start text-gray-500 font max-xl:text-xs max-xl:gap-8">
                            <h1 class="border-b w-full max-lg:border-none">Procedure:</h1>
                            <h1 class="border-b w-full max-lg:border-none">Appointment Date:</h1>
                            <h1 class="border-b w-full max-lg:border-none">Total Amount Due:</h1>
                            <h1 class="border-b w-full max-lg:border-none">Status:</h1>
                        </div>
                        <div
                            class="flex gap-4  flex-col text-right justify-start items-end font-semibold max-xl:text-xs max-xl:gap-8">
                            <h1 class="border-b w-full max-lg:border-none"> {{ $appointment->procedure->name ?? 'None' }}
                            </h1>
                            <h1 class="border-b w-full max-lg:border-none"> {{ $appointment->appointment_date }}</h1>
                            <h1 class="border-b w-full max-lg:border-none">&#8369;
                                {{ $appointment->procedure->price ?? '0' }}
                            </h1>
                            <h1 class="border-b w-full max-lg:border-none">
                                @if (is_null($appointment->payment) || $appointment->payment->status === null)
                                    No payment status yet
                                @else
                                    {{ $appointment->payment->status }}
                                @endif
                            </h1>
                        </div>
                    </div>
                    <div>
                        @if ($appointment->pending === 'Pending')
                            <h1 class="text-xl font-bold max-md:text-3xl mb-4 max-lg:mb-2">Waiting to be approved</h1>
                        @elseif ($appointment->pending === 'Declined')
                            <h1 class="text-xl font-bold max-md:text-3xl mb-4 max-lg:mb-2">Appointment has been declined
                            </h1>
                        @else
                            @if (is_null($appointment->payment))
                                <a href="{{ route('dentist.paymentForm', $appointment->id) }}"
                                    class="flex items-center justify-start gap-2 py-2 px-4 my-2 border border-gray-500 w-max rounded-md hover:border-gray-700 hover:shadow-sm transition-all max-sm:justify-center">
                                    <img class="h-8 max-lg:h-4" src="{{ asset('assets/images/payment.png') }}"
                                        alt="">
                                    <h1 class="max-lg:text-xs">Add payment</h1>
                                </a>
                            @elseif ($appointment->payment->status === 'Pending')
                                <div class="mt-6 flex gap-2 flex-grow-0 self-end">
                                    <a href="{{ route('dentist.paymentForm', $appointment->id) }}"
                                        class="flex items-center justify-start gap-2 px-4 my-2 border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all max-sm:justify-center w-max">
                                        <img class="h-5 max-lg:h-4" src="{{ asset('assets/images/payment.png') }}"
                                            alt="">
                                        <h1 class="max-lg:text-xs text-sm">Add payment</h1>
                                    </a>
                                    <a href="{{ route('dentist.paymentHistory', $appointment->id) }}"
                                        class="flex items-center justify-start gap-2 py-2 px-4 my-2 border border-gray-500 w-max rounded-md hover:border-gray-700 hover:shadow-sm transition-all max-sm:justify-center">
                                        <img class="h-5 max-lg:h-4"
                                            src="{{ asset('assets/images/transaction-history.png') }}" alt="">
                                        <h1 class="max-lg:text-xs text-sm">Payment history</h1>
                                    </a>
                                </div>
                            @elseif ($appointment->payment->status === 'Paid')
                                <a href="{{ route('dentist.paymentHistory', $appointment->id) }}"
                                    class="flex items-center justify-start gap-2 py-2 px-4 my-2 border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all max-sm:justify-center">
                                    <img class="h-8 max-lg:h-4" src="{{ asset('assets/images/transaction-history.png') }}"
                                        alt="">
                                    <h1 class="max-lg:text-xs">Payment history</h1>
                                </a>
                            @elseif ($appointment->payment->status === 'Declined')
                                <p>Payment has been declined</p>
                            @endif
                        @endif
                    </div>
                </div>
            </section>
        </section>

        {{-- asdadasddddddddddddddddddddddddasdasddssdasdasdasdasd --}}
    </section>

    {{-- <section class="bg-white p-8 m-2 shadow-lg rounded-md flex flex-col justify-center z-0  max-lg:mt-14">
        <div class="pb-4">
            <a @if ($appointment->pending === 'Pending') href=" {{ route('appointments.pending', $appointment->dentist_id) }}"
            @elseif($appointment->pending === 'Approved') href=" {{ route('appointments.approved', $appointment->dentist_id) }}" @endif
                class="flex justify-start font-semibold max-lg:text-xs border-gray-600 py-1 max-lg:px-2 w-max gap-2"><img
                    class="h-6" src="{{ asset('assets/images/arrow-back.png') }}" alt=""> Back</a>
        </div>
        <div class="flex justify-around p-2 max-sm:flex-col">
            <div class="flex flex-col max-2xl:flex-wrap p-2 max-md:mb-4">
                <h1 class="text-2xl font-bold max-md:text-3xl mb-4 max-lg:mb-2">
                    Appointment Information
                </h1>
                <div class="flex flex-col gap-2 max-md:gap-1 text-md max-md:text-xs">
                    <h1 class="max-md:text-xs"> Patient: <span class="font-semibold">
                            {{ $appointment->patient->last_name }}
                            {{ $appointment->patient->first_name }}
                        </span>
                    </h1>10004;
                    <h1 class="max-md:text-xs"> Dentist: <span class="font-semibold">
                            Dr. {{ $appointment->dentist->dentist_last_name }}
                            {{ $appointment->dentist->dentist_first_name }}
                        </span>
                    </h1>
                    <h1 class="max-md:text-xs"> Birth date: <span class="font-semibold">
                            {{ $appointment->patient->date_of_birth }}
                        </span>
                    </h1>
                    <h1 class="max-md:text-xs"> Phone number: <span class="font-semibold">
                            {{ $appointment->patient->phone_number }}
                        </span>
                    </h1>
                    <h1 class="max-md:text-xs"> Email: <span class="font-semibold">
                            {{ $appointment->patient->email }}
                        </span> </h1>
                    <h1 class="max-md:text-xs"> Branch: <span class="font-semibold">
                            {{ $appointment->branch->branch_loc }}
                        </span> </h1>
                    <h1 class="max-md:text-xs"> Appointment date: <span class="font-semibold">
                            {{ $appointment->appointment_date }}
                        </span> </h1>
                    <h1 class="max-md:text-xs"> Preferred time: <span class="font-semibold">
                            {{ $appointment->preferred_time }}
                        </span> </h1>
                    <h1 class="max-md:text-xs"> Notes: <span class="font-semibold"> Wala pa
                            {{ $appointment->notes }}
                        </span> </h1>

                </div>

                <div class="flex flex-col max-2xl:flex-wrap text-lg mt-5 max-md:mt-2 text-left max-w-min">
                    @if ($appointment->pending === 'Approved')
                        <div class="tooltip">
                            <div class="flex gap-3">
                                <h1 class="font-bold text-green-500">Approved</h1>
                                <span class="tooltiptext">Approved</span>
                            </div>
                        </div>
                    @elseif($appointment->pending === 'Declined')
                        <div class="tooltip">
                            <div class="flex gap-3">
                                <h1 class="font-bold text-red-500">Declined</h1>
                                <span class="tooltiptext">Declined</span>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-center m-0 p-0 items-center gap-2 ">
                            <form method="POST" action="{{ route('appointments.approve', $appointment->id) }}">
                                @csrf
                                <div class="tooltip">
                                    <button type="submit"
                                        class="border-2 rounded-md border-green-600 text-sm font-semibold py-2 px-6">
                                        Approve
                                    </button>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('appointments.decline', $appointment->id) }}">
                                @csrf
                                <div class="tooltip">
                                    <button type="submit"
                                        class="border-2 rounded-md border-red-600 text-sm font-semibold py-2 px-6">
                                        Decline
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section> --}}
@endsection
