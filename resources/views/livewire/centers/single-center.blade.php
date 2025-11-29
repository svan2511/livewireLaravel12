<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ $center->center_name }} Center .</flux:heading>
        <flux:subheading size="lg" class="mb-6">Manage your members of {{ $center->center_name }} center here .</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Header + Add Button Row -->
    <div class="flex justify-between items-center mb-6">
        <!-- Optional: You can add a small title/description here if you want -->
        <div>
            <!-- You can leave this empty or add extra info -->
        </div>

        <!-- Add New Center Button – Right aligned -->
    
    

  <flux:button
    variant="primary" 
    color="cyan" 
    wire:click=""
    class="cursor-pointer">Back
  </flux:button>



    </div>

    <!-- Wrapper for better Flux + Table harmony -->
    <div class="space-y-6">
     
       <livewire:members-table :centerId="$center->id"/>
    </div>

    

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

