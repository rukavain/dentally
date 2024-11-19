<!-- Notification Bell -->
<div class="relative inline-block" 
     x-data="{ 
        open: false,
        notificationCount: {{ Auth::user()->unreadNotifications->count() }},
        removeNotification() {
            this.notificationCount--;
            if (this.notificationCount <= 0) {
                this.notificationCount = 0;
                this.open = false;
            }
        }
     }"
     @click.away="open = false"
     @keydown.escape.window="open = false">
    <button type="button" 
            @click="open = !open"
            class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <template x-if="notificationCount > 0">
            <span class="notification-count absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full" x-text="notificationCount"></span>
        </template>
    </button>

    <!-- Notifications Dropdown -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-[100]"
         style="display: none;">
        <div class="py-2">
            @forelse(Auth::user()->unreadNotifications()->orderBy('created_at', 'desc')->get() as $notification)
                @php
                    $data = $notification->data;
                    $appointmentId = $data['appointment_id'] ?? null;
                    $message = $data['message'] ?? 'New notification';
                    $date = $data['appointment_date'] ?? '';
                    $time = $data['preferred_time'] ?? '';
                    $procedure = $data['procedure_name'] ?? '';
                @endphp
                <div class="notification-item" id="notification-{{ $notification->id }}">
                    @php
                        $appointmentRoute = '#';
                        if ($appointmentId) {
                            if (Auth::user()->role === 'dentist') {
                                $appointmentRoute = route('appointments.show', $appointmentId);
                            } elseif (in_array(Auth::user()->role, ['admin', 'staff'])) {
                                $appointmentRoute = route('show.appointment', ['appointment' => $appointmentId]);
                            }
                        }
                    @endphp
                    <a href="{{ $appointmentRoute }}"
                       class="flex items-center px-4 py-3 border-b hover:bg-gray-100 transition-colors duration-200"
                       @click.prevent="handleNotificationClick($event, '{{ $notification->id }}', '{{ $appointmentRoute }}', '{{ csrf_token() }}')">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $message }}</p>
                            @if($date && $time)
                                <p class="text-xs text-gray-500">Date: {{ $date }} at {{ $time }}</p>
                            @endif
                            @if($procedure)
                                <p class="text-xs text-gray-500">Procedure: {{ $procedure }}</p>
                            @endif
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="px-4 py-3 text-sm text-gray-500">
                    No new notifications
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function handleNotificationClick(event, notificationId, redirectUrl, csrfToken) {
    event.preventDefault();
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        if (redirectUrl && redirectUrl !== '#') {
            window.location.href = redirectUrl;
        }
        return;
    }

    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove the notification from the UI
            const notificationElement = document.getElementById(`notification-${notificationId}`);
            if (notificationElement) {
                notificationElement.remove();
            }
            
            // Update the count using Alpine.js
            const component = document.querySelector('[x-data]').__x.$data;
            if (component) {
                component.removeNotification();
            }

            // Redirect after successfully marking as read
            if (redirectUrl && redirectUrl !== '#') {
                window.location.href = redirectUrl;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (redirectUrl && redirectUrl !== '#') {
            window.location.href = redirectUrl;
        }
    });
}
</script>