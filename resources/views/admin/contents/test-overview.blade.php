@extends('admin.dashboard')
@section('content')
    <div class="m-4">
        @include('components.search')
    </div>
    <section class="py-2 px-6 border">
        <div class="rounded-xl shadow-lg p-7 bg-white my-4 gap-4 mt-6 max-lg:mt-14">
            <h1 class=" text-5xl max-md:text-3xl font-bold my-2 ">Test Good day,
                {{ Auth::user()->username }}!
            </h1>
            <h1>Let's brighten smiles and make a difference today!</h1>
        </div>
        <div class="flex gap-5 justify-start max-lg:justify-center items flex-wrap">
            <div class="w-full flex flex-1 gap-2 items-center justify-start p-4 shadow-lg rounded-xl bg-white">
                <div class="w-[50%] flex md:flex-col gap-4 p-2">
                    <div class="w-[]">
                        <div class="p-4 border rounded-t-lg">
                            <h1 class="text-md max-md:text-sm max-md:text-center font-semibold">Total Patients</h1>
                            <h1 class="text-4xl max-md:text-2xl font-bold">{{ $totalPatients > 0 ? $totalPatients : 0 }}
                            </h1>
                        </div>
                        <div class="">
                            <a href="{{ route('patient.active') }}"
                                class="flex justify-between items-center px-3  shadow-lg  gap-2 rounded-b-lg bg-gray-200 hover:bg-gray-500 hover:text-white transition-all max-md:flex-col max-md:p-2">
                                <span>View more</span> <span class="text-2xl">&rightarrow;</span>
                            </a>
                        </div>
                    </div>

                    <div class="w-[]">
                        <div class="p-4 border rounded-t-lg">
                            <h1 class="text-md max-md:text-sm max-md:text-center font-semibold">Total Appointments</h1>
                            <h1 class="text-4xl max-md:text-2xl font-bold">
                                {{ $totalAppointments > 0 ? $totalAppointments : 0 }}
                            </h1>
                        </div>
                        <div class="">
                            <a href="{{ route('patient.active') }}"
                                class="flex justify-between items-center px-3  shadow-lg  gap-2 rounded-b-lg bg-gray-200 hover:bg-gray-500 hover:text-white transition-all max-md:flex-col max-md:p-2">
                                <span>View more</span> <span class="text-2xl">&rightarrow;</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="w-[50%] flex md:flex-col gap-4 p-2">
                    <div class="w-[]">
                        <div class="p-4 border rounded-t-lg">
                            <h1 class="text-md max-md:text-sm max-md:text-center font-semibold">Total Patients</h1>
                            <h1 class="text-4xl max-md:text-2xl font-bold">{{ $totalPatients > 0 ? $totalPatients : 0 }}
                            </h1>
                        </div>
                        <div class="">
                            <a href="{{ route('patient.active') }}"
                                class="flex justify-between items-center px-3  shadow-lg  gap-2 rounded-b-lg bg-gray-200 hover:bg-gray-500 hover:text-white transition-all max-md:flex-col max-md:p-2">
                                <span>View more</span> <span class="text-2xl">&rightarrow;</span>
                            </a>
                        </div>
                    </div>

                    <div class="w-[]">
                        <div class="p-4 border rounded-t-lg">
                            <h1 class="text-md max-md:text-sm max-md:text-center font-semibold">Total Revenue</h1>
                            <h1 class="text-4xl max-md:text-2xl font-bold">&#8369;
                                {{ $totalRevenue > 0 ? number_format($totalRevenue, 2) : 0 }}
                            </h1>
                        </div>
                        <div class="">
                            <a href="{{ route('patient.active') }}"
                                class="flex justify-between items-center px-3  shadow-lg  gap-2 rounded-b-lg bg-gray-200 hover:bg-gray-500 hover:text-white transition-all max-md:flex-col max-md:p-2">
                                <span>View more</span> <span class="text-2xl">&rightarrow;</span>
                            </a>
                        </div>
                    </div>
                    {{-- <a href="{{ route('appointments.walkIn') }}"
                        class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-2 rounded-md hover:bg-gray-100 transition-all  ">

                        <h1 class="text-md font-semibold max-md:text-xs max-md:text-center">Total of Appointments</h1>
                        <h1 class="text-4xl font-bold max-md:text-2xl">
                            {{ $totalAppointments > 0 ? $totalAppointments : 0 }}
                        </h1>
                    </a>
                    <a href="{{ route('appointments.walkIn') }}"
                        class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-2 rounded-md hover:bg-gray-100 transition-all  ">

                        <h1 class="text-md font-semibold max-md:text-xs max-md:text-center">New Appointments</h1>
                        <h1 class="text-4xl font-bold max-md:text-2xl">
                            {{ $newAppointments > 0 ? $newAppointments : 0 }}
                        </h1>
                    </a> --}}
                    {{-- <a href="{{ route('appointments.walkIn') }}"
                            class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-2 rounded-md hover:bg-gray-100 transition-all  ">
                            <img class="h-12 max-md:h-6" src="{{ asset('assets/images/appointment-today.png') }}"
                                alt="">
                            <h1 class="text-md font-semibold max-md:text-xs max-md:text-center">Today's Appointments</h1>
                            <h1 class="text-4xl font-bold max-md:text-2xl">
                                {{ $todayAppointment > 0 ? $todayAppointment : 0 }}
                            </h1>
                        </a> --}}
                </div>
            </div>
            @if (Auth::user()->role === 'admin')
                <div class="w-[50%] bg-white shadow-lg rounded-xl p-8 flex flex-1 flex-col items-center justify-center">

                </div>
            @endif
        </div>
    </section>
@endsection
