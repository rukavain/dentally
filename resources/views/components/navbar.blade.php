<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @vite('resources/css/app.css')
</head>

<body class="">
    <div class="flex p-4 justify-between align-center shadow-lg">
        <div class="flex gap-4 text-md justify-center items-center">
            <a href="{{ route('welcome') }}">
                <img class="h-14 max-lg:h-8" src="{{ asset('assets/images/logo.png') }}" alt="">
            </a>
            <div class="flex gap-4 max-md:hidden">
                <h1 class="hover:font-bold transition-all text-md font-semibold">Tooth Impressions Dental Clinic</h1>
            </div>
        </div>
        <div class="flex gap-6 max-lg:gap-3 justify-center items-center max-w-4xl">
            @guest
                <a class="flex self-center justify-center gap-2 max-lg:text-sm" href="{{ route('login') }}">
                    <h1 class="font-bold">Login</h1>
                </a>
                {{-- <a class="flex justify-center items-center" href="{{ route('appointments.request') }}"> --}}
                <a href="{{ route('login') }}"
                    class="py-2 max-lg:text-xs px-4 rounded-md text-white font-semibold bg-green-600 hover:bg-green-700 transition-all cursor-pointer max-lg:py-1 max-lg:px-2">
                    BOOK NOW</a>
                </a>
            @endguest
            <div class="flex justify-center items-center gap-3 max-w-4xl">
                @auth
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="py-2 px-4 rounded-md text-white font-semibold bg-green-600 hover:bg-green-700 transition-all cursor-pointer max-lg:py-1 max-lg:px-2 max-lg:text-xs ">
                            DASHBOARD
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="m-auto">
                            @csrf
                            <button type="submit" class="font-bold max-lg:text-sm">
                                Log out
                            </button>
                        </form>
                    @elseif (Auth::user()->role === 'staff')
                        <a href="{{ route('staff.dashboard') }}"
                            class="py-2 px-4 rounded-md text-white font-semibold bg-green-600 hover:bg-green-700 transition-all cursor-pointer max-lg:py-1 max-lg:px-2 max-lg:text-xs ">
                            DASHBOARD
                        </a>
                    @elseif (Auth::user()->role === 'dentist')
                        <a href="{{ route('dentist.dashboard', Auth::user()->dentist_id) }}"
                            class="py-2 px-4 rounded-md text-white font-semibold bg-green-600 hover:bg-green-700 transition-all cursor-pointer max-lg:py-1 max-lg:px-2 max-lg:text-xs ">
                            DASHBOARD
                        </a>
                    @elseif (Auth::user()->role === 'client')
                        <a class="flex gap-6 justify-center items-center"
                            href="{{ route('client.overview', Auth::user()->patient_id) }}">
                            <h1
                                class="py-2 px-4 rounded-md text-white font-semibold bg-green-600 hover:bg-green-700 transition-all cursor-pointer max-lg:py-1 max-lg:px-2 max-lg:text-xs ">
                                Profile</h1>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="m-auto">
                            @csrf
                            <button type="submit" class="font-bold max-lg:text-sm">
                                Log out
                            </button>
                        </form>
                    @elseif (Auth::user()->role === 'staff')
                        <form action="{{ route('logout') }}" method="POST" class="m-auto">
                            @csrf
                            <button type="submit" class="font-bold max-lg:text-sm">
                                Log out
                            </button>
                        </form>
                        <a href="{{ route('admin.dashboard') }}"
                            class="py-2 px-4 rounded-md text-white font-semibold bg-green-600 hover:bg-green-700 transition-all cursor-pointer max-lg:py-1 max-lg:px-2 max-lg:text-xs ">
                            DASHBOARD
                        </a>
                    @elseif (Auth::user()->role === 'dentist')
                        <form action="{{ route('logout') }}" method="POST" class="m-auto">
                            @csrf
                            <button type="submit" class="font-bold max-lg:text-sm">
                                Log out
                            </button>
                        </form>
                        <a href="{{ route('admin.dashboard') }}"
                            class="py-2 px-4 rounded-md text-white font-semibold bg-green-600 hover:bg-green-700 transition-all cursor-pointer max-lg:py-1 max-lg:px-2 max-lg:text-xs ">
                            DASHBOARD
                        </a>
                    @else
                    @endif
                @endauth
            </div>
        </div>
    </div>
</body>

</html>
