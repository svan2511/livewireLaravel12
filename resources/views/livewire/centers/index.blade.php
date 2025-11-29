<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Centers') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage your centers .') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Header + Add Button Row -->
    <div class="flex justify-between items-center mb-6">
        <!-- Optional: You can add a small title/description here if you want -->
        <div>
            <!-- You can leave this empty or add extra info -->
        </div>

        <!-- Add New Center Button – Right aligned -->
    
    

<div x-data="{ isLoading: false }">
    <button
        type="button"
        @click.prevent="
            isLoading = true;
            $dispatch('open-form-modal');
            setTimeout(() => isLoading = false, 800)
        "
        :disabled="isLoading"
        class="inline-flex cursor-pointer items-center gap-3 px-5 py-3 text-sm font-medium text-white bg-cyan-600 rounded-lg hover:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-300 disabled:opacity-70 transition-all"
    >
        <span x-show="!isLoading" class="flex items-center gap-3">
            <!-- THIS ONE NEVER BREAKS — SOLID PLUS CIRCLE -->
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.5 10.5a.75.75 0 01-.75.75h-3v3a.75.75 0 01-1.5 0v-3h-3a.75.75 0 010-1.5h3v-3a.75.75 0 011.5 0v3h3a.75.75 0 01.75.75z" clip-rule="evenodd" />
            </svg>
            Add New Center
        </span>

        <span x-show="isLoading" class="flex items-center gap-3">
            <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Wait...
        </span>
    </button>
</div>



    </div>

    <!-- Wrapper for better Flux + Table harmony -->
    <div class="space-y-6">
     
        <livewire:centers-table/>
    </div>

    <livewire:centers.center-form />

    <div
    x-data="{ show: false, message: '', type: 'success' }"
    x-on:toast.window="
        message = $event.detail.message;
        type = $event.detail.type ?? 'success';
        show = true;
        setTimeout(() => show = false, 4000);
    "
    x-show="show"
    x-transition
    class="fixed top-4 right-4 z-50"
>
    <div
        x-bind:class="type === 'success' 
           ? 'bg-emerald-500 border-emerald-600 text-white'
            : 'bg-red-500 border-red-600 text-white'"
        class="px-4 py-4 mt-6 rounded shadow-lg text-sm font-medium"
    >
        <span x-text="message"></span>
    </div>
</div>

</div>

