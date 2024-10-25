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
                <div class="flex flex-col items-start justify-between gap-2">
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
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open"
                            class="flex justify-start items-center gap-2 hover:bg-gray-300 transition-all w-full p-2 rounded-md">
                            <img class="h-8" src="{{ asset('assets/images/patient-list-icon.png') }}"
                                alt="">
                            <span class="hover:font-bold transition-all">Patients</span>
                            <svg class="w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('patient.active') }}"
                                class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-200 rounded-md">Active
                                Patient</a>
                            <a href="{{ route('patient.archived') }}"
                                class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-200 rounded-md">Archived
                                Patient</a>
                        </div>
                    </div>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('schedule') }}">
                        <img class="h-8" src="{{ asset('assets/images/appointment-calendar.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Schedule
                        </button>
                    </a>
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open"
                            class="flex justify-start items-center gap-2 hover:bg-gray-300 transition-all w-full p-2 rounded-md">
                            <img class="h-8" src="{{ asset('assets/images/appointment.png') }}" alt="">
                            <span class="hover:font-bold transition-all">Appointment</span>
                            <svg class="w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('appointments.walkIn') }}"
                                class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-200 rounded-md">Walk-in
                                Request</a>
                            <a href="{{ route('appointments.online') }}"
                                class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-200 rounded-md">Online
                                Request</a>
                        </div>
                    </div>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('procedure') }}">
                        <img class="h-8" src="{{ asset('assets/images/procedure-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Procedure
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('branch') }}">
                        <img class="h-8" src="{{ asset('assets/images/branches-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Branches
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('inventory') }}">
                        <img class="h-8" src="{{ asset('assets/images/inventory-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Inventory
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('sales') }}">
                        <img class="h-8" src="{{ asset('assets/images/sales-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Sales Report
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('audit.logs') }}">
                        <img class="h-8" src="{{ asset('assets/images/audit-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all">
                            Audit Logs
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
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open"
                            class="flex justify-start items-center gap-2 hover:bg-gray-300 transition-all w-full p-2 rounded-md">
                            <img class="h-8" src="{{ asset('assets/images/patient-list-icon.png') }}"
                                alt="">
                            <span class="hover:font-bold transition-all">Patients</span>
                            <svg class="w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('patient.active') }}"
                                class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-200 rounded-md">Active
                                Patient</a>
                            <a href="{{ route('patient.archived') }}"
                                class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-200 rounded-md">Archived
                                Patient</a>
                        </div>
                    </div>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('schedule') }}">
                        <img class="h-8" src="{{ asset('assets/images/appointment-calendar.png') }}"
                            alt="">
                        <button class="hover:font-bold transition-all">
                            Schedule
                        </button>
                    </a>
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open"
                            class="flex justify-start items-center gap-2 hover:bg-gray-300 transition-all w-full p-2 rounded-md">
                            <img class="h-8" src="{{ asset('assets/images/appointment.png') }}" alt="">
                            <span class="hover:font-bold transition-all">Appointment</span>
                            <svg class="w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('appointments.walkIn') }}"
                                class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-200 rounded-md">Walk-in
                                Request</a>
                            <a href="{{ route('appointments.online') }}"
                                class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-200 rounded-md">Online
                                Request</a>
                        </div>
                    </div>
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

                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointments.pending', Auth::user()->dentist_id) }}">
                        <img class="h-8" src="{{ asset('assets/images/appointment.png') }}" alt="">
                        <button class="hover:font-bold  transition-all">
                            Pending Appointments
                        </button>
                    </a>

                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointments.approved', Auth::user()->dentist_id) }}">
                        <img class="h-8" src="{{ asset('assets/images/quality.png') }}" alt="">
                        <button class="hover:font-bold  transition-all">
                            Approved Appointments
                        </button>
                    </a>

                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointments.declined', Auth::user()->dentist_id) }}">
                        <img class="h-8" src="{{ asset('assets/images/declined.png') }}" alt="">
                        <button class="hover:font-bold  transition-all">
                            Declined Appointments
                        </button>
                    </a>

                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointments.payment', Auth::user()->dentist_id) }}">
                        <img class="h-8" src="{{ asset('assets/images/payment.png') }}" alt="">
                        <button class="hover:font-bold  transition-all">
                            Payments
                        </button>
                    </a>

                </div>
            @endif

        </div>
        <div class="flex self-start max-md:text-xs m-2.5">
            <div class="flex gap-2 items-center justify-center">
                <button class="btn flex justify-center items-center gap-2" onclick="my_modal_2.showModal()">
                    <button class="btn" onclick="my_modal_2.showModal()"></button>
            </div>
            {{-- Dentist Logout --}}
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
                                Log out
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
                            <button onclick="my_modal_3.showModal()" class="text-left">
                                <h1 class="btn max-lg:text-xs text-right">Log
                                    out
                            </button>
                            </button>
                            {{-- mobile Admin/Staff Logout --}}
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
                        href="{{ route('staff') }}">
                        <img class="h-5" src="{{ asset('assets/images/patient-list-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Staff
                        </button>
                    </a>
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open"
                            class="flex justify-start items-center gap-2 hover:bg-gray-300 transition-all w-full p-2 rounded-md">
                            <img class="h-5" src="{{ asset('assets/images/patient-list-icon.png') }}"
                                alt="">
                            <span class="hover:font-bold transition-all text-xs">Patients</span>
                            <svg class="w-3 h-3 ml-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('patient.active') }}"
                                class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-200 rounded-md">Active
                                Patient</a>
                            <a href="{{ route('patient.archived') }}"
                                class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-200 rounded-md">Archived
                                Patient</a>
                        </div>
                    </div>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('schedule') }}">
                        <img class="h-5" src="{{ asset('assets/images/appointment-calendar.png') }}"
                            alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Schedule
                        </button>
                    </a>
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open"
                            class="flex justify-start items-center gap-2 hover:bg-gray-300 transition-all w-full p-2 rounded-md">
                            <img class="h-5" src="{{ asset('assets/images/appointment.png') }}" alt="">
                            <span class="hover:font-bold transition-all text-xs">Appointment</span>
                            <svg class="w-3 h-3 ml-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('appointments.walkIn') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded-md max-md:text-xs">Walk-in
                                Request</a>
                            <a href="{{ route('appointments.online') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded-md max-md:text-xs">Online
                                Request</a>
                        </div>
                    </div>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('procedure') }}">
                        <img class="h-5" src="{{ asset('assets/images/procedure-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Procedures
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('branch') }}">
                        <img class="h-5" src="{{ asset('assets/images/branches-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Branches
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('inventory') }}">
                        <img class="h-5" src="{{ asset('assets/images/inventory-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Inventory
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('sales') }}">
                        <img class="h-5" src="{{ asset('assets/images/sales-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Sales Report
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('audit.logs') }}">
                        <img class="h-5" src="{{ asset('assets/images/audit-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Audit Logs
                        </button>
                    </a>
                </div>
            @endif
            @if (Auth::user()->role === 'staff')
                <div class="flex flex-col items-start gap-2">

                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('staff.dashboard') }}">

                        <img class="h-5" src="{{ asset('assets/images/dashboard-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Dashboard
                        </button>
                    </a>
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open"
                            class="flex justify-start items-center gap-2 hover:bg-gray-300 transition-all w-full p-2 rounded-md">
                            <img class="h-5" src="{{ asset('assets/images/appointment.png') }}" alt="">
                            <span class="hover:font-bold transition-all text-xs">Appointment</span>
                            <svg class="w-3 h-3 ml-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('appointments.walkIn') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded-md max-md:text-xs">Walk-in
                                Request</a>
                            <a href="{{ route('appointments.online') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded-md max-md:text-xs">Online
                                Request</a>
                        </div>
                    </div>
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
                        <img class="h-5" src="{{ asset('assets/images/inventory.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Inventory
                        </button>
                    </a>
                </div>
            @endif
            @if (Auth::user()->role === 'dentist')
                <div class="flex flex-col items-start gap-2">

                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('dentist.dashboard', Auth::user()->dentist_id) }}">

                        <img class="h-5" src="{{ asset('assets/images/dashboard-icon.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Dashboard
                        </button>
                    </a>

                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointments.pending', Auth::user()->dentist_id) }}">

                        <img class="h-5" src="{{ asset('assets/images/appointment.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Pending appointments
                        </button>
                    </a>

                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointments.approved', Auth::user()->dentist_id) }}">

                        <img class="h-5" src="{{ asset('assets/images/quality.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Approved appointments
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointments.declined', Auth::user()->dentist_id) }}">

                        <img class="h-5" src="{{ asset('assets/images/declined.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Declined appointments
                        </button>
                    </a>
                    <a class="flex justify-start items-center gap-2  hover:bg-gray-300 transition-all w-full p-2 rounded-md"
                        href="{{ route('appointments.payment', Auth::user()->dentist_id) }}">

                        <img class="h-4" src="{{ asset('assets/images/payment.png') }}" alt="">
                        <button class="hover:font-bold transition-all text-xs">
                            Payments
                        </button>
                    </a>
                </div>
            @endif
        </div>
        <div id="overlay"></div>
        <div class="flex self-start max-md:text-xs m-2.5">
            {{-- <h1><Strong>Role: </Strong>{{ Auth::user()->role }}</h1> --}}
            <div class="flex gap-2 items-center justify-center">
                <img class="max-md:h-4 h-4" src="{{ asset('assets/images/logout.png') }}" alt="">
                <button class="btn" onclick="my_modal_1.showModal()">Log out</button>
            </div>
            {{-- sidebar logout --}}
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
