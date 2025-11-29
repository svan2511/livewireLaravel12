<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage your users and user settings') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

      <!-- Header + Add Button Row -->
    <div class="flex justify-between items-center mb-6">
        <!-- Optional: You can add a small title/description here if you want -->
        <div>
            <!-- You can leave this empty or add extra info -->
        </div>

        <!-- Add New Center Button – Right aligned -->
       <flux:modal.trigger name="edit-profile">
    <flux:button icon="plus-circle" variant="primary" color="cyan" class="cursor-pointer">Add New User</flux:button>
</flux:modal.trigger>
    </div>

    <!-- Wrapper for better Flux + Table harmony -->
    <div class="space-y-6">
        <livewire:users-table />
    </div>

  
</div>