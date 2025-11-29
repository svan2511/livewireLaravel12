<flux:modal name="permission-form" class="max-w-full w-full mx-4 md:max-w-5xl md:mx-auto">
    <div class="space-y-8">
        <div>
            <flux:heading size="lg">{{ $isView ? 'View Permission' : ($editId ? 'Edit Permission' : 'Create Permission') }}</flux:heading>
            <flux:text class="mt-2">You can {{ $isView ? 'view' : ($editId ? 'edit' : 'create') }} permission by filling up this form.</flux:text>
        </div>

        <flux:separator variant="subtle" />

        <form wire:submit="handleSubmit" class="space-y-8">

            <!-- ROW 1: Name, Phone, Center -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">

            <flux:select wire:model.live="module" label="Module" placeholder="Select a module"  class="{{ $isView ? 'pointer-events-none opacity-50' : '' }}" >
            
                     <!-- <flux:select.option disabled>No centers available</flux:select.option> -->
                      @foreach($modules as $slug => $name)
                      <flux:select.option value="{{$slug}}">{{$name}}</flux:select.option>
                    @endforeach
                </flux:select>


                <flux:input
                    wire:model.live="label"
                    label="Permission label"
                    placeholder="Enter permission label"
                    class="{{ $isView ? 'pointer-events-none opacity-50' : '' }}"
                />

                <flux:textarea
                    wire:model.live="desc"
                    label="Permission description"
                    placeholder="Enter some description"
                    class="{{ $isView ? 'pointer-events-none opacity-50' : '' }}"
                />

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