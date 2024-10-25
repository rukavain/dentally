<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} | Register </title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
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
</head>

<body>
    <section class="h-svh bg-slate-100 flex justify-center items-center ">
        <div class="bg-white rounded-lg shadow-lg flex max-w-5xl max-lg:flex-col-reverse border border-gray-300">
            <div class="bg-green-600 max-lg:rounded-sm rounded-lg text-white p-8 flex flex-col justify-between">
                <h1 class="font-bold text-4xl text-white max-w-sm max-lg:text-3xl">Register account</h1>
                <form method="POST" action="{{ route('register') }}" class="flex flex-wrap max-w-3xl gap-4 mb-8 mt-4">
                    @method('POST')
                    @csrf

                    <label class="flex flex-col flex-1" for="first_name">
                        <h1 class="max-lg:text-sm">First name</h1>
                        <input
                            class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-lg:py-1 text-gray-900 max-lg:text-sm max-lg:px-2"
                            name="first_name" type="text" id="first_name" autocomplete="off" placeholder="Juan"
                            value="{{ old('first_name') }}" oninput="validateInput('first_name')">
                        @error('first_name')
                            <span id="first_name_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1" for="last_name">
                        <h1 class="max-lg:text-sm">Last name</h1>
                        <input
                            class="border border-gray-400 py-2 px-4 rounded-md max-lg:py-1 max-lg:text-sm text-gray-900 max-lg:px-2"
                            name="last_name" type="text" id="last_name" autocomplete="off" placeholder="Dela Cruz"
                            value="{{ old('last_name') }}" oninput="validateInput('last_name')">
                        @error('last_name')
                            <span id="last_name_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1 " for="gender">
                        <h1 class="max-lg:text-sm ">Gender</h1>
                        <select
                            class="border border-gray-400 py-2 px-4 rounded-md max-lg:py-1 text-gray-900 max-lg:text-sm max-lg:px-2"
                            name="gender" id="gender" oninput="validateInput('gender')">
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="others" {{ old('gender') == 'others' ? 'selected' : '' }}>Others</option>
                            <option value="prefer not to say"
                                {{ old('gender') == 'prefer not to say' ? 'selected' : '' }}>Prefer not to say
                            </option>
                        </select>
                        @error('gender')
                            <span id="gender_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1" for="date_of_birth">
                        <h1 class="max-lg:text-sm">Date of birth</h1>
                        <input
                            class="border border-gray-400 py-2 px-4 rounded-md max-lg:py-1 max-lg:text-sm max-lg:px-2 text-gray-900"
                            name="date_of_birth" type="date" id="date_of_birth" value="{{ old('date_of_birth') }}"
                            oninput="validateInput('date_of_birth')">
                        @error('date_of_birth')
                            <span id="date_of_birth_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1" for="email">
                        <h1 class="max-lg:text-sm">Email</h1>
                        <input
                            class="border border-gray-400 py-2 px-4 rounded-md max-lg:py-1 max-lg:text-sm max-lg:px-2 text-gray-900"
                            name="email" type="email" id="email" autocomplete="off" placeholder="juan@gmail.com"
                            value="{{ old('email') }}" oninput="validateInput('email')">
                        @error('email')
                            <span id="email_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1" for="fb_name">
                        <h1 class=" max-lg:text-sm">Facebook name</h1>
                        <input
                            class="border border-gray-400 py-2 px-4 rounded-md max-lg:py-1 max-lg:text-sm max-lg:px-2 text-gray-900"
                            name="fb_name" type="text" autocomplete="off" id="fb_name" placeholder="Dela Cruz"
                            value="{{ old('fb_name') }}" oninput="validateInput('fb_name')">
                        @error('fb_name')
                            <span id="fb_name_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col flex-1 " for="password">
                        <h1 class=" max-lg:text-sm">Password</h1>
                        <input
                            class="border border-gray-400 py-2 px-4 rounded-md max-lg:py-1 max-lg:text-sm max-lg:px-2 text-gray-900"
                            name="password" type="password" id="password" autocomplete="off"
                            oninput="validateInput('password')">
                        @error('password')
                            <span id="password_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1 " for="password_confirmation">
                        <h1 class=" max-lg:text-sm">Confirm Password</h1>
                        <input
                            class="border border-gray-400 py-2 px-4 rounded-md max-lg:py-1 max-lg:text-sm max-lg:px-2 text-gray-900"
                            type="password" name="password_confirmation" id="password_confirmation"
                            oninput="validateInput('password_confirmation')">
                        @error('password_confirmation')
                            <span id="password_confirmation_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col flex-1" for="phone_number">
                        <h1 class=" max-lg:text-sm">Phone number</h1>
                        <input
                            class="border border-gray-400 py-2 px-4 rounded-md max-lg:py-1 max-lg:text-sm max-lg:px-2 text-gray-900"
                            name="phone_number" type="text" autocomplete="off" id="phone_number"
                            value="{{ old('phone_number') }}" oninput="validateInput('phone_number')">
                        @error('phone_number')
                            <span id="phone_number_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class=" flex-col flex-1 hidden" for="next_visit">
                        <h1 class="">Date of next visit</h1>
                        <input class="border border-gray-400 py-2 px-4 rounded-md" name="next_visit" type="date"
                            autocomplete="off" id="next_visit" disabled>
                        @error('next_visit')
                            <span id="next_visit_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="w-full flex justify-center mt-2">
                        <button
                            class="bg-slate-200 w-1/2 text-slate-900 font-bold py-2 px-8 rounded-md hover:bg-slate-900 hover:text-slate-100 transition-all">
                            Register </button>
                    </div>

                </form>

                <div
                    class="flex flex-col justify-center text-center max-lg:text-left mt-6 max-lg:justify-start max-lg:mt-2 max-lg:text-sm max-lg:gap-3">
                    <a class="text-white hover:font-semibold transition-all" href="{{ route('login') }}">Already have
                        an account?</a>
                    <a class="text-white hover:font-semibold transition-all" href="{{ route('welcome') }}">Go back to
                        homepage</a>
                </div>
            </div>
            <div class="p-8 max-lg:px-2 max-lg:py-4 max-lg:flex-row flex-col flex items-center justify-center">
                <img class="mx-8 max-lg:mx-4 max-lg:h-16" src="{{ asset('assets/images/logo.png') }}"
                    alt="">
                <div
                    class="flex flex-col justify-center text-center max-lg:text-left mt-6 max-lg:justify-start max-lg:mt-2">
                    <h1 class="font-bold text-xl max-lg:text-sm max-lg:w-max">Tooth Impressions Dental Clinic</h1>
                    <h1 class="text-sm max-lg:text-xs">Your Smile, Our Passion: Quality Dental Care You Can Trust.</h1>
                </div>
            </div>
        </div>
    </section>

    <script>
        // JavaScript to handle input validation and showing/hiding error messages
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
</body>

</html>
