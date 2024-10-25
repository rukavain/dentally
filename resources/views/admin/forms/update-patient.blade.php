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
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto max-lg:m-3 max-lg:p-3 max-lg:mt-14">
        <h1 class="font-bold text-5xl p-4 max-md:text-2xl">Update patient information</h1>
        <form method="POST" action="{{ route('update.patient', $patient->id) }}">
            @method('PUT')
            @csrf
            <div class="flex flex-col items-center">

                <div class="flex flex-wrap flex-row items-start justify-start gap-6 max-md:gap-2 max-w-4xl p-8 max-md:p-4">
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="first_name">
                        <h1>First name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md" name="first_name" type="text"
                            id="first_name" value="{{ old('firstname', $patient->first_name) }}" autocomplete="off"
                            placeholder="Juan" oninput="validateInput('first_name')">
                        @error('first_name')
                            <span id="first_name_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="last_name">
                        <h1>Last name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md" name="last_name" type="text"
                            id="last_name" value="{{ old('last_name', $patient->last_name) }}" autocomplete="off"
                            placeholder="Dela Cruz" oninput="validateInput('last_name')">
                        @error('last_name')
                            <span id="last_name_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="gender">
                        <h1>Gender</h1>
                        <select class="border border-gray-400 py-2 px-4 rounded-md" name="gender" id="gender"
                            oninput="validateInput('gender')">
                            <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male
                            </option>
                            <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>
                                Female</option>
                            <option value="others" {{ old('gender', $patient->gender) == 'others' ? 'selected' : '' }}>
                                Others</option>
                            <option value="prefer-not-to-say"
                                {{ old('gender', $patient->gender) == 'prefer-not-to-say' ? 'selected' : '' }}>Prefer not to
                                say</option>
                        </select>
                        @error('gender')
                            <span id="gender_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="date_of_birth">
                        <h1>Date of birth</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md" name="date_of_birth" type="date"
                            value="{{ old('date_of_birth', $patient->date_of_birth) }}" id="date_of_birth"
                            oninput="validateInput('date_of_birth')">
                        @error('date_of_birth')
                            <span id="date_of_birth_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="fb_name">
                        <h1>Facebook name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md" name="fb_name" type="text"
                            autocomplete="off" id="fb_name" value="{{ old('fb_name', $patient->fb_name) }}"
                            placeholder="Dela Cruz" oninput="validateInput('package')">
                        @error('fb_name')
                            <span id="fb_name_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="phone_number">
                        <h1>Phone number</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md" name="phone_number" type="number"
                            autocomplete="off" oninput="validateInput('phone_number')"
                            value="{{ old('phone_number', $patient->phone_number) }}" id="phone_number">
                        @error('phone_number')
                            <span id="phone_number_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] " for="branch_id">
                        <h1 class="max-md:text-xs">Select Branch</h1>
                        <select class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2"
                            id="branch_id" name="branch_id" required>
                            <option class="max-md:text-xs" value="">Select your branch</option>
                            @foreach ($branches as $branch)
                                <option class="max-md:text-xs" value="{{ $branch->id }}"
                                    {{ $branch->id == $patient->branch_id ? 'selected' : '' }}>
                                    {{ $branch->branch_loc }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="next_visit">
                        <h1>Date of next visit</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md" name="next_visit" type="date"
                            value="{{ old('next_visit', $patient->next_visit) }}" autocomplete="off" id="next_visit"
                            oninput="validateInput('next_visit')">
                        @error('next_visit')
                            <span id="next_visit_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>

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
                    <a class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                        @if ($patient->is_archived == '0') href=" {{ route('patient.active') }}"
                    @elseif ($patient->is_archived == '1') href=" {{ route('patient.archived') }} " @endif>
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </section>
    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_of_next_visit').setAttribute('min', today);


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
