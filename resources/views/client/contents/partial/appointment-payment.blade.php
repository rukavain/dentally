<div class="container">
    @if ($payments->isEmpty())
        <p>No payments found for your appointments.</p>
    @else
        <table class="min-w-full bg-white border text-center">

            <thead>
                <tr class="w-full bg-gray-100">
                    <th class="py-1 px-4 border-b text-gray-600 max-lg:text-xs">Date</th>
                    <th class="py-1 px-4 border-b text-gray-600 max-lg:text-xs">Appointment to</th>
                    <th class="py-1 px-4 border-b text-gray-600 max-xl:hidden">Amount</th>
                    <th class="py-1 px-4 border-b text-gray-600 max-xl:hidden">Status</th>
                    <th class="py-1 px-4 border-b text-gray-600 max-lg:text-xs">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr class="border-b-2">
                        {{-- <td class="border px-4 py-2 max-md:py-1 max-md"> {{ $payment->id }}</td> --}}
                        <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $payment->procedure->name }}
                        </td>
                        <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs"> Dr.
                            {{ $payment->dentist->dentist_last_name . ' ' . $payment->dentist->dentist_first_name }}
                        </td>
                        <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs max-xl:hidden">
                            {{ $payment->procedure->price }}
                        </td>
                        <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs max-xl:hidden">
                            @if (is_null($payment->payment))
                                <h1 class="text-xs  font-semibold bg-red-200 text-red-700 rounded-full py-1">
                                    &#9679; No Payment</h1>
                            @elseif ($payment->payment->status === 'Paid')
                                <h1 class="text-xs  font-semibold bg-green-200 text-green-700 rounded-full py-1">
                                    &#9679; Paid</h1>
                            @elseif ($payment->payment->status === 'Pending')
                                <h1 class="text-xs  font-semibold bg-slate-200 text-slate-700 rounded-full py-1">
                                    &#9679; Pending</h1>
                            @endif
                        </td>
                        <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs max-md:flex">
                            @if (is_null($payment->payment))
                                <div class=" flex gap-2 justify-center flex-wrap items-center">
                                </div>
                            @elseif ($payment->payment->status === 'Pending')
                                <div class=" flex gap-2 justify-center flex-wrap items-center">
                                    <a class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-300 transition-all"
                                        href="{{ route('client.form', $payment->id) }}">
                                        <h1 class=" text-xs text-gray-700 text-center">Add Payment</h1>
                                    </a>
                                </div>
                            @elseif ($payment->payment->status === 'Paid')
                                <div class=" flex gap-2 justify-center flex-wrap items-center">
                                    <a class=" border border-slate-600 flex max-md:flex-1 justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-300 transition-all"
                                        href="{{ route('client.history', $payment->id) }}">
                                        <h1 class=" text-xs text-gray-700 text-center">Payment Record</h1>
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    @endif
</div>
