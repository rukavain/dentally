@extends('admin.dashboard')
@section('content')
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class="m-4 px-4 pb-4 bg-white shadow-lg rounded-md max-lg:mt-14">
        <div class="flex flex-wrap items-center justify-between py-4">
            <div class="flex items-center gap-4 flex-wrap">
                <h1 class="font-bold text-3xl max-md:text-xl">Schedule list</h1>
                <span class="text-gray-500 text-sm">
                    {{ $weekRange['start'] }} - {{ $weekRange['end'] }}, {{ $weekRange['year'] }}
                </span>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('schedule') }}"
                        class="flex items-center gap-2 text-sm">
                        <select name="sortSchedule" id="sortSchedule"
                            class="border border-gray-300 rounded-md px-3 py-1.5 bg-white focus:border-green-500 focus:ring-1 focus:ring-green-500">
                            <option value="current_week" {{ request()->get('sortSchedule') == 'current_week' ? 'selected' : '' }}>
                                Current Week</option>
                            <option value="next_week" {{ request()->get('sortSchedule') == 'next_week' ? 'selected' : '' }}>
                                Next Week</option>
                            <option value="dentist_asc" {{ request()->get('sortSchedule') == 'dentist_asc' ? 'selected' : '' }}>
                                Dentist Name (A-Z)</option>
                            <option value="dentist_desc" {{ request()->get('sortSchedule') == 'dentist_desc' ? 'selected' : '' }}>
                                Dentist Name (Z-A)</option>
                        </select>
                    </form>
                    <form method="GET" action="{{ route('schedule') }}" class="flex items-center gap-2">
                        <input type="week" name="selectedWeek"
                            value="{{ request()->get('selectedWeek', now()->format('Y-\WW')) }}"
                            class="border border-gray-300 rounded-md px-3 py-1.5 bg-white focus:border-green-500 focus:ring-1 focus:ring-green-500">
                        <button type="submit"
                            class="bg-green-50 text-green-700 px-3 py-1.5 rounded-md hover:bg-green-100 transition-colors">
                            Go to Week
                        </button>
                    </form>
                </div>
            </div>
            <form method="GET" action="{{ route('add.schedule') }}">
                @csrf
                <button onclick="openModal()"
                    class="flex justify-center items-center gap-2 rounded-md py-2 px-4 min-w-max border-2 border-gray-600 hover:shadow-md hover:border-green-700 font-semibold text-gray-800 transition-all max-md:px-2">
                    <span class="max-md:text-xs">Add Schedule</span>
                    <img class="h-6 max-md:h-4" src="{{ asset('assets/images/add.png') }}" alt="">
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @php
                $groupedSchedules = $schedules->groupBy('dentist_id');
                $currentWeek = now()->startOfWeek();
                $weekRange = [
                    $currentWeek->copy()->format('Y-m-d'),
                    $currentWeek->copy()->addDays(6)->format('Y-m-d')
                ];
            @endphp

            @foreach ($groupedSchedules as $dentistId => $dentistSchedules)
                @php
                    $dentist = $dentistSchedules->first()->dentist;
                    // Group schedules by week
                    $weeklySchedules = $dentistSchedules->groupBy(function($schedule) {
                        return \Carbon\Carbon::parse($schedule->date)->startOfWeek()->format('Y-m-d');
                    });
                @endphp

                <div class="bg-gray-50 rounded-lg p-3 shadow hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-base font-semibold text-gray-800 truncate">
                            @if ($dentistId === null)
                                No Assigned Dentist
                            @else
                                Dr. {{ $dentist->dentist_first_name . ' ' . $dentist->dentist_last_name }}
                            @endif
                        </h2>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">
                            {{ $dentistSchedules->count() }}
                        </span>
                    </div>

                    <div class="overflow-hidden">
                        <div class="space-y-3">
                            @foreach ($weeklySchedules as $weekStart => $weekSchedules)
                                @php
                                    $weekEnd = \Carbon\Carbon::parse($weekStart)->addDays(6)->format('M d');
                                    $weekStart = \Carbon\Carbon::parse($weekStart)->format('M d');
                                @endphp
                                <div class="border-b border-gray-200 last:border-b-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <h3 class="text-xs font-medium text-gray-600">{{ $weekStart }} - {{ $weekEnd }}</h3>
                                        <span class="text-xs text-gray-500">{{ $weekSchedules->count() }} schedules</span>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full">
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach ($weekSchedules as $schedule)
                                                    <tr class="text-xs hover:bg-gray-100">
                                                        <td class="py-1.5 pr-2">
                                                            <div class="flex flex-col">
                                                                <span>{{ \Carbon\Carbon::parse($schedule->date)->format('D, M d') }}</span>
                                                                <span class="text-xs text-gray-500">{{ $schedule->branch ? $schedule->branch->branch_loc : 'No branch' }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="py-1.5 px-2">
                                                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                        </td>
                                                        <td class="py-1.5 pl-2 text-right">
                                                            <div class="flex gap-2 justify-end">
                                                                <a href="{{ route('schedule.edit', $schedule->id) }}"
                                                                    class="text-green-600 hover:text-green-800">Edit</a>
                                                                <button
                                                                    onclick="document.getElementById('delete_modal_{{ $schedule->id }}').showModal()"
                                                                    class="text-red-600 hover:text-red-800">Delete</button>
                                                            </div>

                                                            <dialog id="delete_modal_{{ $schedule->id }}"
                                                                class="modal p-4 rounded-md max-md:text-lg">
                                                                <div class="modal-box flex flex-col">
                                                                    <h3 class="text-lg font-bold max-md:text-sm text-left">Schedule</h3>
                                                                    <p class="py-4 max-md:text-sm mb-4">Are you sure you want to delete this
                                                                        item?</p>
                                                                    <div class="modal-action flex gap-2 self-end">
                                                                        <form method="dialog"
                                                                            class="border rounded-md hover:bg-gray-300 transition-all py-2 px-4">
                                                                            <button class="btn max-md:text-xs">Close</button>
                                                                        </form>
                                                                        <form method="POST"
                                                                            action="{{ route('schedule.delete', $schedule->id) }}"
                                                                            class="border rounded-md bg-red-500 hover:bg-red-700 text-white transition-all py-2 px-4">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="btn max-md:text-xs">Delete</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </dialog>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $schedules->links() }}
        </div>
    </section>

    <script>
        document.getElementById('sortSchedule').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
@endsection
