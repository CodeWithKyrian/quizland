@props(['title', 'stats', 'color' => 'blue'])

@php
    $colors = [
        'red' => 'bg-red-100 text-red-500 dark:bg-red-500 dark:text-red-100',
        'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
        'green' => 'bg-green-100 text-green-600 dark:bg-green-600 dark:text-green-100',
        'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500 dark:text-yellow-100',
        'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
        'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
        'pink' => 'bg-pink-100 text-pink-800 dark:bg-pink-800 dark:text-pink-100',
        'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
        'zinc' => 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-100',
    ];
@endphp

<div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-zinc-800">
    <div
        class="p-3 mr-4 rounded-full {{ $colors[$color] }}">
        {{$icon}}
    </div>
    <div>
        <p class="mb-2 font-josefin uppercase text-sm font-medium text-zinc-500 dark:text-zinc-400">
            {{ $title }}
        </p>
        <p class="text-lg font-semibold text-zinc-700 dark:text-zinc-200">{{ $stats }}</p>
    </div>
</div>
