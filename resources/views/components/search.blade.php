<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} @yield('title') </title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @vite('resources/css/app.css')
</head>
<style>
    /* Custom CSS */
    #mobile-nav {
        transition: transform 0.3s ease;
        z-index: 30;
    }

    #mobile-nav.active {
        transform: translateX(0);
        z-index: 30;
        /* Higher than the overlay */
    }

    /* Add this to your CSS file */
    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(86, 86, 86, 0.5);
        z-index: 20;
        /* Ensure it's above other content but below mobile-nav */
    }

    #overlay.active {
        display: block;
    }

    .no-scroll {
        overflow: hidden;
    }

    /* Ensure the container including the name and dropdown is above the overlay */
    #user-container {
        position: relative;
        z-index: 20;
    }

    /* Ensure the dropdown is above the overlay */
    #user-dropdown {
        position: relative;
        z-index: 25;
    }

    .no-scroll {
        overflow: hidden;
        height: 100vh;
    }

    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        z-index: 25;
        /* Ensure it's above other content but below the overlay */
    }

    .dropdown[open] .dropdown-menu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    #dropdown-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 0%;
        margin: 0%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent black */
        z-index: 24;
        /* Below the dropdown but above other content */
    }

    #dropdown-overlay.active {
        display: block;
    }
</style>

