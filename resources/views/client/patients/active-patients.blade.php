@extends('admin.dashboard')
@section('content')
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class="m-4 max-lg:mt-14 p-4 bg-white shadow-lg rounded-md">
        <div class="flex items-start justify-center max-md:items-start max-md:justify-start flex-col max-md:flex-wrap">
            <label class="flex justify-between w-full items-start gap-2" for="time">
                <h1 class="font-bold text-3xl max-md:text-xl min-w-max">Active Patient list</h1>
                <form class="" method="GET" action="{{ route('add.patient') }}">
                    @csrf
                    <button onclick="openModal()"
                        class="flex justify-center items-center gap-2  rounded-md py-2 px-4 min-w-max border-2 border-gray-600 hover:shadow-md hover:border-green-700 font-semibold text-gray-800 transition-all max-md:px-2">
                        <span class="max-md:text-xs"> Add patient</span>
                        <img class="h-8 max-md:h-4" src="{{ asset('assets/images/add-patient.png') }}" alt="">
                    </button>
                </form>
            </label>
            <form method="GET" class="flex flex-wrap items-center gap-4 my-4" action="{{ route('patient.active') }}">
                <div class="flex items-center gap-4 flex-1">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patients..."
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Sort Options -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600" for="sort">Sort by:</label>
                        <select id="sort" name="sort"
                            class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="next_visit" {{ request('sort') == 'next_visit' ? 'selected' : '' }}>Next Visit</option>
                            <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="date_added" {{ request('sort') == 'date_added' ? 'selected' : '' }}>Date Added</option>
                        </select>
                    </div>

                    <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">

                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-all text-sm">
                        Apply Filters
                    </button>
                </div>
            </form>

        </div>

        <!-- run @/foreach for each field/row  -->
        <table class="w-full table-auto mt-2 overflow-hidden">
            <thead>
                <tr class="bg-green-200 text-green-700">
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            ID
                            @if(request('sort') === 'id')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Name
                            @if(request('sort') === 'name')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="border px-4 py-2 max-lg:hidden">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'next_visit', 'direction' => request('sort') === 'next_visit' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Date of next visit
                            @if(request('sort') === 'next_visit')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="border px-4 py-2 max-lg:hidden">Contacts</th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($activePatients as $patient)
                    {{-- <tr class="odd:bg-green-100 even:bg-slate-100"> --}}
                    <tr class="border-b-2">
                        <td class=" px-4 py-2 max-md:py-1 max-md:px-2 text-sm max-md:text-xs ">{{ $patient->id }}</td>
                        <td class=" px-4 py-2 max-md:py-1 max-md:px-2 text-sm max-md:text-xs">{{ $patient->last_name }}
                            {{ $patient->first_name }}</td>
                        <td class=" px-4 py-2 text-sm max-lg:hidden">{{ $patient->next_visit }}</td>
                        <td class=" px-4 py-2 text-sm max-lg:hidden">
                            <div class="flex justify-center items-center gap-6">
                                <div class="tooltip">
                                    <img class="h-5" src="{{ asset('assets/images/phone-call.png') }}" alt="Call Icon">
                                    <span class="tooltiptext">{{ $patient->phone_number }}</span>
                                </div>
                                <div class="tooltip">
                                    <img class="h-5" src="{{ asset('assets/images/facebook-icon.png') }}"
                                        alt="Facebook Icon">
                                    <span class="tooltiptext">{{ $patient->fb_name }}</span>
                                </div>
                            </div>
                        </td>
                        <td class=" py-2">
                            <div class="flex gap-2 justify-center items-center">
                                <a class=" border border-slate-600 rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-sm text-white font-semibold hover:bg-gray-400 max-d transition-all max-md:hidden"
                                    href=" {{ route('edit.patient', $patient->id) }} ">
                                    <img class="h-5 sm:h-4 sm:w-4 max-md:h-4 max-md:w-4"
                                        src="{{ asset('assets/images/edit-icon.png') }}" alt="">
                                </a>
                                <a href="{{ route('show.patient', $patient->id) }}"
                                    class="border border-slate-600 rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-sm text-white font-semibold hover:bg-gray-400 transition-all">
                                    <h1 class="hidden max-md:block text-xs font-semibold text-gray-800">View</h1>
                                    <img class="h-5 sm:h-4 sm:w-4 max-md:h-4 max-md:w-4 max-md:hidden"
                                        src="{{ asset('assets/images/user-icon.png') }}" alt="">
                                </a>
                                <a
                                    class="border border-slate-600 rounded-md  text-white font-semibold hover:bg-gray-400 max-d transition-all max-md:hidden">
                                    @if (is_null($patient->archived_at))
                                        <button class="py-2 px-4 max-md:py-1 max-md:px-2 " type="submit"
                                            onclick="document.getElementById('archive_modal_{{ $patient->id }}').showModal()">
                                            <img class="h-5 sm:h-4 sm:w-4 max-md:h-4 max-md:w-4 max-md:hidden"
                                                src="{{ asset('assets/images/archive.png') }}" alt=""></button>
                                        <dialog id="archive_modal_{{ $patient->id }}"
                                            class="modal border-2 shadow-lg border-gray-400 p-8 rounded-md max-md:text-lg">
                                            <div class="modal-box flex flex-col">
                                                <h3 class="text-2xl font-bold max-md:text-sm">Archive Patient</h3>
                                                <p class="py-4 font-normal max-md:text-sm">Are you sure you want to
                                                    archive
                                                    {{ $patient->last_name . ' ' . $patient->first_name }}?</p>
                                                <div class="modal-action flex gap-2 self-end">
                                                    <form method="dialog" class="border rounded-md w-max py-2 px-4">
                                                        <button class="btn max-md:text-xs">Close</button>
                                                    </form>
                                                    <form action="{{ route('archive.patient', $patient->id) }}"
                                                        method="POST"
                                                        class="border  bg-red-600 text-white rounded-md py-2 px-4">
                                                        @csrf
                                                        <button
                                                            class="btn  bg-red-600 text-white max-md:text-xs w-max flex gap-2">Yes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </dialog>
                                    @else
                                        <form action="{{ route('restore.patient', $patient->id) }}" method="POST">
                                            @csrf
                                            <button class="py-2 px-4 max-md:py-1 max-md:px-2" type="submit">
                                                <img class="h-5 sm:h-4 sm:w-4 max-md:h-4 max-md:w-4 max-md:hidden"
                                                    src="{{ asset('assets/images/restore.png') }}" alt=""></button>
                                        </form>
                                    @endif
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-full m-2">
            {{ $activePatients->links() }}
        </div>

    </section>

    <script>
        document.getElementById('sort').addEventListener('change', function() {
            this.form.submit();
        });



        // Check if the modal element exists before interacting with it
        document.querySelectorAll('[id^="archive_modal_"]').forEach((modal) => {
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
