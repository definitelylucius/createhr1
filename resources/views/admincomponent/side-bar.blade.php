<aside class="w-64 bg-white border-r border-gray-200 p-4 shadow-lg h-screen flex flex-col">

    <div class="flex flex-col">
        <!-- Home -->
        <div class="flex flex-col relative my-1 cursor-pointer">
            <a href="/admin/dashboard" class="flex items-center p-2 w-full rounded-lg transition-colors duration-200 {{ request()->is('admin/dashboard') ? 'bg-gray-200 text-gray-800' : 'hover:bg-gray-100' }}

                    <i class="fi fi-sr-home text-xl"></i>
                    <span class="text-lg">Home</span>
                </span>
            </a>
        </div>

        <!-- Job Requisitions -->
        <div class="relative my-1 cursor-pointer">
            <div class="flex justify-between items-center p-2 w-full rounded-lg transition-colors duration-200 hover:bg-gray-100" onclick="toggleDropdown('jobRequisitionDropdown')">
                <span class="flex items-center gap-2">
                    <i class="fi fi-sr-briefcase text-xl"></i>
                    <span class="text-lg">Job Requisitions</span>
                </span>
            </div>
            <div id="jobRequisitionDropdown" class="hidden flex-col ml-2 mt-1 space-y-1 transition-all duration-200">
                <a href="{{ route('admin.jobs.create') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Create New Requisition</a>
                <a href="{{ route('admin.jobs.manage') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">View All Requisitions</a>
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Requisition Approval</a>
            </div>
        </div>

        <!-- Onboarding -->
        <div class="relative my-1 cursor-pointer">
            <div class="flex justify-between items-center p-2 w-full rounded-lg transition-colors duration-200 hover:bg-gray-100" onclick="toggleDropdown('onboardingDropdown')">
                <span class="flex items-center gap-2">
                    <i class="fi fi-sr-user-add text-xl"></i>
                    <span class="text-lg">Onboarding</span>
                </span>
            </div>
            <div id="onboardingDropdown" class="hidden flex-col ml-2 mt-1 space-y-1 transition-all duration-200">
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">New Hire Profiles</a>
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Onboarding Progress</a>
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Task Management</a>
            </div>
        </div>

        <!-- Recruitment Overview -->
        <div class="relative my-1 cursor-pointer">
            <div class="flex justify-between items-center p-2 w-full rounded-lg transition-colors duration-200 hover:bg-gray-100" onclick="toggleDropdown('recruitmentDropdown')">
                <span class="flex items-center gap-2">
                    <i class="fi fi-sr-users-alt text-xl"></i>
                    <span class="text-lg">Recruitment Overview</span>
                </span>
            </div>
            <div id="recruitmentDropdown" class="hidden flex-col ml-2 mt-1 space-y-1 transition-all duration-200">
                <a href="{{ route('admin.applicants.review') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Candidate</a>
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Interview Schedule</a>
                <a href="{{ route('admin.applicants.hired') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Offer</a>
            </div>
        </div>

        <!-- Applicant Management -->
        <div class="relative my-1 cursor-pointer">
            <div class="flex justify-between items-center p-2 w-full rounded-lg transition-colors duration-200 hover:bg-gray-100" onclick="toggleDropdown('applicantDropdown')">
                <span class="flex items-center gap-2">
                    <i class="fi fi-sr-file-user text-xl"></i>
                    <span class="text-lg">Applicant Management</span>
                </span>
            </div>
            <div id="applicantDropdown" class="hidden flex-col ml-2 mt-1 space-y-1 transition-all duration-200">
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">All Applications</a>
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Archived Applications</a>
            </div>
        </div>

        <!-- Reports -->
        <div class="relative my-1 cursor-pointer">
            <div class="flex justify-between items-center p-2 w-full rounded-lg transition-colors duration-200 hover:bg-gray-100" onclick="toggleDropdown('reportsDropdown')">
                <span class="flex items-center gap-2">
                    <i class="fi fi-sr-chart-pie text-xl"></i>
                    <span class="text-lg">Reports</span>
                </span>
            </div>
        </div>
    </div>
</aside>

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden');
    }
</script>


