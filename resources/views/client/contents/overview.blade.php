@extends('client.profile')
@section('content')
    <section class="flex max-lg:p-3  max-lg:gap-3 gap-5  p-6 max-2xl:flex-wrap max-xl:mt-20">
        <!-- Sidebar -->
        <div class="flex-1 max-w-[35%] bg-white p-4 rounded-lg shadow-md max-xl:max-w-full">
            <div class="flex flex-col items-center text-center">
                <div
                    class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-4xl text-gray-700 font-bold">
                    {{ $patient->first_initial }}
                </div>
                <h2 class="mt-4 text-xl font-bold">{{ $patient->first_name }} {{ $patient->last_name }}</h2>
                <p class="text-gray-500">{{ $patient->email }}</p>
            </div>
            <div class="mt-6 max-lg:text-sm">
                <hr class="w-full bg-gray">
                <div class="flex flex-col justify-center items-between">
                    <div class="flex justify-between min-w-max my-2 py-2 px-4 gap-4">
                        <h3 class="font-bold text-gray-600">Gender</h3>
                        <p>{{ $patient->gender }}</p>
                    </div>
                    <hr class="w-full bg-gray">
                    <div class="flex justify-between min-w-max my-2 py-2 px-4 gap-4">
                        <h3 class="font-bold text-gray-600">Birthdate</h3>
                        <p>{{ $patient->date_of_birth }}</p>
                    </div>
                    <hr class="w-full bg-gray">
                    <div class="flex justify-between min-w-max my-2 py-2 px-4 gap-4">
                        <h3 class="font-bold text-gray-600">Phone</h3>
                        <p>{{ $patient->phone_number }}</p>
                    </div>
                    <hr class="w-full bg-gray">
                    <div class="flex justify-between min-w-max my-2 py-2 px-4 gap-4">
                        <h3 class="font-bold text-gray-600">Next visit</h3>
                        <p> {{ $patient->next_visit }}</p>
                    </div>
                    <hr class="w-full bg-gray">
                    <div class="flex justify-between min-w-max my-2 py-2 px-4 gap-4 w-full mt-6">
                        <form class="w-full" method="GET" action="{{ route('add.online', $patient->id) }}">
                            @csrf
                            <button onclick="openModal()"
                                class="flex w-full justify-center items-center gap-2 rounded-md py-2 px-4  min-w-max border-2 border-gray-600 hover:shadow-md hover:border-green-700 font-semibold text-gray-800 transition-all max-md:px-2">
                                <span class="max-md:text-xs"> Request an Appointment</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-2 w-[65%] bg-white max-xl:w-full max-xl:mx-1 p-4 max-lg:p-2 max-xl:text-xs rounded-lg shadow-md">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-4">
                <nav class="flex items-center gap-5 max-lg:gap-2 max-xl:flex-wrap">

                    <div class="w-full flex gap-4 max-xl:justify-between max-xl:gap-2 max-xl:px-10">
                        <button
                            class="text-gray-500 pb-2 border-b-2 border-transparent focus:outline-none hover:border-b-green-300 transition-all"
                            data-tab-target="#tab1">Appointments</button>

                        <button
                            class="text-gray-500 pb-2 border-b-2 border-transparent focus:outline-none hover:border-b-green-300 transition-all"
                            data-tab-target="#tab3">Payment</button>
                    </div>

                </nav>
            </div>

            <!-- Table -->
            <div class="max-w-full">
                <div id="tab1" class="tab-content text-gray-700  hidden max-w-full">
                    <!-- component -->
                    @include('client.contents.partial.overview-appointments')
                    <div class="w-full mt-4">
                        {{ $appointments->links() }}
                    </div>
                </div>

                <div id="tab3" class="tab-content text-gray-700 hidden">
                    @include('client.contents.partial.appointment-payment')
                    <div class="w-full mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
