<flux:modal name="role-form" class="max-w-full w-full mx-4 md:max-w-5xl md:mx-auto">
    <div class="space-y-8">
        <div>
            <flux:heading size="lg">{{ $isView ? 'View Role' : ($editId ? 'Edit Role' : 'Create Role') }}</flux:heading>
            <flux:text class="mt-2">You can {{ $isView ? 'view' : ($editId ? 'edit' : 'create') }} role by filling up this form.</flux:text>
        </div>

        <flux:separator variant="subtle" />

        <form wire:submit="handleSubmit" class="space-y-8">

            <!-- ROW 1: Name, Phone, Center -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">


                <flux:input
                    wire:model.live="label"
                    label="Role label"
                    placeholder="Enter Role label"
                    class="{{ $isView ? 'pointer-events-none opacity-50' : '' }}"
                />

                <flux:textarea
                    wire:model.live="desc"
                    label="Role description"
                    placeholder="Enter some description"
                    class="{{ $isView ? 'pointer-events-none opacity-50' : '' }}"
                />

            </div>

            <!-- MODULE + PERMISSIONS SECTION -->
<div class="space-y-6">

    <!-- Section Title -->
    <flux:heading size="md">Assign Permissions</flux:heading>

    <!-- Static Example Data -->
    @php
        $modules = [
            'Users Module' => [
                'view-users',
                'create-users',
                'edit-users',
                'delete-users',
            ],
            'Centers Module' => [
                'view-centers',
                'create-centers',
                'edit-centers',
                'delete-centers',
            ],
            'Finance Module' => [
                'view-reports',
                'export-finance',
                'manage-ledger',
            ],
        ];
    @endphp

    <!-- Loop Modules -->
    @foreach ($modules as $moduleName => $permissions)

        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">

            <!-- MODULE NAME -->
            <div class="mb-3">
                <flux:heading size="sm">{{ $moduleName }}</flux:heading>
            </div>

            <!-- PERMISSIONS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($permissions as $perm)
                    <label class="flex items-center gap-3">

                        <input 
                            type="checkbox" 
                            value="{{ $perm }}"
                            wire:model="selectedPermissions"
                            class="w-4 h-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500"
                            {{ $isView ? 'disabled' : '' }}
                        >

                        <span class="{{ $isView ? 'opacity-50' : '' }}">
                            {{ ucwords(str_replace('-', ' ', $perm)) }}
                        </span>

                    </label>
                @endforeach
            </div>

        </div>

    @endforeach

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