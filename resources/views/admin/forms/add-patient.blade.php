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
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto max-lg:p-3 max-lg:m-3  max-lg:mt-14">
        <h1 class="font-bold text-4xl px-4 max-md:text-2xl w-max">Add new patient</h1>
        <form method="POST" action="{{ route('store.patient') }}" class="">
            @method('POST')
            @csrf
            <div
                class="flex max-lg:flex-col flex-wrap items-start justify-start  gap-4 max-md:gap-4 max-w-4xl p-8 max-md:p-2">
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="first_name">
                    <h1 class="max-md:text-sm">First name</h1>
                    <input
                        class="max-md:text-sm max-md:py-1 max-md:px-2 border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                        name="first_name" type="text" id="first_name" autocomplete="off" placeholder="Juan"
                        value="{{ old('first_name') }}" oninput="validateInput('first_name')">
                    @error('first_name')
                        <span id="first_name_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="last_name">
                    <h1 class="max-md:text-sm">Last name</h1>
                    <input class="max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md"
                        name="last_name" type="text" id="last_name" autocomplete="off" placeholder="Dela Cruz"
                        value="{{ old('last_name') }}" oninput="validateInput('last_name')">
                    @error('last_name')
                        <span id="last_name_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="fb_name">
                    <h1 class="max-md:text-sm">Facebook name</h1>
                    <input class="max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md"
                        name="fb_name" type="text" autocomplete="off" id="fb_name" placeholder="Dela Cruz"
                        value="{{ old('fb_name') }}" oninput="validateInput('fb_name')">
                    @error('fb_name')
                        <span id="fb_name_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>

                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="email">
                    <h1 class="max-md:text-sm">Email</h1>
                    <input class="max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md"
                        name="email" type="email" id="email" autocomplete="off" placeholder="juan@gmail.com"
                        value="{{ old('email') }}" oninput="validateInput('email')">
                    @error('email')
                        <span id="email_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="gender">
                    <h1 class="max-md:text-sm">Gender</h1>
                    <select class="border max-md:text-sm max-md:py-1 max-md:px-2 border-gray-400 py-2 px-4 rounded-md"
                        name="gender" id="gender" oninput="validateInput('gender')">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="others">Others</option>
                        <option value="prefer not to say">Prefer not to say</option>
                    </select>
                    @error('gender')
                        <span id="gender_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="date_of_birth">
                    <h1 class="max-md:text-sm">Date of birth</h1>
                    <input class="max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md"
                        name="date_of_birth" type="date" id="date_of_birth" value="{{ old('date_of_birth') }}"
                        oninput="validateInput('date_of_birth')">
                    @error('date_of_birth')
                        <span id="date_of_birth_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="password">
                    <h1 class="max-md:text-sm">Password</h1>
                    <input class="max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md"
                        name="password" type="password" id="password" autocomplete="off""
                        oninput="validateInput('password')">
                    @error('password')
                        <span id="password_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="password_confirmation">
                    <h1 class="max-md:text-sm">Confirm Password</h1>
                    <input class="max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md"
                        type="password" name="password_confirmation" id="password_confirmation"
                        oninput="validateInput('password_confirmation')">
                    @error('password_confirmation')
                        <span id="password_confirmation_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>

                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="phone_number">
                    <h1 class="max-md:text-sm">Phone number</h1>
                    <input class="max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md"
                        name="phone_number" type="number" autocomplete="off" id="phone_number"
                        value="{{ old('phone_number') }}" oninput="validateInput('phone_number')">
                    @error('phone_number')
                        <span id="phone_number_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full " for="branch_id">
                    <h1 class="max-md:text-xs">Select branch</h1>
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
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full " for="patient_type">
                    <h1 class="max-md:text-xs">Type of patients</h1>
                    <select class="border max-md:text-sm max-md:py-1 max-md:px-2 border-gray-400 py-2 px-4 rounded-md"
                        name="patient_type" id="patient_type" oninput="validateInput('patient_type')">
                        <option class="max-md:text-xs" value="">Select patient type</option>
                        <option value="Walk-in">Walk-in</option>
                        <option value="Orthodontics">Orthodontics</option>
                        <option value="Insurance">Insurance</option>
                    </select>
                </label>
                <label class="flex flex-col flex-1 min-w-[30%] max-lg:w-full" for="next_visit">
                    <h1 class="max-md:text-sm">Date of next visit</h1>
                    <input class="max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md"
                        name="next_visit" type="date" autocomplete="off" id="next_visit"
                        value="{{ old('next_visit') }}" oninput="validateInput('next_visit')">
                    @error('next_visit')
                        <span id="next_visit_error"
                            class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                    @enderror
                </label>
                <div class="w-full">
                    <label for="hmo">Do you have an HMO?</label>
                    <input type="hidden" name="has_hmo" value="0">
                    <input type="checkbox" id="hmo" name="has_hmo" value="1" onclick="toggleHmoFields()">
                </div>

                <div id="hmoFields" style="display:none;" class="flex flex-col flex-1 ">
                    <div class="flex gap-4 max-md:gap-2">
                        <div class="flex flex-col flex-1">
                            <label for="hmo_company">
                                <h1 class="max-md:text-sm">Select HMO Company</h1>
                            </label>
                            <select id="hmo_company" name="hmo_company" onchange="toggleOtherHmoField()"
                                class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2">
                                <option value="">Select HMO</option>
                                <option value="HMO1">HMO1</option>
                                <option value="HMO2">HMO2</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div id="otherHmo" style="display:none;" class="flex flex-col flex-1">
                            <label for="other_hmo_name">
                                <h1 class="max-md:text-sm">Enter HMO Company Name:</h1>
                            </label>
                            <input type="text" id="other_hmo_name" name="other_hmo_name"
                                class="w-full max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md">
                        </div>
                    </div>
                    <div class="flex gap-4 max-md:gap-2">
                        <label for="hmo_number" class="flex flex-col flex-1">
                            <h1 class="max-md:text-sm">HMO Number:</h1>
                            <input type="text" id="hmo_number" name="hmo_number"
                                class="w-full max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md">
                        </label>
                        <label for="hmo_type" class="flex flex-col flex-1">
                            <h1 class="max-md:text-sm">Type of HMO:</h1>
                            <input type="text" id="hmo_type" name="hmo_type"
                                class="w-full max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md">
                        </label>
                    </div>
                </div>

            </div>
            <div class="w-full flex gap-2 px-8 mb-3">
                <button
                    class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                    type="submit">
                    Add patient
                </button>
                <button
                    class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                    type="reset">
                    Reset
                </button>
                <a href=" {{ route('patient.active') }} "
                    class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                    type="reset">
                    Cancel
                </a>
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

        function toggleHmoFields() {
            const hmoCheckbox = document.getElementById('hmo');
            const hmoFields = document.getElementById('hmoFields');
            hmoFields.style.display = hmoCheckbox.checked ? 'block' : 'none';

            if (!hmoCheckbox.checked) {
                document.getElementById('hmo_company').value = '';
                toggleOtherHmoField(); // Hide the other HMO field if checkbox is unchecked
            }
        }

        function toggleOtherHmoField() {
            const hmoCompanySelect = document.getElementById('hmo_company');
            const otherHmo = document.getElementById('otherHmo');
            otherHmo.style.display = hmoCompanySelect.value === 'other' ? 'block' : 'none';
        }
    </script>
@endsection
