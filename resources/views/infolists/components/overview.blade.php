@php use Illuminate\Support\Str; @endphp

<div class="flex flex-col items-center gap-6 p-6 bg-white shadow-md rounded-xl dark:bg-gray-900 dark:text-white">
    {{-- Avatar --}}
    <div class="relative">
        <img src="{{ $record->header_image ?? 'https://randomuser.me/api/portraits/men/76.jpg' }}"
            class="w-72 h-72 rounded-full border-4 border-gray-300 dark:border-gray-700 shadow-lg ring-4 ring-white dark:ring-gray-800 transition-transform hover:scale-105 object-cover"
            alt="User Avatar">
    </div>

    {{-- Full Name --}}
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 text-center">
        {{ Str::title(
            trim(
                $record->profile?->firstname .
                    ' ' .
                    $record->profile?->middlename .
                    ' ' .
                    $record->profile?->lastname .
                    ' ' .
                    $record->profile?->extension,
            ),
        ) }}
    </h2>

    {{-- Status Badge --}}
    @php
        $statusMap = [
            1 => ['🔍 Unverified', 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white'],
            2 => ['✅ Verified', 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-white'],
            3 => ['❌ Denied', 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-white'],
        ];
        [$label, $classes] = $statusMap[$record->id_status] ?? [
            '❓ Undefined',
            'bg-gray-300 text-gray-900 dark:bg-gray-600 dark:text-white',
        ];
    @endphp

    <div
        class="rounded-xl bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 shadow-sm p-4 inline-block">
        <span class="px-4 py-1 rounded-full text-sm font-medium {{ $classes }}">
            {{ $label }}
        </span>
    </div>

    {{-- Horizontal Fixed-Size Stat Boxes --}}
    <div class="flex w-full gap-4 mt-6 overflow-x-auto">
        @php
            $boxes = [
                ['label' => 'Points', 'value' => $record->points ?? 0],
                ['label' => 'Direct Referrals', 'value' => $record->directReferrals()->count()],
                ['label' => 'Indirect Referrals', 'value' => $record->indirectReferrals()->count()],
                ['label' => 'Events', 'value' => 0],
            ];
        @endphp

        @foreach ($boxes as $box)
            <div
                class="flex-1 min-w-[200px] flex flex-col justify-center items-center bg-gray-100 dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition border border-gray-300 dark:border-gray-600 p-6">
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $box['value'] }}</div>
                <div class="text-base text-gray-600 dark:text-gray-400">{{ $box['label'] }}</div>
            </div>
        @endforeach
    </div>
</div>
