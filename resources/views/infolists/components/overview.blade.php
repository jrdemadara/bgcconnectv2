<div {{ $attributes }}>
    <!-- Profile Info -->
    <div class="flex flex-col items-center text-center">
        <img src="{{ asset('path-to-image.jpg') }}" alt="Profile Photo" class="w-24 h-24 rounded-xl object-cover">
        <h2 class="mt-4 text-lg font-semibold">Johnny Roger Demadara</h2>
        <span class="mt-1 text-sm text-green-600 bg-green-100 px-2 py-0.5 rounded-full flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" /></svg>
            Verified
        </span>
    </div>

    <!-- Profile Completion -->
    <div class="space-y-1">
        <div class="text-sm font-medium text-gray-700">Profile Completion</div>
        <div class="w-full bg-gray-200 h-2 rounded">
            <div class="bg-green-500 h-2 rounded" style="width: 25%;"></div>
        </div>
        <div class="text-right text-sm text-gray-600">25%</div>
    </div>

    <!-- Call to Action -->
    <div class="bg-orange-100 border border-orange-300 text-orange-700 text-sm px-4 py-3 rounded flex justify-between items-center">
        <span>🔔 Complete your profile to reach Level 2 and earn a 20-point bonus!</span>
        <a href="#" class="text-blue-600 hover:underline">Complete my profile</a>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Points -->
        <div class="bg-green-50 rounded-lg p-4 flex flex-col justify-between shadow-sm">
            <div class="flex items-center justify-between">
                <div class="text-green-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z"></path>
                        <path d="M12 2C6.48 2 2 6.48 2 12a10 10 0 0017.514 6.62L22 22"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">7490</div>
            </div>
            <div class="mt-2 text-sm text-green-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z"/></svg>
                +0% than last month
            </div>
        </div>

        <!-- Referrals -->
        <div class="bg-blue-50 rounded-lg p-4 flex flex-col justify-between shadow-sm">
            <div class="flex items-center justify-between">
                <div class="text-blue-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a4 4 0 00-5-4"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M3 21v-2a4 4 0 014-4h4"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">10</div>
            </div>
            <div class="mt-2 text-sm text-green-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z"/></svg>
                +0% than last month
            </div>
        </div>

        <!-- Downlines -->
        <div class="bg-indigo-50 rounded-lg p-4 flex flex-col justify-between shadow-sm">
            <div class="flex items-center justify-between">
                <div class="text-indigo-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M8 17l4 4 4-4m0-5l-4-4-4 4"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">21375</div>
            </div>
            <div class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M15 10l-5 5-5-5h10z"/></svg>
                -100% than last month
            </div>
        </div>

        <!-- Activity -->
        <div class="bg-red-50 rounded-lg p-4 flex flex-col justify-between shadow-sm">
            <div class="flex items-center justify-between">
                <div class="text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">1</div>
            </div>
            <div class="mt-2 text-sm text-green-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z"/></svg>
                0% than last month
            </div>
        </div>
    </div>

    <!-- Infolist children -->
    {{ $getChildComponentContainer() }}
</div>
