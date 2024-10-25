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
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto  max-lg:mt-14">
        <h1 class="font-bold text-4xl px-4 max-md:text-2xl w-max">Add Staff</h1>
        <form method="POST" action="{{ route('store.staff') }}">
            @method('POST')
            @csrf
            <div>
                <div class="flex flex-wrap flex-row items-start justify-start gap-8 max-md:gap-4 max-w-4xl p-8 max-md:p-2">
                    <label class="flex flex-col flex-1 min-w-[45%]" for="first_name">
                        <h1 class="max-md:text-sm">First name</h1>
                        <input
                            class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="first_name" type="text" id="first_name" autocomplete="off" placeholder="Juan"
                            value="{{ old('first_name') }}" oninput="validateInput('first_name')">
                        @error('first_name')
                            <span id="first_name_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="last_name">
                        <h1 class="max-md:text-sm">Last name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="last_name" type="text" id="last_name" autocomplete="off" placeholder="Dela Cruz"
                            value="{{ old('last_name') }}" oninput="validateInput('last_name')">
                        @error('last_name')
                            <span id="last_name_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1 min-w-[45%]" for="email">
                        <h1 class="max-md:text-sm">Email</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="email" type="email" id="email" autocomplete="off" placeholder="juan@gmail.com"
                            value="{{ old('email') }}" oninput="validateInput('email')">
                        @error('email')
                            <span id="email_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="gender">
                        <h1 class="max-md:text-sm">Gender</h1>
                        <select
                            class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            id="gender" name="gender" required>
                            <option value="male"> Male</option>
                            <option value="female"> Female</option>
                            <option value="Prefer not to say"> Prefer not to say</option>
                        </select>
                        @error('gender')
                            <span id="gender_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="phone_number">
                        <h1 class="max-md:text-sm">Phone Number</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="phone_number" type="text" id="phone_number" autocomplete="off"
                            value="{{ old('phone_number') }}" oninput="validateInput('phone_number')">
                        @error('phone_number')
                            <span id="phone_number"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="fb_name">
                        <h1 class="max-md:text-sm">Facebook Name</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="fb_name" type="text" id="fb_name" autocomplete="off" placeholder="Juan"
                            value="{{ old('fb_name') }}" oninput="validateInput('fb_name')">
                        @error('fb_name')
                            <span id="email_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="password">
                        <h1 class="max-md:text-sm">Password</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="password" type="password" id="password" autocomplete="off"
                            oninput="validateInput('password')">
                        @error('password')
                            <span id="password_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="password_confirmation">
                        <h1 class="max-md:text-sm">Confirm Password</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            type="password" name="password_confirmation" id="password_confirmation"
                            oninput="validateInput('password_confirmation')">
                        @error('password_confirmation')
                            <span id="password_confirmation_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 min-w-[45%]" for="branch_id">
                        <h1 class="">Select Branch</h1>
                        <select class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-md:text-xs"
                            id="branch_id" name="branch_id" required>
                            <option class="max-md:text-xs" value="">Select branch</option>
                            @foreach ($branches as $branch)
                                <option class="max-md:text-xs" value="{{ $branch->id }}">
                                    {{ $branch->branch_loc }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="w-full flex gap-2 px-8 mb-3">
                    <button
                        class="flex-1 justify-center items-center  py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                        type="submit">
                        Add Staff
                    </button>
                    <button
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                        type="reset">
                        Reset
                    </button>
                    <a href=" {{ route('staff') }} "
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
