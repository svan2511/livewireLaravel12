<flux:modal name="member-form" class="max-w-full w-full mx-4 md:max-w-5xl md:mx-auto">
    <div class="space-y-8">
        <div>
            <flux:heading size="lg">Create Member</flux:heading>
            <flux:text class="mt-2">You can create member by filling up this form.</flux:text>
        </div>

        <flux:separator variant="subtle" />

        <form wire:submit="handleSubmit" class="space-y-8">

            <!-- ROW 1: Name, Phone, Center -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:input
                    wire:model.live="mem_name"
                    label="Member Name"
                    placeholder="Enter full name"
                />

                <flux:input
                    wire:model.live="mem_phone"
                    label="Phone Number"
                    type="tel"
                    placeholder="e.g. 9876543210"
                />

                <flux:select wire:model.live="center_id" label="Center" placeholder="Select a center" required>
                    @if($centers)
                        @foreach($centers as $id => $name)
                            <flux:select.option :value="$id">{{ $name }}</flux:select.option>
                        @endforeach
                    @else
                        <flux:select.option disabled>No centers available</flux:select.option>
                    @endif
                </flux:select>
            </div>

            <!-- ROW 2: Loan Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:input
                    wire:model.live="disb_amount"
                    label="Disbursed Amount"
                    placeholder="0.00"
                    prefix="₹"
                />

               <flux:select
                wire:model.live="mem_tenor"
                label="Loan Tenor (Months)"
                placeholder="Select Tenor"
            >
                <option value="">Select Tenor</option>

                <option value="15">15 Months</option>
                <option value="18">18 Months</option>
                <option value="22">22 Months</option>
                <option value="24">24 Months</option>
            </flux:select>


                <flux:input
                    wire:model.live="monthly_inst"
                    label="Monthly Installment"
                    type="number"
                    step="0.01"
                    placeholder="0.00"
                    prefix="₹"
                    disabled
                />
            </div>

          <!-- ROW 3: Disbursement Date + Upload Button + Preview (3 in one row) -->
<!-- ROW 3: Disbursement Date + Upload Button (with label) + Preview (3 in one row) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

    <!-- 1. Disbursement Date -->
    <flux:input
        wire:model.live="disb_date"
        label="Disbursement Date"
        type="date"
        required
    />

    <!-- 2. Upload Button with Proper Label -->
    <div class="space-y-2">
        <!-- Label (matches Flux label style) -->
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            
            <span class="text-red-500"></span>
        </label>

        <div class="flex flex-col items-center justify-center space-y-5">
            <!-- Hidden file input -->
            <input 
                type="file" 
                wire:model.live="mem_img" 
                id="mem_img_input" 
                class="hidden" 
                accept="image/*"
            />

            <!-- Styled Upload Button -->
            <label 
                for="mem_img_input" 
                class="cursor-pointer inline-flex items-center mt-3 gap-3 px-6 py-4 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-xl shadow-lg transition-all hover:shadow-xl"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ isset($mem_img) && $mem_img ? 'Change Photo' : 'Upload Photo' }}</span>
            </label>
        </div>
    </div>

    <!-- 3. Live Preview -->
    <div class="flex justify-center items-center">
        @if (isset($mem_img) && $mem_img)
            <div class="relative">
                @if(!$editId || $mem_img instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                    <img 
                        src="{{ $mem_img->temporaryUrl() }}" 
                        alt="Member Preview" 
                        class="w-48 h-48 object-cover rounded-2xl shadow-2xl border-8 border-white dark:border-gray-800"
                    >
                @else
                    <img 
                        src="{{ Storage::url($mem_img) }}" 
                        alt="Member Preview" 
                        class="w-48 h-48 object-cover rounded-2xl shadow-2xl border-8 border-white dark:border-gray-800"
                    >
                @endif
            </div>
        @else
            <div class="w-48 h-48 bg-gray-200 dark:bg-gray-700 border-4 border-dashed border-gray-400 dark:border-gray-600 rounded-2xl flex items-center justify-center">
                <span class="text-gray-500 dark:text-gray-400 text-sm text-center px-4">
                    No photo selected
                </span>
            </div>
        @endif
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
                    class="cursor-pointer"
                >
                    {{ $buttonText }}
                </flux:button>
            </div>

        </form>
    </div>
</flux:modal>