<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-5xl mx-auto">

        <!-- Back + Title -->
        <div class="mb-8">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2 text-cyan-700 dark:text-cyan-500 hover:text-cyan-800 font-medium text-sm mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Members
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Member Profile</h1>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-10">
                <!-- Profile & Summary (same as before) -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-8 mb-10">
                    <div class="relative">
                        @if($member->mem_img)
                            <img src="{{ Storage::url($member->mem_img) }}" class="w-28 h-28 rounded-full object-cover ring-8 ring-white shadow-lg border-4 border-white">
                        @else
                            <div class="w-28 h-28 rounded-full bg-cyan-600 ring-8 ring-white shadow-lg flex items-center justify-center text-white text-3xl font-bold border-4 border-white">
                                {{ strtoupper(substr($member->mem_name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="absolute bottom-1 right-1 w-9 h-9 bg-green-500 rounded-full ring-4 ring-white flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $member->mem_name }}</h2>
                        <p class="text-lg font-medium text-gray-600 dark:text-gray-300 mt-1">{{ $member->center?->center_name ?? '—' }}</p>
                        <p class="text-sm text-gray-500 mt-1">ID: <span class="font-mono font-bold">#{{ str_pad($member->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
                    <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/20 rounded-lg p-6 border border-cyan-200 dark:border-cyan-800">
                        <p class="text-xs font-semibold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider">Disbursed</p>
                        <p class="text-2xl font-bold text-cyan-900 dark:text-cyan-300 mt-2">₹{{ number_format($member->disb_amount) }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Tenor</p><p class="text-2xl font-bold mt-2">{{ $member->mem_tenor }} months</p></div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">EMI</p><p class="text-2xl font-bold mt-2">₹{{ number_format($member->monthly_inst ?? round($member->disb_amount / $member->mem_tenor)) }}</p></div>
                </div>

              
            </div>
        </div>

        <!-- BEST IN CLASS INSTALLMENT TABLE -->
<div class="mt-10 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Installment Collection</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Enter amount → Click Save → Status updates automatically</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-4 text-left">Inst #</th>
                    <th class="px-6 py-4 text-left">Due Date</th>
                    <th class="px-6 py-4 text-left">EMI Due</th>
                    <th class="px-6 py-4 text-left">Paid Amount</th>
                    <th class="px-6 py-4 text-left">Remaining</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                @foreach($member->emis as $emi)
                @php
                
                $disabled = $this->daysBetween($emi->due_date) <= 0  ? 0 : 1 ;
                @endphp

                    <tr class="transition-all
                    @if($emi->status === 1)
                    bg-gray-100 text-gray-700 opacity-70 cursor-not-allowed 
                     @elseif($emi->status === 2)
                     bg-red-100 text-red-800 cursor-not-allowed
                    @elseif($disabled || $emi->status === 1)
                    bg-gray-100 text-gray-700 opacity-70 cursor-not-allowed
                            @elseif($disabled === 0)
                                 bg-green-100 text-green-800 
                            @else
                                bg-emerald-50 text-emerald-800 
                            @endif
                        ">

                
                
                <td class="px-6 py-4 font-medium">{{ $loop->iteration }}</td>

                    <td class="px-6 py-4">
                      {{$emi->due_date}}

                      
                    </td>

                    <td class="px-6 py-4 font-medium">
                      ₹{{$emi->inst_amount}}
                    </td>

                    <!-- Paid Amount Input -->
                    <td class="px-6 py-4">
                       
                            <input
                                type="number"
                                placeholder="0"
                                min="0"
                                step="1"
                                max="{{$emi->inst_amount}}"
                                wire:model.live.debounce.300ms="paid_amounts.{{ $emi->id }}"
                                @disabled($disabled || $emi->status > 0 )
                                x-data
                                x-on:input.debounce="
                                    if ($el.value > {{ $emi->inst_amount }})
                                        $el.value = {{ $emi->inst_amount }};"
                                x-on:keydown="
                                if ([8,9,37,38,39,40,46].includes($event.keyCode)) return;
                                if (!/[0-9]/.test($event.key)) $event.preventDefault();"

                                class="w-28 px-3 py-1.5 border rounded text-sm text-right
                                       focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                            >
                     
                    </td>

                    <!-- Remaining -->
                 
                     <td class="px-6 py-4 font-bold">
                      @php
                         $remaining = $this->calculateRemaining($emi->inst_amount ,$paid_amounts[$emi->id]);
                         $isOverdue = $remaining > 0;                         
                      @endphp
                           <span class="{{ $isOverdue ? 'text-red-600' : 'text-green-600' }}">
                                ₹{{ number_format($remaining) }}
                            </span>
                       
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                       {{ match(true) {
                            $emi->status === 1 => 'Fully Paid',
                            $emi->status === 2 => 'Partially Paid',
                            default => $this->getLabelStatus($emi->due_date)
                        }
                     }}
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4">
                     
                            <div class="flex gap-2 flex-wrap">

                                <!-- Full Paid -->
                                <flux:button
                                  wire:click="markFullPaid({{ $emi->id }})"
                                    size="sm"
                                    variant="primary"
                                    color="green"
                                    class="text-xs cursor-pointer {{ ($disabled || $isOverdue || $emi->status) ? 'opacity-50 pointer-events-none' : '' }}"
                                >Full Paid</flux:button>

                              <!-- partialy Paid -->
                                <flux:button
                                    size="sm"
                                    variant="primary"
                                    color="orange"
                                    class="text-xs cursor-pointer {{ ($disabled || !$isOverdue || $emi->status) ? 'opacity-50 pointer-events-none' : '' }}"
                                    wire:click="markpartiallyPaid({{ $emi->id }})"
                                >Partialy Paid</flux:button>

                            </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


        <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-10">
            © {{ date('Y') }} • Microfinance System • {{ now()->format('d M Y') }}
        </p>
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