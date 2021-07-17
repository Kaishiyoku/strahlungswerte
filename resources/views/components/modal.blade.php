<div x-data="modal()" x-on:keydown.escape="closeDialog()">
    <button x-ref="modal_button" @click="openDialog()" {{ $attributes->merge(['class' => 'btn btn-primary']) }}>
        {{ $title }}
    </button>

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
            class="flex flex-col rounded-lg shadow-lg bg-white dark:bg-gray-900 w-3/5 h-3/5 z-10"
        >
            <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-800">
                <div class="text-xl" id="{{ $name }}_label" x-ref="{{ $name }}_label">{{ $title }}</div>
            </div>
            <div class="p-4 overflow-y-scroll">
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
