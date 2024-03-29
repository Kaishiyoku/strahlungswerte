@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-400 text-sm font-medium leading-5 text-gray-900 dark:text-gray-200 focus:outline-none focus:border-blue-700 dark:focus:border-blue-300 transition'
                : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 focus:outline-none focus:text-gray-700 dark:focus:gray-300 focus:border-gray-300 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
