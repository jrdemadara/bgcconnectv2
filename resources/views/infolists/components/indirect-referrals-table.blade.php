@if ($userId)
    @livewire('indirect-refferal-table', ['userId' => $userId])
@else
    <p class="text-gray-500">No user ID available.</p>
@endif
