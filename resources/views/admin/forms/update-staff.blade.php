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
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto max-lg:mt-14 m-6">
        <h1 class="font-bold text-5xl p-4 max-md:text-2xl">Update Staff information</h1>
        <form method="POST" action="{{ route('update.staff', $staff->id) }}">
            @method('PUT')
            @csrf
            <div class="flex flex-col items-center">

                <div class="flex flex-wrap items-start justify-start gap-8 max-md:gap-4 max-w-4xl p-8 max-md:p-2 flex-row">
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="first_name">
                        <h1>First name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" name="first_name"
                            type="text" id="first_name" value="{{ old('firstname', $staff->first_name) }}"
                            autocomplete="off" placeholder="Juan" oninput="validateInput('first_name')">
                        @error('first_name')
                            <span id="first_name_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="last_name">
                        <h1>Last name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" name="last_name"
                            type="text" id="last_name" value="{{ old('last_name', $staff->last_name) }}"
                            autocomplete="off" placeholder="Dela Cruz" oninput="validateInput('last_name')">
                        @error('last_name')
                            <span id="last_name_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="fb_name">
                        <h1 class="max-md:text-sm">Facebook Name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="fb_name" type="text" id="fb_name" autocomplete="off"
                            value="{{ old('fb_name', $staff->fb_name) }}" oninput="validateInput('fb_name')">
                        @error('fb_name')
                            <span id="email_error"
                                class="validation-message text-white max-md:text-sm bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="phone_number">
                        <h1>Phone number</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" name="phone_number"
                            type="number" autocomplete="off" oninput="validateInput('phone_number')"
                            value="{{ old('phone_number', $staff->phone_number) }}" id="phone_number">
                        @error('phone_number')
                            <span id="phone_number_error"
                                class="validation-message text-white bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%] pb-4" for="branch_id">
                        <h1 class="max-md:text-sm">Branch</h1>
                        <select
                            class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            id="branch_id" name="branch_id" required>
                            <option value="1" {{ old('branch_id', $staff->branch_id) === 1 ? 'selected' : '' }}> Dau
                            </option>
                            <option value="2" {{ old('branch_id', $staff->branch_id) === 2 ? 'selected' : '' }}>
                                Angeles
                            </option>
                            <option value="3" {{ old('branch_id', $staff->branch_id) === 3 ? 'selected' : '' }}>
                                Sindalan</option>
                        </select>
                        @error('branch_id')
                            <span id="branch_id_error"
                                class="validation-message text-white max-md:text-sm bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
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
                        href=" {{ route('staff') }} ">
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
