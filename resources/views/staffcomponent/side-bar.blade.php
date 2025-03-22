<div class="h-screen flex">
    <aside class="w-64 md:w-72 lg:w-80 bg-white border-r border-gray-200 p-4 shadow-lg flex flex-col overflow-y-auto">

    <!-- Dashboard -->
    <div class="flex relative my-1 cursor-pointer">
        <a href="{{ route('staff.dashboard') }}" class="flex relative my-1 w-full">
            <span class="w-4 rounded-xl absolute -left-2 h-full bg-white"></span>
            <p class="ml-8 flex items-center w-full p-1 rounded-xl font-semibold text-gray-700 hover:bg-[#003355] hover:text-white transition">
                <i class="fi fi-rr-home text-2xl"></i>
                <span class="px-2">Dashboard</span>
            </p>
        </a>
    </div>

    <!-- My Tasks -->
    <div class="relative my-1 cursor-pointer">
        <div onclick="toggleDropdown('myTasksDropdown')" class="flex relative my-1 w-full cursor-pointer">
            <span class="w-4 rounded-xl absolute -left-2 h-full bg-white"></span>
            <p class="ml-8 flex items-center w-full p-1 rounded-xl font-semibold text-gray-700 hover:bg-[#003355] hover:text-white transition">
                <i class="fi fi-rr-list-check text-2xl"></i>
                <span class="px-2">My Tasks</span>
            </p>
        </div>
        <div id="myTasksDropdown" class="hidden flex-col ml-8 mt-1 space-y-1">
            <a href="{{ route('staff.tasks.assigned') }}" class="block p-2 rounded-lg hover:bg-[#003355] hover:text-white transition">Assigned Tasks</a>
            <a href="{{ route('staff.tasks.pending') }}" class="block p-2 rounded-lg hover:bg-[#003355] hover:text-white transition">Pending Tasks</a>
            <a href="{{ route('staff.tasks.completed') }}" class="block p-2 rounded-lg hover:bg-[#003355] hover:text-white transition">Completed Tasks</a>
        </div>
    </div>

    <!-- Onboarding Assistance -->
    <div class="relative my-1 cursor-pointer">
        <div onclick="toggleDropdown('onboardingDropdown')" class="flex relative my-1 w-full cursor-pointer">
            <span class="w-4 rounded-xl absolute -left-2 h-full bg-white"></span>
            <p class="ml-8 flex items-center w-full p-1 rounded-xl font-semibold text-gray-700 hover:bg-[#003355] hover:text-white transition">
                <i class="fi fi-rr-briefcase text-2xl"></i>
                <span class="px-2">Onboarding Assistance</span>
            </p>
        </div>
        <div id="onboardingDropdown" class="hidden flex-col ml-8 mt-1 space-y-1">
        <a href="{{ route('new.hire.orientation') }}" 
   class="block p-2 rounded-lg hover:bg-[#003355] hover:text-white transition-colors duration-300 ease-in-out">
    New Hire Orientation
</a>
    <a href="{{ route('staff.onboarding.documents') }}" 
       class="block p-2 rounded-lg text-gray-800 hover:bg-[#003355] hover:text-white transition-colors duration-300 ease-in-out">
        Document Collection
    </a>
    <a href="{{ route('staff.onboarding.training') }}" 
       class="block p-2 rounded-lg text-gray-800 hover:bg-[#003355] hover:text-white transition-colors duration-300 ease-in-out">
        Training & Courses
    </a>
</div>

    </div>

    <!-- Recruitment -->
    <div class="relative my-1 cursor-pointer">
        <div onclick="toggleDropdown('recruitmentDropdown')" class="flex relative my-1 w-full cursor-pointer">
            <span class="w-4 rounded-xl absolute -left-2 h-full bg-white"></span>
            <p class="ml-8 flex items-center w-full p-1 rounded-xl font-semibold text-gray-700 hover:bg-[#003355] hover:text-white transition">
                <i class="fi fi-rr-users text-2xl"></i>
                <span class="px-2">Recruitment</span>
            </p>
        </div>
        <div id="recruitmentDropdown" class="hidden flex-col ml-8 mt-1 space-y-1">
            <a href="{{ route('staff.recruitment.documents') }}" class="block p-2 rounded-lg hover:bg-[#003355] hover:text-white transition">Candidate</a>
            <a href="{{ route('staff.recruitment.interview') }}" class="block p-2 rounded-lg hover:bg-[#003355] hover:text-white transition">Interview</a>
            <a href="{{ route('staff.recruitment.feedback') }}" class="block p-2 rounded-lg hover:bg-[#003355] hover:text-white transition">Feedback Submission</a>
        </div>
    </div>

 

</aside>
</div>

<script>
  function toggleDropdown(id) {
    let dropdown = document.getElementById(id);
    dropdown.classList.toggle("hidden");
  }
</script>







<!-- Load Flat Icons -->
<!-- Flaticon / Fontisto Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.min.css">
