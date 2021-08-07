@props(['disabled' => false, 'name' => null])

<input {{ $disabled ? 'disabled' : '' }} name="{{ $name }}" {!! $attributes->merge(['class' => classNames('dark:text-gray-400 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500 focus:ring-opacity-50 rounded-md shadow-sm transition', ['border-red-800' => $errors->has($name), 'opacity-50' => $disabled])]) !!}>
