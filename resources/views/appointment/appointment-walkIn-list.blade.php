@extends('admin.dashboard')
@section('content')
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class=" m-4 p-4 bg-white shadow-lg rounded-md max-lg:mt-14">
        <div class="flex items-center justify-between pt-3 pb-6 max-lg:flex-wrap gap-4 max-md:gap-2 max-md:pt-1 max-md:pb-3">
            <div class="flex gap-4 max-md:gap-2">
                <label class="flex items-center gap-2" for="time">
                    <h1 class="font-bold text-3xl mr-4 max-md:mr-0 max-md:text-2xl">Walk-in Request</h1>
                </label>
                <div>
                    <form method="GET" action="{{ route('appointments.walkIn') }}"
                        class="flex max-lg:text-xs gap-1 items-center max-lg:m-1">
                        <h1 class="font-semibold">Sort by: </h1>
                        <select name="sort" id="sortBy"
                            class="border text-sm w-auto border-gray-400 pr-6 mx-2 rounded-md max-lg:text-xs"
                            onchange="this.form.submit()">
                            <option value="created_at" {{ $sort == 'created_at' ? 'selected' : '' }}>
                                Date Submitted</option>
                            <option value="preferred_time" {{ $sort == 'preferred_time' ? 'selected' : '' }}>Appointment
                                Time
                            </option>
                            <option value="appointment_date" {{ $sort == 'appointment_date' ? 'selected' : '' }}>Appointment
                                Date
                            </option>
                            <option value="branch" {{ $sort == 'branch' ? 'selected' : '' }}>Branch
                            </option>
                            <option value="status" {{ $sort == 'status' ? 'selected' : '' }}>Status
                            </option>
                        </select>
                    </form>
                </div>
            </div>
            <form method="GET" class="justify-end" action="{{ route('add.walkIn') }}">
                @csrf
                <button onclick="openModal()"
                    class="flex justify-center items-center gap-2 rounded-md py-2 px-4 my-2 min-w-max border-2 border-gray-600 hover:shadow-md hover:border-green-700 font-semibold text-gray-800 transition-all max-md:px-2">
                    <span class="max-md:text-xs"> Add Appointment</span>
                    <img class="h-6 max-md:h-4" src="{{ asset('assets/images/add.png') }}" alt="">
                </button>
            </form>
        </div>
        <table class="w-full table-auto text-center">
            <thead>
                <tr class="bg-green-200 text-green-700">
                    <th class="max-lg:py-2 max-lg:px-2 border max-lg:text-xs">Patient</th>
                    <th class="max-lg:py-2 max-lg:px-2 border max-lg:text-xs max-2xl:hidden">Date Submitted</th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs">Appointment Date</th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs max-2xl:hidden">Preferred
                        time
                    </th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs max-2xl:hidden">Branch</th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs">Status</th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs">Actions</th>
                </tr>
            </thead>
            {{-- testing --}}

            {{-- testing --}}
            <tbody>

                @foreach ($walkin_appointments as $appointment)
                    <tr class="border-b-2">
                        <td class=" max-lg:py-2 py-2 max-lg:px-2 text-black text-sm max-lg:text-xs">
                            <span class="max-lg:hidden">{{ $appointment->patient->first_name }}</span>
                            {{ $appointment->patient->last_name }}
                        </td>
                        <td class=" max-lg:py-2 py-2 max-lg:px-2 text-sm max-lg:text-xs max-2xl:hidden">
                            {{ $appointment->created_at }}
                        </td>
                        <td class=" max-lg:py-2 py-2 max-lg:px-2 text-sm max-lg:text-xs ">
                            {{ $appointment->appointment_date }}
                        </td>
                        <td class=" max-lg:py-2 py-2 max-lg:px-2 text-sm max-lg:text-xs max-2xl:hidden">

                            {{ $appointment->preferred_time }}</td>
                        <td class=" max-lg:py-2 py-2 max-lg:px-2 text-sm max-lg:text-xs max-2xl:hidden">

                            {{ $appointment->branch->branch_loc }}</td>
                        <td class="px-4 py-2 min-w-max h-full text-sm max-lg:text-xs ">
                            @if ($appointment->pending === 'Approved')
                                <h1 class="text-xs  text-green-600 font-semibold bg-green-200 rounded-full">
                                    <span class="max-lg:hidden max-lg:text-xs">&#9679;</span> Approved
                                </h1>
                            @elseif ($appointment->pending === 'Declined')
                                <h1 class="text-xs  text-red-600 font-semibold bg-red-200 rounded-full">
                                    <span class="max-lg:hidden max-lg:text-xs">&#9679;</span>
                                    Declined
                                </h1>
                            @else
                                <h1 class="text-xs  text-slate-600 font-semibold bg-slate-200 rounded-full">
                                    <span class="max-lg:hidden max-lg:text-xs">&#9679;</span> Pending
                                </h1>
                            @endif
                        </td>
                        {{-- <td class="py-2 px-2 flex gap-2 justify-center max-lg:text-xs max-2xl:hidden h-max">
                            @if ($appointment->pending === 'Approved')
                                <form method="POST"
                                    action="{{ route('appointments.approve', $appointment->id) }}">
                                    @csrf
                                    <div class="tooltip">
                                        <button type="submit" class="btn btn-success btn-sm " disabled>
                                            <img src="{{ asset('assets/images/accept.png') }}" alt="">
                                            <span class="tooltiptext">Approved</span>
                                        </button>
                                    </div>
                                </form>
                            @elseif($appointment->pending === 'Declined')
                                <form method="POST"
                                    action="{{ route('appointments.decline', $appointment->id) }}">
                                    @csrf
                                    <div class="tooltip">
                                        <button type="submit" class="btn btn-danger btn-sm" disabled>
                                            <img src="{{ asset('assets/images/decline.png') }}" alt="">
                                            <span class="tooltiptext">Declined</span>
                                        </button>
                                    </div>
                                </form>
                            @else
                                <form method="POST"
                                    action="{{ route('appointments.approve', $appointment->id) }}">
                                    @csrf
                                    <div class="tooltip">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <img src="{{ asset('assets/images/accept.png') }}" alt="">
                                            <span class="tooltiptext">Approve</span>
                                        </button>
                                    </div>
                                </form>
                                <form method="POST"
                                    action="{{ route('appointments.decline', $appointment->id) }}">
                                    @csrf
                                    <div class="tooltip">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <img src="{{ asset('assets/images/decline.png') }}" alt="">
                                            <span class="tooltiptext">Decline</span>
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </td> --}}
                        <td class="p-1 justify-center items-center max-lg:text-xs">
                            <a href="{{ route('show.appointment', $appointment->id) }}"
                                class="flex justify-center items-center border rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-400 transition-all">
                                <h1 class="hidden max-2xl:block text-xs font-semibold text-gray-800">View</h1>
                                <img class="h-5 sm:h-4 sm:w-4 max-md:h-4 max-md:w-4 max-2xl:hidden"
                                    src="{{ asset('assets/images/user-icon.png') }}" alt="">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $walkin_appointments->links() }}

        </div>

    </section>
    <script>
        document.getElementById('sortBy').addEventListener('change', function() {
            const selectedValue = this.value;

            window.location.href = window.location.pathname + '?sort=' + selectedValue;
        });



        function fadeOut(element) {
            element.classList.add('hidden');
            setTimeout(() => {
                element.style.display = 'none'; // Optionally hide the element after fading out
            }, 1000); // Match this duration with the CSS transition duration
        }

        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.fade-out');
            messages.forEach(message => {
                setTimeout(() => {
                        fadeOut(message);
                    },
                    1000
                ); // Change this duration to how long you want the message to be visible (in milliseconds)
            });
        });
    </script>
@endsection
