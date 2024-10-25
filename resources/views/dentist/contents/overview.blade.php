@extends('admin.dashboard')
@section('content')
    <div class="m-4">
        @include('components.search')
    </div>
    <section class="flex flex-col justify-center items-center  w-full  max-xl:w-min ">
        <section
            class="flex flex-col justify-center items-start rounded-lg bg-white shadow-lg border-y max-xl:mt-12 my-4 p-6 max-xl:p-1">
            <section
                class="flex max-xl:flex-col max-xl:items-start flex-1 flex-wrap justify-between items-center p-6 max-xl:p-2 w-full ">
                <div>
                    <h1 class="text-4xl font-bold max-xl:text-lg">Welcome {{ Auth::user()->username }}!</h1>
                    <h1 class="text-sm"> {{ Auth::user()->branch_loc }}</h1>
                    <h1 class="text-sm">Have a nice day at work!</h1>
                </div>
                <div class="flex gap-4 max-lg:my-4 justify-center items-center border p-4 rounded-md bg-slate-100">
                    <p class="font-bold text-xl max-lg:text-xs">{{ now()->format('l, F j, Y') }}</p>
                    <img class="h-6" src="{{ asset('assets/images/calendar.png') }}" alt="">
                </div>
            </section>
            <section
                class="flex  rounded-md flex-1 max-xl:flex-col flex-wrap  justify-center max-xl:justify-start gap-8 max-xl:gap-2 items-start border-y w-full px-6 max-xl:px-2 max-xl:hidden ">
                <div
                    class="flex flex-1 gap-4 p-4 border-r max-lg:border max-lg:bg-white max-lg:shadow-lg max-lg:p-3 max-lg:rounded-md my-4 max-lg:max-w-min max-xl:p-1 max-xl:my-2 justify-start items-start max-xl:items-center max-xl:justify-center min-w-max">
                    <img class="h-12 max-xl:h-6" src="{{ asset('assets/images/appointment-today.png') }}" alt="">
                    <div class="font-semibold text-xl">
                        {{ $pendingAppointmentsDashboard > 0 ? $pendingAppointmentsDashboard : '0' }}
                        <h1>Pending Appointments</h1>
                    </div>
                </div>
                <div
                    class="flex flex-1 gap-4 p-4 border-r max-lg:border max-lg:bg-white max-lg:shadow-lg max-lg:p-3 max-lg:rounded-md max-lg:max-w-min my-4 max-xl:p-1 max-xl:my-2 justify-start items-start max-xl:items-center max-xl:justify-center min-w-max">
                    <img class="h-12 max-xl:h-6" src="{{ asset('assets/images/appointment-new.png') }}" alt="">
                    <div class="font-semibold text-xl">
                        {{ $approvedAppointments > 0 ? $approvedAppointments : '0' }}
                        <h1>Approved Appointments</h1>
                    </div>
                </div>
                <div
                    class="flex flex-1 gap-4 p-4 border-r max-lg:border max-lg:bg-white max-lg:shadow-lg max-lg:p-3 max-lg:rounded-md  max-lg:max-w-min my-4 max-xl:p-1 max-xl:my-2 justify-start items-start max-xl:items-center max-xl:justify-center min-w-max">
                    <img class="h-12 max-xl:h-6" src="{{ asset('assets/images/appointment-total.png') }}" alt="">
                    <div class="font-semibold text-xl">
                        {{ $declinedAppointments > 0 ? $declinedAppointments : '0' }}

                        <h1>Declined Appointments</h1>
                    </div>
                </div>
            </section>
            <section
                class="flex flex-1 flex-wrap  gap-6 justify-center max-xl:justify-start items-start w-full my-6 px-6 max-xl:px-2 max-xl:my-3 ">

                <div
                    class="flex flex-1 flex-col justify-center items-center bg-white rounded-md shadow-lg p-6 max-xl:p-2 min-w-xl border h-full min-w-max max-xl:max-w-min ">
                    <div class="flex justify-between items-center border-b w-full gap-12 mb-4">
                        <h1 class="text-xl font-semibold text-left max-lg:py-2">Pending appointments</h1>
                        <a class="text-sm text-blue-400"
                            href="{{ route('appointments.pending', Auth::user()->dentist_id) }}">See all</a>
                    </div>
                    <div class="flex flex-col justify-start items-start gap-4 w-full">
                        @if ($pendingAppointmentsInformation->isEmpty())
                            <div class="mt-4 flex flex-1 w-full justify-between items-center gap-4 border-b py-4 ">
                                <div class="flex justify-center items-center w-full flex-col">
                                    <img class="h-20" src="{{ asset('assets/images/relax.png') }}" alt="">
                                    <h1 class="text-sm"> There are currently no pending appointments. </h1>
                                </div>
                            </div>
                        @else
                            @foreach ($pendingAppointmentsInformation as $appointment)
                                <div class="mt-4 flex flex-1 justify-between items-center gap-4 border-b py-4 w-full">
                                    <div class="flex justify-center items-center gap-4">
                                        <img class="h-7 rounded-full p-1 border border-black"
                                            src="{{ asset('assets/images/user-icon.png') }}" alt="">
                                        <div>
                                            <h1 class="font-semibold text-lg">{{ $appointment->patient->last_name }},
                                                {{ $appointment->patient->first_name }}</h1>
                                            <h1 class="text-xs">{{ $appointment->procedure->name ?? 'None' }}</h1>
                                            <h1 class="text-xs">{{ $appointment->preferred_time }}</h1>
                                        </div>
                                    </div>
                                    <h1 class="border py-2 px-4 rounded-md bg-green-100 text-xs">
                                        {{ $appointment->appointment_date }}
                                    </h1>
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
                <div
                    class="flex flex-1 flex-col justify-center items-center bg-white rounded-md shadow-lg p-6 max-xl:p-2 max-lg:m-0 min-w-max border max-xl:max-w-min ">
                    <div class="flex flex-wrap justify-between items-center border-b w-full gap-12 max-xl:gap-2 mb-4">
                        <h1 class="text-xl font-semibold text-left max-lg:py-2">Recent on going payments</h1>
                        <a class="text-sm text-blue-400"
                            href="{{ route('appointments.payment', Auth::user()->dentist_id) }}">See all</a>
                    </div>
                    @if ($recentPayments->isEmpty())
                        <div class="mt-4 flex flex-1 w-full justify-between items-center gap-4 border-b py-4 ">
                            <div class="flex justify-center items-center w-full flex-col">
                                <img class="h-20" src="{{ asset('assets/images/relax.png') }}" alt="">
                                <h1 class="text-sm">There are currently no pending appointments.</h1>
                            </div>
                        </div>
                    @else
                        @foreach ($recentPayments as $recentPayment)
                            <div class="mt-4 flex justify-between items-center gap-4 border-b py-4 w-full">
                                <div class="flex justify-center items-center gap-4">
                                    <img class="h-7 rounded-full p-1 border border-black"
                                        src="{{ asset('assets/images/user-icon.png') }}" alt="">
                                    <div>
                                        <h1 class="font-semibold text-lg">
                                            {{ $recentPayment->appointment->patient->first_name }}
                                            {{ $recentPayment->appointment->patient->last_name }}</h1>
                                        <h1 class="text-xs">{{ $recentPayment->appointment->procedure->name }}</h1>
                                        <h1 class="text-xs">{{ $recentPayment->created_at->format('d-m-Y') }}</h1>
                                    </div>
                                </div>
                                @if ($recentPayment->status === 'Paid')
                                    <h1 class="border py-2 px-4 rounded-md bg-green-100 text-xs">
                                        Paid
                                    </h1>
                                @else
                                    <h1 class="border py-2 px-4 rounded-md bg-orange-100 text-xs">
                                        On going
                                    </h1>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
        </section>




        {{-- <div
            class="flex flex-col items-center justify-center w-full rounded-xl shadow-lg  bg-white my-4 mx-auto gap-4 mt-6 max-lg:mt-14 max-w-5xl">
            <h1 class=" text-5xl max-md:text-3xl font-bold my-2 ">Good day,
                {{ Auth::user()->username }}!
            </h1>
            <h1>Let's brighten smiles and make a difference today!</h1>
        </div>
        <div class="w-full flex gap-5 justify-center max-lg:justify-center items flex-wrap ">
            <div class="w-full bg-white shadow-lg rounded-xl p-8 flex flex-col items-center justify-center max-w-md">
                <div class="mb-8 text-center">
                    <h1 class="text-3xl max-md:text-xl font-bold">Patient Statistics</h1>
                </div>
                <div class="flex gap-4 flex-col justify-center items-start">
                    <div class="flex md:flex-col gap-4">
                        <a href="{{ route('patient_list') }}"
                            class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-4 rounded-md hover:bg-gray-100 transition-all cursor-pointer">
                            <img class="h-12 max-md:h-6" src="{{ asset('assets/images/total-icon.png') }}" alt="">

                            <h1 class="text-md max-md:text-sm max-md:text-center font-semibold">Total Patients</h1>
                            <h1 class="text-4xl max-md:text-2xl font-bold">{{ $totalPatients > 0 ? $totalPatients : 0 }}
                            </h1>
                        </a>

                        <a href="{{ route('patient_list') }}"
                            class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-2 rounded-md hover:bg-gray-100 transition-all cursor-pointer">
                            <img class="h-12 max-md:h-6" src="{{ asset('assets/images/today-icon.png') }}" alt="">

                            <h1 class="text-md max-md:text-sm max-md:text-center font-semibold">Today's Patients</h1>
                            <h1 class="text-4xl max-md:text-2xl font-bold">{{ $todayPatients > 0 ? $todayPatients : 0 }}
                            </h1>

                        </a>

                        <a href="{{ route('patient_list') }}"
                            class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-2 rounded-md hover:bg-gray-100 transition-all cursor-pointer">
                            <img class="h-12 max-md:h-6" src="{{ asset('assets/images/new-icon.png') }}" alt="">

                            <h1 class="text-md max-md:text-sm max-md:text-center font-semibold ">New Patients</h1>
                            <h1 class="text-4xl max-md:text-2xl font-bold">{{ $newPatients > 0 ? $newPatients : 0 }}</h1>

                        </a>
                    </div>

                </div>
            </div>
            @if (Auth::user()->role === 'admin')
                <div class="w-full bg-white shadow-lg rounded-xl p-8 flex flex-col items-center justify-center max-w-md">
                    <div class="mb-8 text-center">
                        <h1 class="text-3xl max-md:text-xl font-bold">Appointment Summary</h1>
                    </div>
                    <div class="flex gap-4 max-md:gap-2 md:flex-col">
                        <a href="{{ route('appointments.walkIn') }}"
                            class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-2 rounded-md hover:bg-gray-100 transition-all  ">
                            <img class="h-12 max-md:h-6" src="{{ asset('assets/images/appointment-total.png') }}"
                                alt="">
                            <h1 class="text-md font-semibold max-md:text-xs max-md:text-center">Total of Appointments</h1>
                            <h1 class="text-4xl font-bold max-md:text-2xl">
                                {{ $totalAppointments > 0 ? $totalAppointments : 0 }}
                            </h1>
                        </a>
                        <a href="{{ route('appointments.walkIn') }}"
                            class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-2 rounded-md hover:bg-gray-100 transition-all  ">
                            <img class="h-12 max-md:h-6" src="{{ asset('assets/images/appointment-new.png') }}"
                                alt="">
                            <h1 class="text-md font-semibold max-md:text-xs max-md:text-center">New Appointments</h1>
                            <h1 class="text-4xl font-bold max-md:text-2xl">
                                {{ $newAppointments > 0 ? $newAppointments : 0 }}
                            </h1>
                        </a>
                        <a href="{{ route('appointments.walkIn') }}"
                            class="flex-1 flex max-md:flex-col max-md:p-2 py-4 px-8 justify-between bg-white shadow-lg items-center gap-2 rounded-md hover:bg-gray-100 transition-all  ">
                            <img class="h-12 max-md:h-6" src="{{ asset('assets/images/appointment-today.png') }}"
                                alt="">
                            <h1 class="text-md font-semibold max-md:text-xs max-md:text-center">Today's Appointments</h1>
                            <h1 class="text-4xl font-bold max-md:text-2xl">
                                {{ $todayAppointment > 0 ? $todayAppointment : 0 }}
                            </h1>
                        </a>
                    </div>
                </div>
            @endif
        </div> --}}
    </section>
@endsection
