@php
    $firstInitial = Auth::user()->firstname ? strtoupper(substr(Auth::user()->firstname, 0, 1)) : '';
    $lastInitial = Auth::user()->lastname ? strtoupper(substr(Auth::user()->lastname, 0, 1)) : '';
@endphp

<header class="bg-white py-4 px-6 flex justify-between items-center border-b border-gray-300 shadow-lg relative">
    <div class="font-bold text-2xl text-center text-[#00446b]">Nexfleet Dynamics</div>
    <div class="flex items-center space-x-4 relative">
        <!-- Notification Icon -->
        <div class="relative">
    <button type="button" onclick="toggleNotificationDropdown()" class="p-2 rounded-full hover:bg-gray-200 relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.17V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.17c0 .54-.216 1.058-.595 1.425L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
        </svg>
        <span id="notification-badge" class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-500" style="display: none;"></span>
    </button>

<!-- Notification Dropdown -->

<div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-300 rounded-md shadow-lg z-20">
    <!-- Header -->
    <div class="p-4 text-gray-800 font-semibold border-b flex justify-between items-center">
        <span>Notifications</span>
    </div>

    <!-- Notification List -->
    <div id="notificationList" class="max-h-48 overflow-y-auto">
        <p class="text-gray-600 p-3 text-sm">No new notifications</p>
    </div>

    <!-- Action Buttons -->
    <div class="p-3 border-t flex justify-between items-center">
        <button onclick="markAllAsRead()" class="text-blue-500 text-sm hover:underline">Mark All as Read</button>
        <button onclick="clearAllNotifications()" class="text-red-500 text-sm hover:underline">Clear All</button>
    </div>
</div>



</div>
<div class="relative">
            <div class="dropdown">
                <button class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                
<div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2">
    <span class="text-indigo-600 font-semibold">
        {{ $firstInitial }}{{ $lastInitial }}
    </span>
</div>
<span class="hidden md:inline">
    {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
</span>
<i class="fas fa-chevron-down ml-1 text-xs"></i>

                </button>
                
                <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <div class="border-t border-gray-200"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let notificationList = document.getElementById('notificationList');
    let notificationBadge = document.getElementById('notification-badge');

    function fetchNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    notificationBadge.style.display = "block";
                    notificationBadge.innerText = data.length;
                    notificationList.innerHTML = data.map(n =>
                        `<p class="text-gray-600 p-3 text-sm border-b">
                            <a href="/admin/recruitment/hired" onclick="markNotificationsAsRead(event)">${n.message}</a>
                        </p>`
                    ).join('');
                } else {
                    notificationBadge.style.display = "none";
                    notificationList.innerHTML = `<p class="text-gray-600 p-3 text-sm">No new notifications</p>`;
                }
            }).catch(error => console.error('Error fetching notifications:', error));
    }

    function markAllAsRead() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message); // Debugging
        notificationBadge.style.display = "none";
        fetchNotifications();
    })
    .catch(error => console.error('Error marking all as read:', error));
}
function clearAllNotifications() {
    fetch('/notifications/clear-all', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message); // Debugging
        notificationBadge.style.display = "none";
        notificationList.innerHTML = `<p class="text-gray-600 p-3 text-sm">No new notifications</p>`;
    })
    .catch(error => console.error('Error clearing notifications:', error));
}

    setInterval(fetchNotifications, 30000);
    fetchNotifications();

    // Attach functions to buttons
    window.markAllAsRead = markAllAsRead;
    window.clearAllNotifications = clearAllNotifications;
});

// Toggle notification dropdown
function toggleNotificationDropdown() {
    document.getElementById('notificationDropdown').classList.toggle('hidden');
}

// Toggle settings dropdown
function toggleSettingsDropdown() {
    document.getElementById('settingsDropdown').classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
        // Toggle dropdown menu
        const dropdownBtn = document.querySelector('.dropdown button');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        
        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdownMenu.classList.add('hidden');
            });
        }
    });

</script>




<script>
     function toggleSettingsDropdown() {
        const dropdown = document.getElementById('settingsDropdown');
        dropdown.classList.toggle('hidden');
    }
</script>