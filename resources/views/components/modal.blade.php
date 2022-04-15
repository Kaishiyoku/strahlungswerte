<div x-data="modal()" x-on:keydown.escape="closeDialog()">
    <x-secondary-button x-ref="modal_button" @click="openDialog()" {{ $attributes }}>
        {{ $title }}
    </x-secondary-button>

    <div
        role="dialog"
        aria-labelledby="{{ $name }}_label"
        aria-modal="true"
        tabindex="0"
        x-show="open"
        @click="closeDialog(() => $refs.modal_button.focus())"
        @click.away="closeDialog()"
        x-cloak
        class="fixed top-0 left-0 w-full h-screen flex justify-center items-center z-10"
    >
        <div
            aria-hidden="true"
            class="absolute top-0 left-0 w-full h-screen bg-black transition duration-300"
            :class="{ 'opacity-60': open, 'opacity-0': !open }"
            x-show="open"
            x-transition:leave="delay-150"
        ></div>
        <div
            data-modal-document
            @click.stop=""
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="flex flex-col sm:rounded-lg shadow-lg bg-white dark:bg-gray-900 w-full sm:w-2/4 h-full sm:h-3/5 z-10"
        >
            <div class="flex justify-between items-center px-4 py-4 border-b border-gray-200 dark:border-gray-800">
                <div class="text-xl" id="{{ $name }}_label" x-ref="{{ $name }}_label">{{ $title }}</div>
                <button class="p-2 text-gray-600 hover:text-gray-800 dark:hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:text-gray-900 dark:focus:text-gray-400 focus:bg-gray-200 dark:focus:bg-gray-700 rounded-full transition" @click="closeDialog()">
                    <x-heroicon-s-x class="w-7 h-7"/>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function modal() {
        return {
            open: false,
            openDialog() {
                this.open = true;

                document.querySelector('body').classList.add('overflow-hidden');
            },
            closeDialog(callbackFn = null) {
                this.open = false;

                document.querySelector('body').classList.remove('overflow-hidden');

                if (callbackFn) {
                    callbackFn();
                }
            }
        };
    }
</script>
