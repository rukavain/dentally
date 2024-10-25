@extends('admin.dashboard')
@section('content')
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class=" m-4 px-4 pb-4 bg-white shadow-lg rounded-md  max-lg:mt-14">
        <div class="flex flex-wrap items-center justify-between py-4">
            <label class="flex items-center gap-4" for="time">
                <h1 class="font-bold text-3xl max-md:text-xl min-w-max">Schedule list</h1>
                <form method="GET" action="{{ route('schedule') }}"
                    class="flex max-lg:text-xs gap-1 items-center max-lg:m-1">
                    <h1 class="font-semibold">Sort by: </h1>
                    <select name="sortSchedule" id="sortSchedule"
                        class="border text-sm w-auto border-gray-400 pr-6 mx-2 rounded-md max-lg:text-xs">
                        <option value="date" {{ request()->get('sortSchedule') == 'date' ? 'selected' : '' }}>
                            Schedule Date</option>
                        <option value="start_time" {{ request()->get('sortSchedule') == 'start_time' ? 'selected' : '' }}>
                            Start Time
                        </option>
                        <option value="end_time" {{ request()->get('sortSchedule') == 'end_time' ? 'selected' : '' }}>End
                            Time
                        </option>
                    </select>
                </form>
            </label>
            <form method="GET" action="{{ route('add.schedule') }}">
                @csrf
                <button onclick="openModal()"
                    class="flex justify-center items-center gap-2 rounded-md py-2 px-4 min-w-max border-2 border-gray-600 hover:shadow-md hover:border-green-700 font-semibold text-gray-800 transition-all max-md:px-2">
                    <span class="max-md:text-xs"> Add Schedule</span>
                    <img class="h-6 max-md:h-4" src="{{ asset('assets/images/add.png') }}" alt="">
                </button>
            </form>
        </div>
        <table class="w-full table-auto text-center overflow-hidden">
            <thead>
                <tr class="bg-green-200 text-green-700">
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Dentist</th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Date</th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Branch</th>
                    <th class="border px-4 py-2 max-xl:hidden">Start Time</th>
                    <th class="border px-4 py-2 max-xl:hidden">End Time</th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                    <tr class="border-b-2">
                        <td class=" px-2 py-2 text-sm max-md:py-1 max-md:px-2 max-md:text-xs">
                            @if ($schedule->dentist_id === null)
                                {{ 'No dentist' }}
                            @else
                                Dr.
                                {{ $schedule->dentist->dentist_first_name . ' ' . $schedule->dentist->dentist_last_name }}
                            @endif
                        </td>
                        <td class=" px-2 py-2 text-sm max-md:py-1 max-md:px-2 max-md:text-xs">
                            {{ $schedule->date }}
                        </td>
                        <td class=" px-2 py-2 text-sm max-md:py-1 max-md:px-2 max-md:text-xs">
                            @if ($schedule->branch_id === null)
                                {{ 'No branch' }}
                            @else
                                {{ $schedule->branch->branch_loc }}
                            @endif
                        </td>
                        <td class=" px-2 py-2 text-sm max-md:py-1 max-md:px-2 max-xl:hidden ">
                            {{ $schedule->start_time }}</td>
                        <td class=" px-2 py-2 text-sm max-md:py-1 max-md:px-2 max-xl:hidden">
                            {{ $schedule->end_time }}</td>
                        <td class=" px-2 py-2 text-sm max-md:py-1 max-md:px-2 max-md:text-xs max-xl:hidden">
                            <div class="flex gap-2 justify-center flex-wrap items-center">
                                <a class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-300 transition-all"
                                    href="{{ route('schedule.edit', $schedule->id) }}">
                                    <h1 class="text-xs text-gray-700 text-center">Edit</h1>
                                </a>

                                <div class="flex self-start max-md:text-xs ">
                                    <div
                                        class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-300 transition-all">
                                        <button class="  text-xs text-gray-700 text-center"
                                            onclick="document.getElementById('delete_modal_{{ $schedule->id }}').showModal()">Delete</button>
                                    </div>
                                    <dialog id="delete_modal_{{ $schedule->id }}"
                                        class="modal p-4 rounded-md max-md:text-lg">
                                        <div class="modal-box flex flex-col">
                                            <h3 class="text-lg font-bold max-md:text-sm text-left">Schedule</h3>
                                            <p class="py-4 max-md:text-sm mb-4">Are you sure you want to delete this item?
                                            </p>
                                            <div class="modal-action flex gap-2 self-end">
                                                <form method="dialog"
                                                    class="border rounded-md hover:bg-gray-300 transition-all py-2 px-4">
                                                    <button class="btn max-md:text-xs">Close</button>
                                                </form>
                                                <form method="POST" action="{{ route('schedule.delete', $schedule->id) }}"
                                                    class="border rounded-md bg-red-500 hover:bg-red-700 text-white transition-all py-2 px-4">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn max-md:text-xs">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                </div>
                            </div>

                        </td>
                        <td class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs hidden max-xl:flex">
                            <a href=""
                                class="flex justify-center items-center flex-1 m-1 border border-slate-600 rounded-md py-2 px-4 text-gray-900 font-semibold hover:bg-gray-400 transition-all">
                                <span class="hidden text-xs max-xl:block">View</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $schedules->links() }}
        </div>
    </section>

    <script>
        document.getElementById('sortSchedule').addEventListener('change', function() {
            this.form.submit();
        });

        document.querySelectorAll('[id^="delete_modal_"]').forEach((modal) => {
            if (modal) {
                const modalId = modal.id;
                const button = document.querySelector(
                    `[onclick="document.getElementById('${modalId}').showModal()"]`);

                if (button) {
                    button.addEventListener('click', () => {
                        modal.showModal();
                    });
                }
            }
        });
    </script>
@endsection


{{-- - schedule td still not responsive, missing schedule information page. - --}}