<body>
    <div id="overlay"></div>
    <div id="dropdown-overlay"></div>
    <div id="mobile-nav" class="flex justify-end items-center max-lg:hidden">
        @if (Auth::user()->role === 'Admin')
            <div class="flex gap-4 items-center justify-center">
                <form method="GET" class="flex justify-center items-center gap-2"
                    action="{{ route('patient.active') }} ">
                    @csrf
                    <img class="h-12" src="{{ asset('assets/images/search-icon.png') }}" alt="">
                    <input placeholder="Search..." autocomplete="off" name="search" type="search"
                        class="py-2 px-4 border-gray-400 rounded-md">
                    <button type="submit"
                        class="shadow-md py-2 px-6 rounded-md bg-white hover:bg-gray-800 hover:text-white transition-all">
                        Search
                    </button>
                </form>
            </div>
        @else
        @endif
        <div id="user-container" class="flex justify-center items-center gap-2 cursor-pointer self-center">
            <details id="user-dropdown"
                class="self-center bg-white rounded-md border dropdown absolute right-0 top-0 justify-self-center">
                <summary class="flex my-2 self-center justify-center items-center gap-2 py-2 px-8 text-sm">
                    @if (Auth::user()->role === 'Admin')
                        <p class="text-md font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                    @elseif(Auth::user()->role === 'Dentist')
                        <p class="text-md font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                    @elseif(Auth::user()->role === 'Staff')
                        <p class="text-md font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                    @else
                        <p class="text-md font-semibold max-w-xs">{{ Auth::user()->username }}</p>
                    @endif
                    <img class="h-7 p-1 border border-gray-600 rounded-full bg-white"
                        src="{{ asset('assets/images/user-icon.png') }}" alt="">
                </summary>
                <ul
                    class="dropdown-menu menu dropdown-content bg-base-100 mt-1 rounded-box z-[1] w-52 p-2 shadow flex flex-col justify-end items-end gap-2 bg-white rounded-md">
                    <div class="flex gap-2 justify-start items-center py-4 border-b">
                        <img class="h-5 p-1 border border-gray-600 rounded-full bg-white"
                            src="{{ asset('assets/images/user-icon.png') }}" alt="">
                        @if (Auth::user()->role === 'Admin')
                            <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}
                                {{ ['user' => Auth::id()] }}
                            </p>
                        @elseif(Auth::user()->role === 'Dentist')
                            <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                        @elseif(Auth::user()->role === 'Staff')
                            <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                        @else
                            <p class="text-xs font-semibold max-w-xs">{{ Auth::user()->username }}
                            </p>
                        @endif

                    </div>

                    @if (Auth::user()->role === 'admin')
                        <li class="py-3 my-2 px-2 hover:bg-gray-200 transition-all rounded-sm">
                            <a class="" href="{{ route('profile.edit', ['user' => Auth::id()]) }}">
                                <h1 class="max-lg:text-xs text-sm text-left ">Profile</h1>
                            </a>
                        </li>
                    @elseif(Auth::user()->role === 'client')
                        <li class="py-3 my-2 px-2 hover:bg-gray-200 transition-all rounded-sm">
                            <a class="" href="{{ route('profile.edit', ['user' => Auth::id()]) }}">
                                <h1 class="max-lg:text-xs text-sm text-left ">Profile</h1>
                            </a>
                        </li>
                    @elseif(Auth::user()->role === 'dentist')
                        <li class="py-3 my-2 px-2 hover:bg-gray-200 transition-all rounded-sm">
                            <a class="" href="{{ route('profile.edit', ['user' => Auth::id()]) }}">

                                <h1 class="max-lg:text-xs text-sm text-left ">Profile</h1>
                            </a>
                        </li>
                    @elseif(Auth::user()->role === 'staff')
                        <li class="py-3 my-2 px-2 hover:bg-gray-200 transition-all rounded-sm">
                            <a class="" href="{{ route('profile.edit', ['user' => Auth::id()]) }}">

                                <h1 class="max-lg:text-xs text-sm text-left ">Profile</h1>
                            </a>
                        </li>
                    @else
                        <li class="py-3 my-2 px-2 hover:bg-gray-200 transition-all rounded-sm">
                            <a class="" href="">
                                <h1 class="max-lg:text-xs text-sm text-left ">Default</h1>
                            </a>
                        </li>
                    @endif
                    <hr class="bg-gray-700 w-full">
                    <a onclick="document.getElementById('my_modal_2').showModal()"
                        class="py-3  flex my-2 px-2 hover:bg-gray-200 transition-all rounded-sm">
                        <div class="flex self-start text-sm max-md:text-xs">
                            <div class="flex  gap-2 items-center justify-center">

                                Log out

                            </div>
                            {{-- Client Logout --}}
                            <dialog id="my_modal_2"
                                class="modal border-2 shadow-lg border-gray-400 p-8 rounded-md max-md:text-lg">
                                <div class="modal-box flex flex-col">
                                    <h3 class="text-2xl font-bold max-md:text-sm">Log out search</h3>
                                    <p class="py-4 max-md:text-sm">Are you sure you want to log out?</p>
                                    <div class="modal-action flex gap-2 self-end">
                                        <form method="dialog" class="border rounded-md w-max py-2 px-4">
                                            <button class="btn max-md:text-xs">Close</button>
                                        </form>
                                        <form action="{{ route('logout') }}" method="POST"
                                            class="border  bg-red-600 text-white rounded-md py-2 px-4">
                                            @csrf
                                            <button class="btn  bg-red-600 text-white max-md:text-xs w-max flex gap-2">
                                                Log
                                                out</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        </div>
                    </a>
            </details>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('my_modal_2');
            const logoutButton = document.getElementById('logout-button');
            const closeButton = modal.querySelector('button[type="button"]');

            const overlay = document.getElementById('overlay');
            const dropdown = document.getElementById('user-dropdown');
            const mobileNav = document.getElementById('mobile-nav');
            const burgerIcon = document.getElementById('burger-icon');
            const backIcon = document.getElementById('back-icon');
            const body = document.body;

            dropdown.addEventListener('toggle', function(event) {
                if (mobileNav.classList.contains('active')) {
                    event.preventDefault();
                } else {
                    if (event.target.open) {
                        overlay.classList.add('active');
                        body.classList.add('no-scroll');
                    } else {
                        overlay.classList.remove('active');
                        body.classList.remove('no-scroll');
                    }
                }
            });

            burgerIcon.addEventListener('click', function() {
                mobileNav.classList.add('active');
                overlay.classList.add('active');
                body.classList.add('no-scroll');
            });

            backIcon.addEventListener('click', function() {
                mobileNav.classList.remove('active');
                overlay.classList.remove('active');
                body.classList.remove('no-scroll');
            });

            overlay.addEventListener('click', function() {
                mobileNav.classList.remove('active');
                overlay.classList.remove('active');
                body.classList.remove('no-scroll');
            });

            document.addEventListener('click', function(event) {
                const isClickInsideDropdown = dropdown.contains(event.target);
                const isClickInsideMobileNav = mobileNav.contains(event.target);

                if (!isClickInsideDropdown && !isClickInsideMobileNav && dropdown.hasAttribute('open')) {
                    dropdown.removeAttribute('open');
                    overlay.classList.remove('active');
                    body.classList.remove('no-scroll');
                }
            });
        });
    </script>
</body>

</html>
