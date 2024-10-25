<!-- resources/views/admin/content/add-payment.blade.php -->

@extends('admin.dashboard')

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
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto  max-lg:mt-14">
        <h1 class="font-bold text-3xl mb-4 max-md:text-2xl">Add Payment for {{ $patient->first_name }}
            {{ $patient->last_name }}</h1>

        {{-- <h1 class="font-bold text-3xl mb-4 max-md:text-2xl">Appointment {{ $appointment->appointment_date }} -
            {{ $appointment->procedure->name }}</h1> --}}

        <form action="{{ route('store.payment', $patient->id) }}" method="POST">
            @csrf
            <div class="flex flex-col items-start justify-start gap-8 max-w-4xl p-4">
                <div class="w-full ">

                    <label class="flex flex-col flex-1 pb-4" for="appointment_id">
                        <h1>Select Appointment</h1>
                        <select class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                            id="appointment_id" name="appointment_id" required>
                            <option class="max-md:text-xs" value="">Select Appointment</option>
                            @foreach ($appointments as $appointment)
                                <option value="{{ $appointment->id }}">
                                    {{ $appointment->appointment_date }} - {{ $appointment->procedure->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <input type="hidden" id="patient_id" name="patient_id" value="{{ $patient->id }}">

                    <label class="flex flex-col flex-1 pb-4" for="amount">
                        <h1>Enter Amount</h1>
                        <input class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                            type="text" id="amount" name="amount" placeholder="&#8369">
                    </label>
                    <label class="flex
                            flex-col flex-1 pb-4" for="payment_method">
                        <h1>Payment Method</h1>
                        <select class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                            id="payment_method" name="payment_method" required>
                            <option class="max-md:text-xs" value="">Select Method</option>
                        </select>
                    </label>

                    <div class="flex gap-2 mt-4">
                        <button
                            class="flex justify-center items-center  py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                            type="submit">
                            Add Payment
                        </button>
                        <button
                            class="flex justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                            type="reset">
                            Reset
                        </button>
                        <a href=" {{ route('appointment.submission') }} "
                            class="flex justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                            type="reset">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
