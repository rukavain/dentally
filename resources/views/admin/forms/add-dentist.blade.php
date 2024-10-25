@extends('admin.dashboard')
@section('content')
    <style>
        /* Fade-in and Fade-out CSS */
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
    <div class="m-4">
        @include('components.search')
    </div>
    <section class="max-w-max bg-white shadow-lg rounded-md p-6 my-4 mx-auto  max-lg:mt-14">
        <h1 class="font-bold text-4xl px-4 max-md:text-2xl w-max">Add Dentist</h1>
        <form method="POST" action="{{ route('store.dentist') }}">
            @method('POST')
            @csrf
            <div class="flex flex-col items-center">
                <div class="flex flex-wrap items-start justify-start gap-8 max-md:gap-4 max-w-4xl p-8 max-md:p-2 flex-row">
                    <label class="flex flex-col flex-1 min-w-[45%]" for="dentist_first_name">
                        <h1 class="max-md:text-xs">First name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            name="dentist_first_name" type="text" id="dentist_first_name" autocomplete="off"
                            placeholder="Juan" value="{{ old('dentist_first_name') }}"
                            oninput="validateInput('dentist_first_name')">
                        @error('dentist_first_name')
                            <span id="dentist_first_name_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="dentist_last_name">
                        <h1 class="max-md:text-xs">Last name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            name="dentist_last_name" type="text" id="dentist_last_name" autocomplete="off"
                            placeholder="Dela Cruz" value="{{ old('dentist_last_name') }}"
                            oninput="validateInput('dentist_last_name')">
                        @error('dentist_last_name')
                            <span id="dentist_last_name_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="dentist_email">
                        <h1 class="max-md:text-xs">Email</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            name="dentist_email" type="email" id="dentist_email" autocomplete="off"
                            placeholder="juan@gmail.com" value="{{ old('dentist_email') }}"
                            oninput="validateInput('dentist_email')">
                        @error('dentist_email')
                            <span id="dentist_email_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="dentist_phone_number">
                        <h1 class="max-md:text-xs">Phone Number</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            name="dentist_phone_number" type="number" id="dentist_phone_number" autocomplete="off"
                            value="{{ old('dentist_phone_number') }}" oninput="validateInput('dentist_phone_number')">
                        @error('dentist_phone_number')
                            <span id="dentist_phone_number"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="dentist_gender">
                        <h1 class="max-md:text-xs">Gender</h1>
                        <select class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            id="dentist_gender" name="dentist_gender" required>
                            <option value="male"> Male</option>
                            <option value="female"> Female</option>
                            <option value="Prefer not to say"> Prefer not to say</option>
                        </select>
                        @error('dentist_gender')
                            <span id="dentist_gender_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="dentist_birth_date">
                        <h1 class="max-md:text-xs">Date of birth</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            name="dentist_birth_date" type="date" id="dentist_birth_date"
                            value="{{ old('dentist_birth_date') }}" oninput="validateInput('dentist_birth_date')">
                        @error('dentist_birth_date')
                            <span id="dentist_birth_date_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="dentist_specialization">
                        <h1 class="max-md:text-xs">Dentist Specialization</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            type="text" name="dentist_specialization">
                        @error('dentist_specialization')
                            <span id="dentist_specialization_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] " for="branch_id">
                        <h1 class="max-md:text-xs">Select Branch</h1>
                        <select class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            id="branch_id" name="branch_id" required>
                            <option class="max-md:text-xs" value="">Select your branch</option>
                            @foreach ($branches as $branch)
                                <option class="max-md:text-xs" value="{{ $branch->id }}">
                                    {{ $branch->branch_loc }}
                                </option>
                            @endforeach
                        </select>
                        @if ($branches->isEmpty())
                            <h1 class="text-red-600 text-xs">There are currently no registered branches yet.</h1>
                        @endif
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="password">
                        <h1 class="max-md:text-xs">Password</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            name="password" type="password" id="password" autocomplete="off"
                            oninput="validateInput('password')">
                        @error('password')
                            <span id="password_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="password_confirmation">
                        <h1 class="max-md:text-xs">Confirm Password</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            type="password" name="password_confirmation" id="password_confirmation"
                            oninput="validateInput('password_confirmation')">
                        @error('password_confirmation')
                            <span id="password_confirmation_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>

                </div>
                <div class="w-full flex gap-2 px-8 mb-3">
                    <button
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                        type="submit">
                        Add Dentist
                    </button>
                    <button
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                        type="reset">
                        Reset
                    </button>
                    <a href=" {{ route('dentist') }} "
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
        document.getElementById('next_visit').setAttribute('min', today);

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
