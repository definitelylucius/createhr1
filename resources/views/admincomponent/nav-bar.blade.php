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
        <!-- Profile Icon -->
        <button type="button" class="p-2 rounded-full hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>

        <!-- Settings Icon with Dropdown -->
        <div class="relative">
            <button type="button" onclick="toggleSettingsDropdown()" class="p-2 rounded-full hover:bg-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </button>

            <!-- Settings Dropdown -->
            <div id="settingsDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-20">
                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                        {{ __('Log Out') }}
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

</script>




<script>
     function toggleSettingsDropdown() {
        const dropdown = document.getElementById('settingsDropdown');
        dropdown.classList.toggle('hidden');
    }
</script>