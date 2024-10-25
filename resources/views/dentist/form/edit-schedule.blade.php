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

        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Ensure it appears above other content */
        }

        .spinner {
            border: 8px solid #f3f3f3;
            /* Light grey */
            border-top: 8px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto  max-lg:mt-14">
        <div class="p-4">
            <h1 class="font-bold text-3xl pb-2">Edit dentist schedule of Dr. {{ $schedule->dentist->dentist_last_name }}
                {{ $schedule->dentist->dentist_first_name }}</h1>
            <div class="flex justify-between">
                <div class="text-left">
                    <h1 class="max-md:text-xs mb-1">Current date: </h1>
                    <h1 class="max-md:text-xs mb-1">Current start-time: </h1>
                    <h1 class="max-md:text-xs mb-1">Current end-time: </h1>
                </div>
                <div class="text-right">
                    <h2 class="max-md:text-xs mb-1 font-semibold">{{ $schedule->date }}</h2>
                    <h2 class="max-md:text-xs mb-1 font-semibold">{{ $schedule->start_time }}</h2>
                    <h2 class="max-md:text-xs mb-1 font-semibold">{{ $schedule->end_time }}</h2>
                </div>
            </div>
        </div>
        <form action="{{ route('schedule.update', $schedule->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="flex flex-col items-start justify-start gap-8 max-w-4xl p-4">
                <div class="w-full ">
                    {{-- Original Approach --}}
                    <label class="flex flex-col flex-1 pb-4" for="date">
                        <h1>Date</h1>
                        <input class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                            name="date" type="date" id="date" autocomplete="off" placeholder="Juan"
                            value="{{ old('date') }}" oninput="validateInput('date')">
                        @error('date')
                            <span id="date_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="flex flex-wrap flex-1 gap-4 pb-4">
                        <label class="flex flex-col flex-1 pb-4" for="start_time">
                            <h1>Start Time</h1>
                            <input class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                                name="start_time" type="time" id="start_time" step="600"
                                value="{{ old('start_time') }}" oninput="validateInput('start_time')">
                            @error('start_time')
                                <span id="start_time_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col flex-1 pb-4" for="end_time">
                            <h1>End Time</h1>
                            <input class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                                name="end_time" type="time" id="end_time" step="600" value="{{ old('end_time') }}"
                                oninput="validateInput('end_time')">
                            @error('end_time')
                                <span id="end_time_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                    <input type="hidden" id="appointment_duration" name="appointment_duration" value="60">

                </div>
                <div class="w-full flex gap-2 px-8 mb-3">
                    <button
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                        type="submit">
                        Update
                    </button>
                    <button
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                        type="reset">
                        Reset
                    </button>
                    <a href=" {{ route('schedule') }} "
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                        type="reset">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </section>
    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('appointment_date').setAttribute('min', today);

        function validateInput(field) {
            const input = document.getElementById(field);
            const errorElement = document.getElementById(`${field}_error`);

            // Assuming that the presence of errorElement means the field has an error initially
            if (errorElement) {
                // If the input is valid (e.g., not empty), hide the error message
                if (input.value.trim() !== '') {
                    errorElement.classList.remove('show');
                    errorElement.classList.add('hide');

                    setTimeout(() => {
                        errorElement.style.display = 'none';
                    }, 500);
                } else {
                    // If the input is still invalid, keep the error message visible
                    errorElement.classList.remove('hide');
                    errorElement.classList.add('show');
                }
            }
        }
    </script>
@endsection
