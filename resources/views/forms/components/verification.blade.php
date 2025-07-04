@php
    $name = $name ?? 'No Name';

@endphp

<div x-data="{ showModal: false, modalImage: '' }" @keydown.escape.window="showModal = false">
    <!-- Image Cards -->
    <div class="flex gap-6 px-6 py-8 w-full">
        <!-- Avatar -->
        <div
            class="flex flex-col items-center justify-center bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-lg 
                    flex-1 h-[30rem]">
            <img @click="modalImage = '{{ asset('images/avatar.jpg') }}'; showModal = true"
                src="{{ asset('images/avatar.jpg') }}" alt="Avatar"
                class="w-40 h-40 rounded-full border-4 border-white dark:border-gray-800 
                       ring-4 ring-indigo-400 shadow-md object-cover mb-4 cursor-pointer hover:scale-105 transition" />
        </div>

        <!-- Front ID -->
        <div
            class="flex flex-col items-center justify-between bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-lg 
                    flex-1 h-[30rem]">
            <img @click="modalImage = '{{ asset('images/frontid.png') }}'; showModal = true"
                src="{{ asset('images/frontid.png') }}" alt="Front ID"
                class="object-cover w-full h-60 rounded-md shadow cursor-pointer hover:scale-105 transition" />
            <p class="text-base text-gray-600 dark:text-gray-300 mt-4">Front ID</p>
        </div>

        <!-- Back ID -->
        <div
            class="flex flex-col items-center justify-between bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-lg 
                    flex-1 h-[30rem]">
            <img @click="modalImage = '{{ asset('images/idback.png') }}'; showModal = true"
                src="{{ asset('images/idback.png') }}" alt="Back ID"
                class="object-cover w-full h-60 rounded-md shadow cursor-pointer hover:scale-105 transition" />
            <p class="text-base text-gray-600 dark:text-gray-300 mt-4">Back ID</p>
        </div>

        <!-- Signature -->
        <div
            class="flex flex-col items-center justify-between bg-white dark:bg-gray-900 
                    border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-lg 
                    flex-1 h-[30rem]">
            <img @click="modalImage = '{{ asset('images/signature.jpg') }}'; showModal = true"
                src="{{ asset('images/signature.jpg') }}" alt="Signature"
                class="object-contain w-full h-24 rounded-md shadow bg-white dark:bg-gray-800 cursor-pointer hover:scale-105 transition" />
            <p class="text-base text-gray-600 dark:text-gray-300 mt-4">Signature</p>
        </div>
    </div>

    <!-- Member Info Section -->
    <div class="text-center px-6 pb-10">
        <!-- Name and Role -->
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $name }}</h2>
        <p class="text-lg mt-1">Member Profile</p>

        <!-- Detail Info Box -->
        <div
            class="mt-6 inline-block bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-6 py-4 text-left shadow-sm">
            <dl class="space-y-2 text-base text-gray-700 dark:text-gray-200">
                @if (!empty($birthdate))
                    <div class="flex justify-between">
                        <dt class="font-semibold">Birthdate:</dt>
                        <dd>{{ \Carbon\Carbon::parse($birthdate)->format('F d, Y') }}</dd>
                    </div>
                @endif

                @if (!empty($age))
                    <div class="flex justify-between">
                        <dt class="font-semibold">Age:</dt>
                        <dd>{{ $age }}</dd>
                    </div>
                @endif

                @if (!empty($gender))
                    <div class="flex justify-between">
                        <dt class="font-semibold">Gender:</dt>
                        <dd>{{ ucfirst($gender) }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-transition.opacity
        class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center backdrop-blur-sm"
        @click.away="showModal = false">
        <div class="relative">
            <img :src="modalImage"
                class="w-full max-w-[90vw] max-h-[90vh] md:w-[500px] md:h-[500px] object-contain rounded-xl shadow-2xl border-4 border-white dark:border-gray-700" />
            <button @click="showModal = false"
                class="absolute -top-4 -right-4 text-white text-4xl font-bold hover:text-red-500"
                title="Close">&times;</button>
        </div>
    </div>
</div>
