@props(['items' => [], 'suggestions' => []])


<!--  Input component start  -->
<div x-data="tagData" class="wrapper relative" @click.outside="showSuggestions=false">
    <!--    Input    -->
    <div
        class="form-input flex relative block w-full rounded-md mt-1 text-sm text-black border-zinc-300 dark:text-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 focus:border-blue-400 focus:outline-none focus:ring-blue-600">

        <!--    Tag icon    -->
        <div
            class="text-zinc-500 focus-within:text-blue-800 dark:focus-within:text-blue-500 flex items-center pointer-events-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"
                    stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M6 6h.008v.008H6V6z" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </div>

        <!--    Value list    -->
        <div class="flex flex-wrap">
            <ul class="space-x-1 space-y-1 flex flex-wrap items-start">
                <template x-for="(item, index) in items">
                    <li class="flex bg-zinc-200 dark:bg-zinc-700 rounded-full py-1 px-3 leading-none">
                        <span x-text="item"></span>
                        <button type="button" class="ml-2" @click="removeItem(index)">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </li>
                </template>
                <label>
                    <input :size="newItem.length + 1"
                           class="ml-2 p-0 bg-transparent border-0 focus:outline-none focus:ring-0"
                           x-ref="input" @keydown="handleKeyDown" @focus="showSuggestions=true"
                           @keydown.esc="newItem=''" type="text" x-model="newItem"
                           placeholder="Add ..."/>
                </label>
            </ul>
        </div>
    </div>

    <!--    Suggestions list    -->
    <ul class="bg-white dark:bg-zinc-800 absolute w-full rounded-md border border-t-0  border-zinc-300 dark:text-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 z-10"
        x-show="showSuggestions">
        <template x-for="suggestion in suggestions">
            <li class="hover:bg-zinc-200 dark:hover:bg-zinc-700">
                <button type="button" @click="addSuggestedItem(suggestion)" class="w-full h-full text-left p-2"
                        x-text="suggestion"></button>
            </li>
        </template>
    </ul>

    <!--    Hidden input    -->
    <input type="hidden" name="{{ $attributes->get('name') }}" x-bind:value="items"/>
</div>


<script>
    const tagData = {
        newItem: "",
        allSuggestions: @json($suggestions),
        items: @json($items),
        showSuggestions: false,
        get suggestions() {
            return this.allSuggestions.filter((v) => {
                const lowerCaseValue = v.toLowerCase();
                const lowerCaseNewValue = this.newItem.toLowerCase();
                // Filter out suggestions that are already present in items
                return !this.items.some((item) => item.toLowerCase() === lowerCaseValue) && lowerCaseValue.startsWith(lowerCaseNewValue);
            });
        },
        addItem() {
            if (!this.newItem) return;

            const newItemLowerCase = this.newItem.toLowerCase();
            // Check if the new item already exists (case-insensitive)
            if (this.items.some((item) => item.toLowerCase() === newItemLowerCase)) {
                this.newItem = '';
                return;
            }
            this.items.push(this.newItem);
            this.newItem = '';
        },
        handleKeyDown(event) {
            switch (event.key) {
                case 'Tab':
                case 'Enter':
                case ',':
                    if (!this.newItem) break;

                    event.preventDefault();
                    this.addItem();
                    break;
                case 'Backspace':
                    this.removeLastItem();
                    break;
                case 'Escape':
                    this.newItem = ''
                    break;
            }
        },
        addSuggestedItem(suggestedItem) {
            this.items.push(suggestedItem)
            $refs.input.focus()
        },
        removeItem(index) {
            this.items.splice(index, 1)
            $refs.input.focus()
        },
        removeLastItem() {
            if (!this.newItem) {
                this.items.pop()
            }
        }
    }
</script>
