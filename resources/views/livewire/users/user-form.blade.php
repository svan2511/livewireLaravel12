<flux:modal name="user-form" class="max-w-full w-full mx-4 md:max-w-5xl md:mx-auto">
    <div class="space-y-8">
        <div>
            <flux:heading size="lg">{{ $isView ? 'View User' : ($editId ? 'Edit User' : 'Create User') }}</flux:heading>
            <flux:text class="mt-2">You can {{ $isView ? 'view' : ($editId ? 'edit' : 'create') }} user by filling up this form.</flux:text>
        </div>

        <flux:separator variant="subtle" />

        <form wire:submit="handleSubmit" class="space-y-8">

            <!-- ROW 1: Name, Phone, Center -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">


                <flux:input
                    wire:model.live="name"
                    label="Name label"
                    placeholder="Enter user name"
                    class="{{ $isView ? 'pointer-events-none opacity-50' : '' }}"
                />

                 <flux:input
                    wire:model.live="email"
                    type="email"
                    label="Email label"
                    placeholder="Enter user email"
                    class="{{ $isView ? 'pointer-events-none opacity-50' : '' }}"
                />

                <label class="block text-sm font-semibold text-gray-700 mb-2">
    Assign Roles
</label>
 @error('user_role')
    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
@enderror
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    @foreach($roles as $val => $label)
        <label
            class="flex items-center gap-3 p-4 border rounded-2xl shadow-sm cursor-pointer 
                   hover:bg-gray-100 transition
                   @if(in_array($val, $user_role)) bg-red-50 border-red-400 @endif"
        >
            <input type="checkbox"
                   wire:model.defer="user_role"
                   value="{{ $val }}"
                    @if($isView) disabled @endif
                class="h-5 w-5 text-red-600 rounded focus:ring-red-500 {{ $isView ? 'opacity-50' : '' }}"
                   />

            <span class="font-medium text-gray-700">
                {{ $label }}
            </span>
        </label>
    @endforeach

</div>


            

            </div>

            <flux:separator variant="subtle" class="my-6" />

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3">
                <flux:button
                    type="button"
                    wire:click="$dispatch('close-modal')"
                    variant="ghost"
                    class="cursor-pointer"
                >
                    Cancel
                </flux:button>

                <flux:button
                    type="submit"
                    variant="primary"
                    icon="plus-circle"
                    color="cyan"
                    class="cursor-pointer {{ $isView ? 'pointer-events-none opacity-50' : '' }}"
                
                >
                    {{ $buttonText }}
                </flux:button>
            </div>

        </form>
    </div>
</flux:modal>