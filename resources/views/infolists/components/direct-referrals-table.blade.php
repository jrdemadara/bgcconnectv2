@if ($userId)
    
        @livewire('direct-referrals-table', ['userId' => $userId], key($userId))
    
@else
    <p class="text-gray-500 italic">No user ID available.</p>
@endif