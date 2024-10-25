<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} | Reset Password</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body>
    <section class="h-screen bg-slate-100 flex justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg flex">
            <div class="bg-green-600 rounded-lg text-white p-8 flex flex-col justify-between">
                <div>
                    <h1 class="font-italic text-sm text-white max-w-sm mb-6">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</h1>
                    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4 mb-4">
                        @csrf
                        <label for="email">
                            <h1 class="font-semibold">E-mail</h1>
                            <input class="w-full border text-black border-gray-400 py-2 px-4 rounded-md" type="email"
                                name="email" id="email">
                        </label>
                        <button
                            class="bg-slate-200 w-full text-slate-900 font-bold py-2 px-8 rounded-md hover:bg-slate-900 hover:text-slate-100 transition-all">Email Password Reset Link</button>
                    </form>
                </div>
                <div class="flex flex-col text-sm max-w-44">
                    <a class="hover:font-semibold transition-all" href="{{ route('welcome') }}">Go back to homepage</a>
                </div>
            </div>
        </div>
    </section>
</body>

</html>

