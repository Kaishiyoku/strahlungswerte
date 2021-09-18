@props(['name' => null, 'value' => null, 'autocompleteValues' => [], 'uri', 'minChars' => 1])

<div x-data="selectAutocomplete()" {{ $attributes->merge(['class' => 'relative']) }}>
    <x-input
        type="text"
        class="w-full"
        :name="$name"
        :value="$value"
        x-ref="inputElement"
        @focusin="isFocused = true"
        @click.away="isFocused = false"
        @keyup.debounce.500ms="handleKeyUp($event)"
        {{ $attributes }}
    />

    <div
        class="flex justify-center items-center absolute top-[1px] right-0 w-10 h-10 text-gray-500 border-l border-gray-200"
        @click.stop="isFocused ? isFocused = false : $refs.inputElement.focus()"
    >
        <x-heroicon-s-chevron-up class="w-7 h-7" x-show="isFocused" x-cloak/>
        <x-heroicon-s-chevron-down class="w-7 h-7" x-show="!isFocused"/>
    </div>

    <div
        x-cloak
        x-show="isFocused"
        @click.stop=""
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mr-4 mt-2 rounded-md shadow-lg origin-top-right"
    >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white overflow-y-auto max-h-[200px] min-w-[300px] sm:min-w-[350px] md:max-w-[800px]">
            <div x-show="filteredAutocompleteValues.length === 0" class="w-full px-4 py-2 text-sm leading-5 text-gray-700">
                {{ __('No entries found.') }}
            </div>

            <template x-for="autocompleteValue in filteredAutocompleteValues" :key="autocompleteValue.label">
                <div
                    class="w-full px-4 py-2 text-sm leading-5 text-gray-700 cursor-pointer focus:outline-none transition duration-150 ease-in-out"
                    :class="{ 'text-white bg-indigo-500 hover:bg-indigo-600 focus:bg-indigo-700': inputValue === autocompleteValue.label, 'hover:bg-gray-100 focus:bg-gray-100': inputValue !== autocompleteValue.label }"
                    tabindex="0"
                    @click.stop="selectValue(autocompleteValue)"
                    @keydown.enter.prevent="selectValue(autocompleteValue)"
                >
                    <div x-text="autocompleteValue.label"></div>
                    <div
                        x-text="autocompleteValue.description"
                        class="text-xs "
                        :class="{ 'text-gray-300': inputValue === autocompleteValue.label, 'text-gray-500': inputValue === autocompleteValue.label }"
                    >
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script type="text/javascript">
    const autocompleteValues = @json($autocompleteValues);
    const minChars = @json($minChars);

    function selectAutocomplete() {
        return {
            autocompleteValues,
            isFocused: false,
            inputValue: @json($value),
            filteredAutocompleteValues: [],
            init() {
                this.$refs.inputElement.value = this.inputValue;
            },
            selectValue(autocompleteValue) {
                this.isFocused = false;
                this.inputValue = autocompleteValue.label;
                this.$refs.inputElement.value = autocompleteValue.label;
            },
            handleKeyUp(event) {
                const newInputValue = event.target.value;
                const hasInputValueChanged = this.inputValue !== newInputValue;

                this.inputValue = newInputValue;

                if (!newInputValue || !hasInputValueChanged || newInputValue.length < minChars) {
                    return [];
                }

                this.filteredAutocompleteValues = [];

                const trimmedInputValue = this.inputValue.trim().toLowerCase();

                axios.get(`{{ $uri }}${trimmedInputValue}`).then(({data}) => {
                    this.filteredAutocompleteValues = data;
                });
            },
        };
    }
</script>
