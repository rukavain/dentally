@extends('admin.dashboard')
@section('content')
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    {{-- <style>
        * {
            border: 1px solid red;
        }
    </style> --}}
    <section class=" m-4 p-4 bg-white shadow-lg rounded-md max-lg:mt-14">
        <div class="flex items-center justify-between pt-3 pb-6 max-lg:flex-wrap gap-4 max-md:gap-2 max-md:pt-1 max-md:pb-3">
            <div class="flex gap-4 max-md:gap-2">
                <label class="flex items-center gap-2" for="time">
                    <h1 class="font-bold text-3xl mr-4 max-md:mr-0 max-md:text-2xl">Online Request</h1>
                </label>
            </div>
        </div>
        <table class="w-full table-auto text-center">
            <thead>
                <tr class="bg-green-200 text-green-700">
                    <th class="max-lg:py-2 max-lg:px-2 border max-lg:text-xs">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'patient', 'direction' => request('sort') === 'patient' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Patient
                            @if(request('sort') === 'patient')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="max-lg:py-2 max-lg:px-2 border max-lg:text-xs max-2xl:hidden">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_submitted', 'direction' => request('sort') === 'date_submitted' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Date Submitted
                            @if(request('sort') === 'date_submitted')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'appointment_date', 'direction' => request('sort') === 'appointment_date' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Appointment Date
                            @if(request('sort') === 'appointment_date')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs max-2xl:hidden">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'time', 'direction' => request('sort') === 'time' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Preferred time
                            @if(request('sort') === 'time')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs max-2xl:hidden">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'branch', 'direction' => request('sort') === 'branch' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Branch
                            @if(request('sort') === 'branch')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Status
                            @if(request('sort') === 'status')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-2 px-4 max-lg:py-2 max-lg:px-2 border max-lg:text-xs">Actions</th>
                </tr>
            </thead>
            {{-- testing --}}

            {{-- testing --}}
            <tbody>
                @foreach ($online_appointments as $appointment)
                    <tr class="border-b-2">
                        <td class=" max-lg:py-2 max-lg:px-2 text-black max-lg:text-xs text-sm">
                            <span class="max-lg:hidden">{{ $appointment->patient->first_name }}</span>
                            {{ $appointment->patient->last_name }}
                        </td>
                        <td class=" max-lg:py-2 max-lg:px-2 max-lg:text-xs text-sm max-2xl:hidden">
                            {{ $appointment->created_at }}
                        </td>
                        <td class=" max-lg:py-2 max-lg:px-2 max-lg:text-xs text-sm ">
                            {{ $appointment->appointment_date }}</td>
                        <td class=" max-lg:py-2 max-lg:px-2 max-lg:text-xs text-sm max-2xl:hidden">

                            {{ $appointment->preferred_time }}</td>
                        <td class=" max-lg:py-2 max-lg:px-2 max-lg:text-xs text-sm max-2xl:hidden">

                            {{ $appointment->branch->branch_loc }}</td>
                        <td class="px-4 py-2 min-w-max h-full max-lg:text-xs text-sm ">
                            @if ($appointment->status != 'Cancelled')
                                @if ($appointment->pending === 'Approved')
                                    <h1 class="text-xs  text-green-600 font-semibold bg-green-200 rounded-full">

                                        <span>&#9679;</span> {{ $appointment->pending }}
                                    </h1>
                                @elseif ($appointment->pending === 'Declined')
                                    <h1 class="text-xs  text-red-600 font-semibold bg-red-200 rounded-full">

                                        <span>&#9679;</span> {{ $appointment->pending }}
                                    </h1>
                                @else
                                    <h1 class="text-xs  text-slate-600 font-semibold bg-slate-200 rounded-full">

                                        <span>&#9679;</span> {{ $appointment->pending }}
                                    </h1>
                                @endif
                            @else
                                <h1 class="text-xs  text-blue-600 font-semibold bg-blue-200 rounded-full">

                                    <span>&#9679;</span> {{ $appointment->status }}
                                </h1>
                            @endif
                        </td>
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
            {{ $online_appointments->links() }}
        </div>
    </section>
    <script>
        document.getElementById('sortBy').addEventListener('change', function() {
            const selectedValue = this.value;

            window.location.href = window.location.pathname + '?sort=' + selectedValue;
        });
    </script>
@endsection
