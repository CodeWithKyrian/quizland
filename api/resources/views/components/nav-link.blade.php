@props([
    'title' => '',
    'href' => '',
    'active' => false,
    'hasChildren' => false,
])

<li class="relative px-6 py-3">

    @if($active)
        <span class="absolute inset-y-0 left-0 w-1 bg-blue-800 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
    @endif

    <a href="{{ $href }}"
       class="{{ $active
            ? 'inline-flex items-center w-full text-sm font-semibold text-zinc-800 transition-colors duration-150 hover:text-zinc-800 dark:hover:text-zinc-200 dark:text-zinc-100' :
                'inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-zinc-800 dark:hover:text-zinc-200'
        }}">
        {{ $icon }}
        <span class="ml-4">{{ $title }}</span>
    </a>

</li>
