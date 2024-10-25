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
    /* Sidebar styling */
    #mobile-nav {
        transition: transform 0.3s ease;
        z-index: 50;
        /* Ensure it's above the overlay and dropdown */
    }

    #mobile-nav.active {
        transform: translateX(0);
    }

    /* Main overlay styling */
    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(86, 86, 86, 0.5);
        z-index: 40;
        /* Ensure it's between the sidebar and dropdown */
    }

    #overlay.active {
        display: block;
    }

    /* Prevent scrolling when overlay is active */
    .no-scroll {
        overflow: hidden;
    }

    /* Dropdown styling */
    #user-dropdown {
        position: relative;
        z-index: 30;
        /* Ensure it's below the sidebar but above the dropdown overlay */
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        z-index: 30;
        /* Ensure it's above other content but below the overlay */
    }

    .dropdown[open] .dropdown-menu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    /* Ensure dropdown is not interactive when sidebar is active */
    #mobile-nav.active~#user-dropdown .dropdown-menu {
        pointer-events: none;
        /* Prevent interaction */
    }


    /* Dropdown overlay styling */
    #dropdown-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 20;
        /* Ensure it's below the sidebar */
    }

    #dropdown-overlay.active {
        display: block;
    }
</style>

<body class="">
    <div id="overlay"></div>
    <div id="dropdown-overlay"></div>
    <nav
        class="max-w-max min-w-max self-start h-screen bg-white z-0 flex flex-col justify-between items-center py-4 px-8 max-lg:hidden">
        <div class="flex flex-col gap-4">
            <div class="flex justify-start items-center gap-2 mb-4">
                <a href="{{ route('welcome') }}">
                    <img class="h-10" src="{{ asset('assets/images/logo.png') }}" alt="">
                </a>
                <h1 class="text-sm">Tooth Impressions Dental Clinic</h1>
            </div>
            @if (Auth::user()->role === 'admin')
                <div class="flex flex-col items-start gap-2">
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('admin.dashboard') }}">
                        <img class="h-8" src="{{ asset('assets/images/dashboard-icon.png') }}" alt="">
                        <button class="hover:font-bold  transition-all">
                            Dashboard
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('dentist') }}">
                        <img class="h-8" src="{{ asset('assets/images/dentist.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Dentist
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('staff') }}">
                        <img class="h-8" src="{{ asset('assets/images/patient-list-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Staff
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('patient_list') }}">
                        <img class="h-8" src="{{ asset('assets/images/patient-list-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Patient list
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('schedule') }}">
                        <img class="h-8" src="{{ asset('assets/images/appointment-calendar.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Schedule
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointment.submission') }}">
                        <img class="h-8" src="{{ asset('assets/images/appointment.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Appointment List
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('inventory') }}">
                        <img class="h-8" src="{{ asset('assets/images/inventory.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Inventory
                        </button>
                    </a>

                </div>
            @endif

            @if (Auth::user()->role === 'staff')
                <div class="flex flex-col items-start gap-4">
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('staff.dashboard') }}">
                        <img class="h-8" src="{{ asset('assets/images/dashboard-icon.png') }}" alt="">
                        <button class="hover:font-bold  transition-all">
                            Dashboard
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('patient_list') }}">
                        <img class="h-8" src="{{ asset('assets/images/patient-list-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Patient List
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointment.submission') }}">
                        <img class="h-8" src="{{ asset('assets/images/appointment.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Appointment List
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('schedule') }}">
                        <img class="h-8" src="{{ asset('assets/images/appointment-calendar.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Schedule
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('inventory') }}">
                        <img class="h-8" src="{{ asset('assets/images/inventory.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Inventory
                        </button>
                    </a>
                </div>
            @endif
            @if (Auth::user()->role === 'dentist')
                <div class="flex flex-col items-start gap-2">
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('dentist.dashboard', Auth::user()->dentist_id) }}">
                        <img class="h-8" src="{{ asset('assets/images/dashboard-icon.png') }}" alt="">
                        <button class="hover:font-bold  transition-all">
                            Dashboard
                        </button>
                    </a>
                </div>
            @endif

        </div>
        <div class="flex self-start max-md:text-xs m-2.5">
            <div class="flex gap-2 items-center justify-center">
                <button class="btn flex justify-center items-center gap-2" onclick="my_modal_2.showModal()">
                    <button class="btn" onclick="my_modal_2 .showModal()"></button>
            </div>
            <dialog id="my_modal_2" class="modal border-2 shadow-lg border-gray-400  p-8 rounded-md max-md:text-lg">
                <div class="modal-box flex flex-col ">
                    <h3 class="text-2xl font-bold max-md:text-sm">Log out</h3>
                    <p class="py-4 max-md:text-sm">Are you sure you want to log out?</p>
                    <div class="modal-action flex gap-2 self-end">
                        <form method="dialog" class="border rounded-md w-max py-2 px-4">
                            <button class="btn max-md:text-xs">Close</button>
                        </form>
                        <form action="{{ route('logout') }}" method="POST"
                            class="border rounded-md bg-red-600 py-2 px-4 text-white  ">
                            @csrf
                            <button class="btn max-md:text-xs w-max flex gap-2">
                                Log
                                out
                            </button>
                        </form>
                    </div>
                </div>
            </dialog>

        </div>
    </nav>

    {{-- - AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA - --}}

    <div id="dropdown-parent"
        class="hidden max-lg:flex absolute top-0 left-0 w-screen justify-between items-center p-4 bg-white border-b-2 ">
        <button id="burger-icon" class="burger-icon">
            <img class="h-7 border p-1 rounded-md" src="{{ asset('assets/images/hamburger-icon.png') }}"
                alt="Menu">
        </button>
        <div class="flex gap-3 justify-center items-center max-md:hidden">
            <img class="h-8" src="{{ asset('assets/images/logo.png') }}" alt="Menu">
            <h1 class="text-xs font-semibold">Tooth Impression's Dental Clinic</h1>
        </div>

        <div id="user-container" class="flex justify-center items-center gap-2  self-center">
            <details id="user-dropdown"
                class="self-center rounded-md border border-gray-700 dropdown absolute right-0 top-0 justify-self-center">
                <summary class="flex my-2 self-center justify-center items-center gap-2 py-2 px-8 text-sm">
                    @if (Auth::user()->role === 'Admin')
                        <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                    @elseif(Auth::user()->role === 'Dentist')
                        <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                    @elseif(Auth::user()->role === 'Staff')
                        <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                    @else
                        <p class="text-xs font-semibold max-w-xs">{{ Auth::user()->username }}</p>
                    @endif
                    <img class="h-7 p-1 border border-gray-600 rounded-full "
                        src="{{ asset('assets/images/user-icon.png') }}" alt="">
                </summary>
                <ul
                    class="dropdown-menu menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow flex flex-col bg-white justify-end items-end gap-2 rounded-md">
                    <div class="flex gap-2 justify-start items-center py-4 border-b">
                        <img class="h-5 p-1 border border-gray-600 rounded-full "
                            src="{{ asset('assets/images/user-icon.png') }}" alt="">
                        @if (Auth::user()->role === 'Admin')
                            <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                        @elseif(Auth::user()->role === 'Dentist')
                            <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                        @elseif(Auth::user()->role === 'Staff')
                            <p class="text-xs font-semibold max-w-sm">{{ Auth::user()->username }}</p>
                        @else
                            <p class="text-xs font-semibold max-w-xs">{{ Auth::user()->username }}</p>
                        @endif
                    </div>
                    <li class="py-3">
                        <a class="" href=" {{ route('profile', ['user' => Auth::id()]) }} ">
                            <h1 class="max-lg:text-xs text-left">Profile</h1>
                        </a>
                    </li>
                    <hr class="bg-gray-700 w-full">
                    <li class="py-3">
                        <div class="">
                            <div class="text-left">
                                <button class="btn max-lg:text-xs text-right" onclick="my_modal_3.showModal()">Log
                                    out</button>
                            </div>
                            <dialog id="my_modal_3" class="modal p-4 rounded-md max-md:text-lg">
                                <div class="modal-box flex flex-col">
                                    <h3 class="text-lg font-bold max-md:text-sm">Log out</h3>
                                    <p class="py-4 max-md:text-sm">Are you sure you want to log out?</p>
                                    <div class="modal-action flex gap-2 self-end">
                                        <form method="dialog" class="border rounded-md  py-2 px-4">
                                            <button class="btn max-md:text-xs">Close</button>
                                        </form>
                                        <form action="{{ route('logout') }}" method="POST"
                                            class="border rounded-md bg-red-600 py-2 px-4 text-white ">
                                            @csrf
                                            <button class="btn font-semibold max-md:text-xs">
                                                Log out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        </div>
                    </li>
                </ul>
            </details>
        </div>

    </div>
    <nav id="mobile-nav"
        class="max-w-max h-svh min-w-max hidden self-start bg-white z-10 flex-col justify-between items-center py-4 px-4 transform -translate-x-full transition-transform duration-300 max-lg:absolute max-lg:top-0 max-lg:flex max-lg:border-r fixed">
        <div class="flex flex-col gap-4">
            <div class="flex justify-between items-center gap-2 mb-4">
                <a href="{{ route('welcome') }}" class="flex gap-2 justify-center items-center">
                    <img class="h-8" src="{{ asset('assets/images/logo.png') }}" alt="">
                </a>
                <button id="back-icon" class="back-icon">
                    <img class="h-5 border p-1 rounded-md" src="{{ asset('assets/images/back-icon.png') }}"
                        alt="Menu">
                </button>
            </div>
            @if (Auth::user()->role === 'admin')
                <div class="flex flex-col items-start gap-2">
                    <div class="block lg:hidden">
                        <form method="GET" class="flex justify-center items-center gap-2"
                            action="{{ route('patient_list') }} ">
                            @csrf
                            <input placeholder="Search..." autocomplete="off" name="search" type="search"
                                class="py-1 px-2 text-xs border-gray-400 rounded-md ">
                            <button type="submit"
                                class="border py-1 px-2 rounded-md bg-white hover:bg-gray-800 hover:text-white transition-all">
                                <img class="h-4" src="{{ asset('assets/images/search-icon.png') }}"
                                    alt="">
                            </button>
                        </form>
                    </div>
                    <div class="hidden lg:block">
                        <div class="justify-between items-center hidden max-lg:flex">
                            @include('components.search')
                        </div>
                    </div>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('admin.dashboard') }}">
                        <img class="h-5" src="{{ asset('assets/images/dashboard-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Dashboard
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('dentist') }}">
                        <img class="h-5" src="{{ asset('assets/images/dentist.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Dentist
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('patient_list') }}">
                        <img class="h-5" src="{{ asset('assets/images/patient-list-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Patient list
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('schedule') }}">
                        <img class="h-5" src="{{ asset('assets/images/appointment-calendar.png') }}"
                            alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Schedule
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointment.submission') }}">
                        <img class="h-5" src="{{ asset('assets/images/appointment.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Appointment List
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('inventory') }}">
                        <img class="h-5" src="{{ asset('assets/images/inventory.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Inventory
                        </button>
                    </a>

                </div>
            @endif
            @if (Auth::user()->role === 'staff')
                <div class="flex flex-col items-start gap-4">
                    <a class="flex justify-start items-center gap-2  w-full p-2 rounded-md  hover:bg-gray-300 transition-all border"
                        href="{{ route('staff.dashboard') }}">
                        <img class="h-8" src="{{ asset('assets/images/dashboard-icon.png') }}" alt="">
                        <button class="hover:font-bold  transition-all">
                            Dashboard
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('patient_list') }}">
                        <img class="h-8" src="{{ asset('assets/images/patient-list-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Patient List
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointment.submission') }}">
                        <img class="h-5" src="{{ asset('assets/images/appointment.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Appointment List
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('schedule') }}">
                        <img class="h-5" src="{{ asset('assets/images/appointment-calendar.png') }}"
                            alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Schedule
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('inventory') }}">
                        <img class="h-8" src="{{ asset('assets/images/inventory.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Inventory
                        </button>
                    </a>
                </div>
            @endif
        </div>
        <div id="overlay"></div>
        <div class="flex self-start max-md:text-xs m-2.5">
            <div class="flex gap-2 items-center justify-center">
                <img class="max-md:h-4 h-4" src="{{ asset('assets/images/logout.png') }}" alt="">
                <button class="btn" onclick="my_modal_1.showModal()">Log out</button>
            </div>
            <dialog id="my_modal_1" class="modal p-4 rounded-md max-md:text-lg">
                <div class="modal-box flex flex-col">
                    <h3 class="text-lg font-bold max-md:text-sm">Log out</h3>
                    <p class="py-4 max-md:text-sm">Are you sure you want to log out?</p>
                    <div class="modal-action flex gap-2 self-end">
                        <form method="dialog" class="border rounded-md  py-2 px-4">
                            <button class="btn max-md:text-xs">Close</button>
                        </form>
                        <form action="{{ route('logout') }}" method="POST"
                            class="border rounded-md bg-red-600 py-2 px-4 text-white ">
                            @csrf
                            <button class="btn font-semibold max-md:text-xs">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </dialog>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('overlay');
            const dropdown = document.getElementById('user-dropdown');
            const mobileNav = document.getElementById('mobile-nav');
            const burgerIcon = document.getElementById('burger-icon');
            const backIcon = document.getElementById('back-icon');
            const dropdownParent = document.getElementById('dropdown-parent');
            const body = document.body;

            // Show mobile navigation
            burgerIcon.addEventListener('click', function() {
                mobileNav.classList.add('active');
                dropdownParent.classList.add('border-none');
                overlay.classList.add('active'); // Show the overlay
                body.classList.add('no-scroll'); // Disable scrolling
                dropdown.removeAttribute('open'); // Ensure dropdown is closed
                dropdown.style.pointerEvents = 'none'; // Disable dropdown interaction
            });

            // Hide mobile navigation
            backIcon.addEventListener('click', function() {
                mobileNav.classList.remove('active');
                overlay.classList.remove('active'); // Hide the overlay
                body.classList.remove('no-scroll'); // Enable scrolling
                dropdown.style.pointerEvents = ''; // Enable dropdown interaction
            });

            // Close mobile navigation when clicking outside
            overlay.addEventListener('click', function() {
                mobileNav.classList.remove('active');
                overlay.classList.remove('active'); // Hide the overlay
                body.classList.remove('no-scroll'); // Enable scrolling
                dropdown.style.pointerEvents = ''; // Enable dropdown interaction
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInsideDropdown = dropdown.contains(event.target);
                const isClickInsideMobileNav = mobileNav.contains(event.target);

                // Only close the dropdown if not clicking inside it and not inside mobile nav
                if (!isClickInsideDropdown && !isClickInsideMobileNav && dropdown.hasAttribute('open')) {
                    dropdown.removeAttribute('open');
                    overlay.classList.remove('active'); // Hide the overlay
                    body.classList.remove('no-scroll'); // Enable scrolling
                    dropdown.style.pointerEvents = ''; // Enable dropdown interaction
                }
            });
        });
    </script>
</body>

</html>
