@props(['status', 'isQueue' => false])

@php
    $steps = [
        ['key' => 'queued', 'label' => 'Queued', 'icon' => 'clock'],
        ['key' => 'scouting', 'label' => 'Scouting', 'icon' => 'user-search'],
        ['key' => 'ready', 'label' => 'Ready', 'icon' => 'check-circle'],
        ['key' => 'running', 'label' => 'Running', 'icon' => 'play'],
        ['key' => 'completed', 'label' => 'Done', 'icon' => 'flag-checkered'],
    ];

    if (!$isQueue) {
        $steps[0]['label'] = 'Requested';
        $steps[1]['label'] = 'Recruiting';
    }

    $currentIdx = 0;
    foreach ($steps as $idx => $step) {
        if ($status === $step['key'] || ($status === 'open' && $step['key'] === 'scouting') || ($status === 'assigned' && $step['key'] === 'ready')) {
            $currentIdx = $idx;
        }
    }
    
    // Special handling for cancelled
    if ($status === 'cancelled') {
        $steps[$currentIdx]['label'] = 'Abort';
        $steps[$currentIdx]['key'] = 'cancelled';
    }
@endphp

<div class="flex items-center w-full max-w-xs space-x-1">
    @foreach($steps as $idx => $step)
        <div class="flex-grow h-1 rounded-full {{ $idx <= $currentIdx ? ($status === 'cancelled' ? 'bg-red-500' : 'bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]') : 'bg-gray-800' }}"></div>
    @endforeach
</div>
<div class="flex justify-between w-full max-w-xs mt-1 px-0.5">
    <span class="text-[7px] font-black uppercase {{ $status === 'cancelled' ? 'text-red-500' : 'text-indigo-400' }} tracking-tighter">{{ $steps[$currentIdx]['label'] }}</span>
    <span class="text-[7px] font-bold text-gray-600 uppercase tracking-tighter">{{ floor(($currentIdx / (count($steps) - 1)) * 100) }}%</span>
</div>
