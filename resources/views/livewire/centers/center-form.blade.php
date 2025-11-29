<flux:modal name="center-form" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Create center</flux:heading>
            <flux:text class="mt-2">You can create center by filling up this form.</flux:text>
        </div>

        <flux:separator variant="subtle" />

  <form wire:submit="handleSubmit" class="space-y-6">

    <flux:input
        wire:model.live="centerName"
        label="{{ __('Center Name') }}"
        placeholder="{{ __('Enter center name') }}"
    />

    <flux:textarea
        wire:model.live="centerAddress"
        label="{{ __('Center Address') }}"
        placeholder="{{ __('Enter center address') }}"
        :rows="3"
    />

    <flux:separator variant="subtle" class="my-6" />

    <div class="flex items-center justify-end gap-3">
        <flux:button
            type="button"
            wire:click="$dispatch('close-modal')"
            variant="ghost"
            class="cursor-pointer"
        >
            {{ __('Cancel') }}
        </flux:button>

        <flux:button
            type="submit"
            variant="primary"
            icon="plus-circle"
            color="cyan"
            class="cursor-pointer"
        >
            {{ $buttonText }}
        </flux:button>
    </div>

</form>

    </div>
</flux:modal>