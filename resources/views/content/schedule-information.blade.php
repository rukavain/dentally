.
@extends('admin.dashboard')
@section('content')
    <div class="m-4">
        @include('components.search')
    </div>
    <section class="bg-white m-4 p-8 mt-14 shadow-lg rounded-md flex flex-col justify-center z-0">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex flex-col mb-7">
                    <h1 class="text-5xl font-bold max-md:text-3xl">
                        {{-- {{ $schedule->dentist->dentist_first_name . ' ' . $schedule->dentist->dentist_last_name }} --}}
                        Dentist Name HAHA
                    </h1>
                </div>

                <div class="flex flex-col gap-3 text-md">
                    <h1 class="max-md:text-sm"> Branch: <span class="font-semibold">
                            {{-- {{ $schedule->dentist->branch }} --}}
                            branchLoc
                        </span> </h1>
                    <h1 class="max-md:text-sm"> Date: <span class="font-semibold">
                            {{-- {{ $schedule->date }} --}}
                            20-20-20
                        </span>
                    </h1>
                    <h1 class="max-md:text-sm"> Start time: <span class="font-semibold">
                            {{-- {{ $schedule->start_time }} --}}
                            10:00
                        </span> </h1>
                    <h1 class="max-md:text-sm"> End time: <span class="font-semibold">
                            {{-- {{ $schedule->end_time }} --}}
                            12:00
                        </span> </h1>
                    <h1 class="max-md:text-sm">Appointment Duration: <span class="font-semibold">
                            {{-- {{ $schedule->appointment_duration }} --}}
                            60
                            minutes </span> </h1>
                </div>
                <div class="flex justify-start items-center gap-4 mt-4">
                    <a class="max-lg:text-xs flex items-center justify-start gap-2 py-2 px-4 border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all"
                        href="">
                        <h1 class="max-md:text-xs">Edit</h1>
                    </a>

                    <div class="flex gap-2 flex-wrap justify-start items-start ">
                        <div class="flex self-start max-md:text-xs ">
                            <div
                                class="max-lg:text-xs flex-1 flex items-center justify-start gap-2 py-2 px-4 border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all">
                                <button class=" text-gray-700 text-center" onclick="openDeleteModal()">Delete</button>
                            </div>
                            <dialog id="deleteModal" class="modal p-4 rounded-md max-md:text-lg">
                                <div class="modal-box flex flex-col">
                                    <h3 class="text-lg font-bold max-md:text-sm text-left">Inventory</h3>
                                    <p class="py-4 max-md:text-sm mb-4">Are you sure you want to delete this
                                        item?
                                    </p>
                                    <div class="modal-action flex gap-2 self-end">
                                        <form method="dialog"
                                            class="border rounded-md hover:bg-gray-300 transition-all py-2 px-4">
                                            <button class="btn max-md:text-xs">Close</button>
                                        </form>
                                        <form method="dialog"
                                            class="border bg-red-600 text-white  rounded-md hover:bg-gray-300 transition-all py-2 px-4">
                                            <button class="btn max-md:text-xs">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        </div>
                    </div>
                    <a class="max-lg:text-xs flex items-center justify-start gap-2 py-2 px-4 border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all"
                        href="{{ route('schedule') }}">
                        <h1 class="max-md:text-xs text-gray-700">Go back</h1>
                    </a>
                </div>
            </div>

    </section>
    <script>
        function openDeleteModal() {
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.showModal();
        }
    </script>
@endsection
