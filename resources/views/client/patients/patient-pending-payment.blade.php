@extends('admin.dashboard')
@section('content')
    <style>
        .image-modal {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            justify-content: center;
            overflow-y: auto;
            background-size: contain;
            /* Overlay */
        }
    </style>
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class=" m-4 p-4 bg-white shadow-lg rounded-md max-lg:mt-14">
        <div class="flex flex-col items-start justify-start py-2  max-lg:flex-wrap">
            <div class="">
                <a href="{{ route('payments.list', $patient->id) }}"
                    class="flex justify-start font-semibold max-lg:text-xs border-gray-600 py-1 max-lg:px-2 w-max gap-2"><img
                        class="h-6" src="{{ asset('assets/images/arrow-back.png') }}" alt=""> Back</a>
            </div>
            <label class="flex items-center gap-2" for="time">
                <h1 class="font-bold text-3xl mr-4 max-md:mr-0 max-md:text-2xl">Pending payment list</h1>
            </label>
        </div>
        @if ($pendingPayments->isEmpty())
            <p>No pending payments to review.</p>
        @else
            <table class="w-full table-auto text-center mb-2 overflow-hidden">
                <thead>
                    <tr class="bg-green-200 text-green-700">
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Procedure name</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Appointment to</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Paid Amount</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Payment Method</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Remarks</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Payment Proof</th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingPayments as $pending)
                        <tr class="border-b-2 last:border-b-0">
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                                {{ $pending->payment->appointment->procedure->name }}</td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs"> Dr.
                                {{ $pending->payment->appointment->dentist->dentist_last_name }}
                                {{ $pending->payment->appointment->dentist->dentist_first_name }}
                            </td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                                &#8369; {{ number_format($pending->paid_amount, 2) }}</td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $pending->payment_method }}</td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $pending->remarks }}</td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                                {{-- make modal for viewing --}}
                                @if ($pending->payment_proof)
                                    {{-- <a href="{{ asset('storage/' . $pending->payment_proof) }}" target="_blank">View
                                        Proof</a> --}}
                                    <button
                                        class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 font-semibold hover:bg-gray-300 transition-all"
                                        onclick="openModal('{{ asset('storage/' . $pending->payment_proof) }}')">View
                                        Proof</button>
                                @else
                                    No proof uploaded
                                @endif
                            </td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                                <form action="{{ route('payments.approve', $pending->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 font-semibold hover:bg-gray-300 transition-all">Approve</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        {{-- <table class="w-full table-auto mb-2 overflow-hidden">
            <thead>
                <tr class="bg-green-200 text-green-700">
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Payment</th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Appointment to</th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Amount due</th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Status</th>
                    <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @if ($pendingPayments->isEmpty())
                    <h1>no payments</h1>
                @else
                    @foreach ($pendingPayments as $pending)
                        <tr class="border-b-2">
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $pending->payment_id }}
                            </td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $pending->payment_proof }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table> --}}
        <div id="imageModal" class="image-modal hidden">
            <div class="p-4 rounded">
                <span id="closeModal"
                    class="fixed right-5 cursor-pointer text-3xl text-white bg-green-500 rounded-full px-2">&times;</span>upload-
                <img id="modalImage" src="" alt="Modal Image" class="max-h-screen img-fluid">
            </div>
        </div>
    </section>
    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
        }
        document.getElementById('closeModal').onclick = function() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }
        // Close modal when clicking outside of the image
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
@endsection
