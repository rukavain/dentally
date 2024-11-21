@extends('admin.dashboard')
@section('content')
    <style>
        /* Highlight row on hover */
        .inventory-row:hover {
            background-color: #f8f8f8;
            transition: background-color 0.2s ease;
        }
    </style>

    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4 mb-8">
        @include('components.search')
    </div>

    <section class="m-4 p-4 bg-white shadow-lg rounded-md max-lg:mt-14">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <h1 class="font-bold text-3xl mr-4 max-md:mr-0 max-md:text-2xl">Inventory list</h1>
                <span class="text-sm text-gray-500">{{ $items->total() }} items</span>
            </div>
            <div class="flex items-center gap-2">
                <!-- Add Item Button -->
                <form method="GET" action="{{ route('item.add') }}">
                    @csrf
                    <button
                        class="flex justify-center items-center gap-2 rounded-md py-2 px-4 border-2 border-gray-600 hover:shadow-md hover:border-green-700 font-semibold text-gray-800 transition-all">
                        <span class="max-md:text-xs">Add Item</span>
                        <img class="h-8 max-md:h-4" src="{{ asset('assets/images/add.png') }}" alt="">
                    </button>
                </form>
            </div>
        </div>

        <div class="m-4 mb-8">
            <!-- Quick Stats Bar -->
            <div class="flex items-center gap-4 mb-4 p-2 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-md shadow-sm">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    <span class="text-sm font-medium">Available:
                        {{ $items->where('availability', 'available')->count() }}</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-md shadow-sm">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="text-sm font-medium">Out of Stock:
                        {{ $items->where('availability', 'out-of-stock')->count() }}</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-md shadow-sm">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <span class="text-sm font-medium">To Order: {{ $items->where('availability', 'to-order')->count() }}</span>
                </div>
            </div>
            <form method="GET" action="{{ route('inventory') }}"
                class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4 flex-1">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search items..."
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Availability Filter -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600" for="availability">Status:</label>
                        <select id="availability" name="availability"
                            class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">All</option>
                            <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>
                                Available</option>
                            <option value="out-of-stock" {{ request('availability') == 'out-of-stock' ? 'selected' : '' }}>
                                Out of Stock</option>
                            <option value="to-order" {{ request('availability') == 'to-order' ? 'selected' : '' }}>To Order
                            </option>
                        </select>
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-all text-sm">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto mb-2">
                <thead>
                    <tr class="bg-green-200 text-green-700">
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'serial_number', 'direction' => $sortField === 'serial_number' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-center gap-1">
                                Item Serial#
                                @if ($sortField === 'serial_number')
                                    <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                                @endif
                            </a>
                        </th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'item_name', 'direction' => $sortField === 'item_name' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-center gap-1">
                                Item Name
                                @if ($sortField === 'item_name')
                                    <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                                @endif
                            </a>
                        </th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'quantity', 'direction' => $sortField === 'quantity' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-center gap-1">
                                Quantity
                                @if ($sortField === 'quantity')
                                    <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                                @endif
                            </a>
                        </th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'availability', 'direction' => $sortField === 'availability' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-center gap-1">
                                Availability
                                @if ($sortField === 'availability')
                                    <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                                @endif
                            </a>
                        </th>
                        <th class="border px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($items as $item)
                        <tr class="border-b-2 hover:bg-gray-50 inventory-row">
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $item->serial_number }}</td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">{{ $item->item_name }}</td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs quantity-cell">
                                {{ $item->quantity }}</td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2 max-md:text-xs">
                                <span @class([
                                    'rounded-full px-2 py-1',
                                    'bg-green-200 text-green-700' => $item->availability === 'available',
                                    'bg-red-200 text-red-700' => $item->availability === 'out-of-stock',
                                    'bg-blue-200 text-blue-700' => $item->availability === 'to-order',
                                ])>
                                    {{ $item->availability }}
                                </span>
                            </td>
                            <td class="px-4 py-2 max-md:py-1 max-md:px-2">
                                <div class="flex gap-2 justify-center items-center">
                                    <a class="border border-slate-600 flex justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-gray-700 font-semibold hover:bg-gray-100 transition-all"
                                        href="{{ route('item.edit', $item->id) }}" title="Edit item">
                                        <span class="text-xs">Edit</span>
                                    </a>

                                    <button
                                        class="border border-slate-600 flex justify-center items-center rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-gray-700 font-semibold hover:bg-gray-100 transition-all"
                                        onclick="document.getElementById('delete_modal_{{ $item->id }}').showModal()"
                                        title="Delete item">
                                        <span class="text-xs">Delete</span>
                                    </button>

                                    <dialog id="delete_modal_{{ $item->id }}"
                                        class="modal p-4 rounded-md max-w-sm mx-auto">
                                        <div class="modal-box">
                                            <h3 class="text-lg font-bold mb-2">Delete Item</h3>
                                            <p class="text-gray-600 mb-4">Are you sure you want to delete this item?</p>
                                            <div class="flex justify-end gap-2">
                                                <form method="dialog">
                                                    <button
                                                        class="px-4 py-2 border rounded-md hover:bg-gray-100 transition-all text-sm">
                                                        Cancel
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('item.delete', $item->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-all text-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                No items found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </section>

    <script>
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + F for focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.querySelector('input[name="search"]').focus();
            }

            // Esc to clear search and filters
            if (e.key === 'Escape') {
                const searchInput = document.querySelector('input[name="search"]');
                const availabilitySelect = document.querySelector('select[name="availability"]');
                if (searchInput.value || availabilitySelect.value) {
                    searchInput.value = '';
                    availabilitySelect.value = '';
                    document.querySelector('form').submit();
                }
            }
        });

        // Add tooltips to action buttons
        const buttons = document.querySelectorAll('button, a');
        buttons.forEach(button => {
            if (button.textContent.trim()) {
                button.title = button.textContent.trim();
            }
        });

        // Highlight low stock items
        document.querySelectorAll('tr.inventory-row').forEach(row => {
            const quantity = parseInt(row.querySelector('.quantity-cell').textContent);
            const minQuantity = parseInt(row.dataset.minQuantity || 0);

            if (quantity <= minQuantity && quantity > 0) {
                row.classList.add('bg-yellow-50');
            } else if (quantity === 0) {
                row.classList.add('bg-red-50');
            }
        });

        // Enhanced modal functionality
        document.querySelectorAll('[id^="delete_modal_"]').forEach((modal) => {
            if (modal) {
                const modalId = modal.id;
                const button = document.querySelector(
                    `[onclick="document.getElementById('${modalId}').showModal()"]`);
                const closeButton = modal.querySelector('form[method="dialog"] button');

                if (button) {
                    button.addEventListener('click', () => {
                        modal.showModal();
                    });
                }

                if (closeButton) {
                    closeButton.addEventListener('click', () => {
                        modal.close();
                    });
                }

                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.close();
                    }
                });
            }
        });
    </script>
@endsection
