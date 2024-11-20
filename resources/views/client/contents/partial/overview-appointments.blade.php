@if (session('success'))
    <div id="toast" class="absolute bottom-8 right-8">
        @include('components.toast-notification')
    </div>
@endif

<table class="min-w-full bg-white border">
    <thead>
        <tr class="w-full bg-gray-100">
            <th class="py-2 px-4 border-b text-left text-gray-600 max-lg:text-xs">Appointment Date</th>
            <th class="py-2 px-4 border-b text-left text-gray-600  max-xl:hidden">Procedure</th>
            <th class="py-2 px-4 border-b text-left text-gray-600  max-lg:text-xs">Dentist</th>

            <th class="py-2 px-4 border-b text-left text-gray-600 max-xl:hidden">Status</th>
            <th class="py-2 px-4 border-b text-left text-gray-600 max-lg:text-xs">
                Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($appointments as $appointment)
            <tr>
                <td class="py-2 px-4 border-b   max-lg:text-xs">
                    {{ $appointment->appointment_date }} - <span
                        class="font-bold">{{ $appointment->preferred_time }}</span>
                </td>
                <td class="py-2 px-4 border-b max-xl:hidden max-lg:text-xs">
                    {{ $appointment->procedure ? $appointment->procedure->name : 'N/A' }}
                </td>

                <td class="py-2 px-4 border-b    max-lg:text-xs">Dr.
                    {{ $appointment->dentist->dentist_last_name }} {{ $appointment->dentist->dentist_first_name }}
                </td>


                <td class="border-b px-4 py-2 min-w-max h-full max-lg:text-xs max-xl:hidden">
                    @if ($appointment->status === 'Cancelled')
                    <h1 class="text-md text-red-600 font-semibold">Cancelled</h1>

                    @else
                        @if ($appointment->pending === 'Approved')
                            <h1 class="text-md text-green-600 font-semibold">Approved</h1>
                        @elseif ($appointment->pending === 'Declined')
                            <h1 class="text-md text-red-600 font-semibold">Declined</h1>
                        @else
                            <h1 class="text-md text-slate-600 font-semibold">Pending</h1>
                        @endif
                    @endif

                </td>
                <td class="py-2 px-4 max-xl:flex justify-center items-center text-xs">
                    <button class="text-gray-800 border-2 rounded-md px-4 py-2  transition"
                        onclick="openModal('view_modal_{{ $appointment->id }}')">
                        View</button>

                    <div id="view_modal_{{ $appointment->id }}"
                        class="fixed hidden z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 modal">
                        <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                            <!-- Modal header -->
                            <div class="flex justify-between items-center text-gray-600 text-xl rounded-t-md px-4 py-2">
                                <h3 class="text-sm py-2 font-semibold">Appointment details</h3>
                                <button onclick="closeModal('view_modal_{{ $appointment->id }}')">x</button>
                            </div>

                            <!-- Modal body -->
                            <div class="max-h-96 overflow-y-scroll p-4">
                                <div class=" bg-white p-4 rounded-lg shadow-md">
                                    <div class="flex flex-col justify-left">
                                        <h2 class="mt-4 text-xl font-bold">
                                            {{ $appointment->procedure->name }}
                                        </h2>
                                        <p class="text-gray-500">
                                            {{ $appointment->appointment_date }} - <span
                                                class="font-bold">{{ $appointment->preferred_time }}</span>
                                    </div>
                                    <div class="mt-6 max-lg:text-sm">
                                        <hr class="w-full bg-gray">
                                        <div class="flex flex-col justify-center items-between">

                                            <hr class="w-full bg-gray">
                                            <div class="flex justify-between my-2 py-2 px-4 gap-4 flex-col">
                                                <h3 class="font-bold text-gray-600">Description</h3>
                                                <p>
                                                    {{ $appointment->procedure->description }}
                                                </p>
                                            </div>


                                            <hr class="w-full bg-gray">
                                            <div class="flex justify-between my-2 py-2 px-4 gap-4 flex-col">
                                                <h3 class="font-bold text-gray-600">Fees</h3>
                                                <p> &#8369
                                                    {{ $appointment->procedure->price }}
                                                </p>
                                            </div>
                                            <hr class="w-full bg-gray">

                                            <div class="flex justify-between my-2 py-2 px-4 gap-4 flex-col">
                                                <h3 class="font-bold text-gray-600">Branch</h3>
                                                <p>
                                                    {{ $appointment->branch ? $appointment->branch->branch_loc : 'N/A' }}
                                                </p>
                                            </div>
                                            <hr class="w-full bg-gray">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="w-full px-4 py-2 border-t border-t-gray-500 flex justify-between items-center space-x-4">
                                @if ($appointment->status === 'Cancelled')
                                <form action="{{ route('client.cancel', $appointment->id) }}" method="post" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <button class="w-full bg-gray-600 text-white px-4 py-2 rounded-md transition"
                                        type="submit" disabled>Cancelled appointment</button>
                                </form>
                                @elseif($appointment->pending === 'Declined')
                                    <form action="{{ route('client.cancel', $appointment->id) }}" method="post" class="w-full"
                                        >
                                        @csrf
                                        @method('PUT')
                                        <button class="w-full bg-gray-600 text-white px-4 py-2 rounded-md transition"
                                            type="submit" disabled>Declined appointment</button>
                                    </form>
                                @elseif($appointment->payment)
                                        <a href="{{ route('client.form', $appointment->id) }}" class="w-full bg-green-600 text-white px-4 py-2 rounded-md transition"
                                            type="submit">Add payment</a>
                                @else
                                    <form action="{{ route('client.cancel', $appointment->id) }}" method="post" class="w-full"
                                        >
                                        @csrf
                                        @method('PUT')
                                        <button class="w-full bg-red-600 text-white px-4 py-2 rounded-md transition"
                                            type="submit">Cancel appointment</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach

    </tbody>
</table>
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

    setTimeout(() => {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.style.display = 'none';
        }
    }, 3000);

    function closeToast() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.style.display = 'none';
        }
    }
</script>
