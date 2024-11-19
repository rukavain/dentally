@extends('admin.dashboard')
@section('content')
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class=" m-4 max-lg:mt-14 px-4 pb-4 bg-white shadow-lg rounded-md">
        <div class="flex items-center justify-between py-4 max-md:py-2">
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2" for="time">
                    <h1 class="font-bold text-3xl max-md:text-xl">Staff list</h1>
                </label>
                <form method="GET" action="{{ route('staff') }}" class="flex items-center gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search staff..."
                        class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-all text-sm">
                        Search
                    </button>
                </form>
            </div>
            <form method="GET" action="{{ route('add.staff') }}">
                @csrf
                <button onclick="openModal()"
                    class="flex self-center justify-center items-center gap-2 rounded-md py-2 px-4 min-w-max border-2 border-gray-600 hover:shadow-md hover:border-green-700 font-semibold text-gray-800 transition-all max-md:py-1 max-md:px-2 max-md:text-xs">
                    <span class=""> Add Staff</span>
                    <img class="h-8 max-md:h-5" src="{{ asset('assets/images/add-patient.png') }}" alt="">
                </button>
            </form>
        </div>
        <table class="w-full table-auto mb-2 overflow-hidden">
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
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'branch', 'direction' => request('sort') === 'branch' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center gap-1">
                            Branch
                            @if(request('sort') === 'branch')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staffs as $staff)
                    <tr class="border-b-2">
                        <td class=" px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $staff->id }}</td>
                        <td class=" px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                            {{ $staff->last_name }} {{ $staff->first_name }}
                        </td>
                        <td class=" px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $staff->branch->branch_loc }}
                        </td>
                        <td class=" py-2 max-md:py-2">
                            <div class="flex gap-2 justify-center items-center">
                                <a class=" border max-md:hidden border-slate-600 rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-400 transition-all"
                                    href=" {{ route('edit.staff', $staff->id) }} ">
                                    <img class="h-5 sm:h-4 sm:w-4 max-sm:h-4 max-sm:w-5"
                                        src="{{ asset('assets/images/edit-icon.png') }}" alt="">
                                </a>

                                <a href="{{ route('show.staff', $staff->id) }}"
                                    class="border border-slate-600 rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-400 transition-all">
                                    <img class="h-5 max-md:hidden sm:h-4 sm:w-4 max-md:h-4 max-md:w-5"src="{{ asset('assets/images/user-icon.png') }}"
                                        alt="">
                                    <h1 class="hidden max-md:block text-gray-800 text-xs">View</h1>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </section>

    <script>
        document.getElementById('sort').addEventListener('change', function() {
            this.form.submit();
            document.getElementById('package').toUpperCase();
        });
    </script>
@endsection
