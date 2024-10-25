@extends('dentist.dashboard')
@section('content')
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <section class="bg-white shadow-lg rounded-md p-6 my-4 mx-2  max-lg:mt-14">
        <h1 class="font-bold text-3xl p-4">Approved Appointments</h1>

        @if ($approvedAppointments->isEmpty())
            <div class="flex gap-4 flex-col justify-center items-center py-12 ">
                <img class="h-56" src="{{ asset('assets/images/relax.png') }}" alt="">
                <div class="flex flex-col justify-center items-center gap-2  ">
                    <h1 class="font-bold text-3xl text-center">No approved appointments right now.</h1>
                    <h1 class="text-sm text-center"> All caught up! There are no approved appointments at the moment.</h1>
                </div>
            </div>
        @else
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="w-full bg-gray-100">
                        <th class="py-2 px-4 border-b text-left text-gray-600 max-lg:text-xs">Appointment Date</th>
                        <th class="py-2 px-4 border-b text-left text-gray-600 max-xl:hidden">Patient</th>
                        <th class="py-2 px-4 border-b text-left text-gray-600  max-lg:text-xs">Procedure</th>
                        <th class="py-2 px-4 border-b text-left text-gray-600  max-xl:hidden">Branch</th>
                        <th class="py-2 px-4 border-b text-left text-gray-600 max-lg:text-xs ">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvedAppointments as $appointment)
                        <tr>
                            <td class="py-2 px-4 border-b   max-lg:text-xs">
                                {{ $appointment->appointment_date }} - <span
                                    class="font-bold">{{ $appointment->preferred_time }}</span>
                            </td>
                            <td class="py-2 px-4 border-b  max-xl:hidden">{{ $appointment->patient->last_name }},
                                {{ $appointment->patient->first_name }}</td>
                            <td class="py-2 px-4 border-b max-lg:text-xs">
                                {{ $appointment->procedure ? $appointment->procedure->name : 'N/A' }}
                            </td>

                            <td class="py-2 px-4 border-b  max-xl:hidden  ">
                                {{ $appointment->branch ? $appointment->branch->branch_loc : 'N/A' }}</td>

                            <td class="borderb-b py-2 px-4 max-xl:flex justify-center items-center text-xs">
                                {{-- <button class="text-gray-800 border-2 rounded-md px-4 py-2  transition"
                                    onclick="openModal('view_modal_{{ $appointment->id }}')">
                                    View</button> --}}

                                <a href="{{ route('appointments.show', $appointment->id) }}"
                                    class="flex justify-center items-center border rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-400 transition-all">
                                    <h1 class="hidden max-2xl:block text-xs font-semibold text-gray-800">View</h1>
                                    <img class="h-5 sm:h-4 sm:w-4 max-md:h-4 max-md:w-4 max-2xl:hidden"
                                        src="{{ asset('assets/images/user-icon.png') }}" alt="">
                                </a>

                                <div id="view_modal_{{ $appointment->id }}"
                                    class="fixed hidden z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 modal">
                                    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                                        <!-- Modal header -->
                                        <div
                                            class="flex justify-between items-center text-gray-600 text-xl rounded-t-md px-4 py-2">
                                            <h3 class="text-sm py-2 font-semibold">Appointment details</h3>
                                            <button onclick="closeModal('view_modal_{{ $appointment->id }}')">x</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="max-h-96 overflow-y-scroll p-4">
                                            <div class=" bg-white p-4 rounded-lg shadow-md">
                                                <div class="flex flex-col justify-left">
                                                    <h2 class="mt-4 text-xl font-bold">
                                                        {{ $appointment->procedure->name ?? 'None' }}
                                                    </h2>
                                                    <p class="text-gray-500">
                                                        {{ $appointment->appointment_date }} - <span
                                                            class="font-bold">{{ $appointment->preferred_time }}</span>
                                                </div>
                                                <div class="mt-6 max-lg:text-sm">
                                                    <hr class="w-full bg-gray">
                                                    <div class="flex flex-col justify-center items-between">
                                                        <div class="flex flex-col justify-between my-2 py-2 px-4 gap-4">
                                                            <h3 class="font-bold text-gray-600">Teeth No.</h3>
                                                            <p>{{-- {{ $appointment->teeth_number }} --}}26</p>
                                                        </div>
                                                        <hr class="w-full bg-gray">
                                                        <div class="flex justify-between my-2 py-2 px-4 gap-4 flex-col">
                                                            <h3 class="font-bold text-gray-600">Description</h3>
                                                            <p>{{-- {{ $appointment->description }} --}}
                                                                There are three missing teeth, 26 has been extracted due
                                                                to
                                                                extensive caries, 18 and 28 unerupted
                                                            </p>
                                                        </div>
                                                        <hr class="w-full bg-gray">
                                                        <div class="flex justify-between my-2 py-2 px-4 gap-4 flex-col">
                                                            <h3 class="font-bold text-gray-600">Fees</h3>
                                                            <p> &#8369;
                                                                {{ $appointment->procedure->price ?? '0' }}
                                                            </p>
                                                        </div>
                                                        <hr class="w-full bg-gray">
                                                        <div class="flex justify-between my-2 py-2 px-4 gap-4 flex-col">
                                                            <h3 class="font-bold text-gray-600">Remarks</h3>
                                                            <p>{{-- {{ $appointment->remarks }} --}}
                                                                hahahaha remarks
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal footer -->
                                        <div
                                            class="px-4 py-2 border-t border-t-gray-500 flex justify-end items-center space-x-4">
                                            <button class="border text-gray-600 px-4 py-2 rounded-md transition"
                                                onclick="closeModal('view_modal_{{ $appointment->id }}')">Close </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div>
            {{ $approvedAppointments->links() }}
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
