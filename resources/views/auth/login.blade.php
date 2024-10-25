<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} | Login</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        /* Error message styling */
        .error-message {
            color: #fff;
            background-color: #f44336;
            /* Red */
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>

<body>
    <section class="h-screen bg-slate-100 flex justify-center items-center p-2 rounded-lg">
        <div class="bg-white rounded-lg shadow-lg flex max-lg:flex-col-reverse border border-gray-300">
            <div class="bg-green-600 max-lg:rounded-sm rounded-lg text-white p-8 flex flex-col justify-between">
                <div class="">
                    <h1 class="font-bold text-4xl text-white max-w-sm mb-6 max-lg:text-3xl">Login to your account</h1>
                    @if ($errors->any())
                        <div class=" show text-red-600 text-xs">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4 mb-8">
                        @csrf
                        <label for="email">
                            <h1 class="font-semibold max-lg:text-sm">E-mail</h1>
                            <input
                                class="w-full border text-black border-gray-400 py-2 px-4 max-lg:py-1 max-lg:text-sm max-lg:px-2 rounded-md"
                                type="email" name="email" id="email" value="{{ old('email') }}">
                        </label>
                        <label for="password">
                            <h1 class="font-semibold max-lg:text-sm">Password</h1>
                            <input
                                class="w-full border text-black border-gray-400 py-2 px-4 max-lg:py-1 max-lg:text-sm max-lg:px-2 rounded-md"
                                type="password" name="password" id="password">
                        </label>
                        <a class="text-sm max-lg:text-xs hover:font-semibold transition-all"
                            href="{{ route('password.request') }}">Forgot your password?</a>
                        <button
                            class="bg-slate-200 max-w-min text-slate-900 font-bold py-2 px-8 rounded-md hover:bg-slate-900 hover:text-slate-100 transition-all">Login</button>
                    </form>
                </div>
                <div class="flex gap-2 flex-col text-sm max-w-44">
                    <a class="hover:font-semibold transition-all max-lg:text-xs" href="{{ route('register') }}">Don't
                        have an
                        account?</a>
                    <a class="hover:font-semibold transition-all max-lg:text-xs" href="{{ route('welcome') }}">Go back
                        to homepage</a>
                </div>
            </div>
            <div class="p-8 max-lg:px-2 max-lg:py-4 max-lg:flex max-lg:items-center max-lg:justify-center">
                <img class="mx-8 max-lg:mx-4 max-lg:h-16" src="{{ asset('assets/images/logo.png') }}" alt="">
                <div
                    class="flex flex-col justify-center text-center max-lg:text-left mt-6 max-lg:justify-start max-lg:mt-2 ">
                    <h1 class="font-bold text-xl max-lg:text-sm max-lg:w-max">Tooth Impressions Dental Clinic
                    </h1>
                    <h1 class="text-sm max-lg:text-xs">Your Smile, Our Passion: Quality Dental Care You Can Trust.</h1>
                </div>
            </div>
        </div>
    </section>

    <script>
        // JavaScript to show the error message if there are errors
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = document.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>

</html>
