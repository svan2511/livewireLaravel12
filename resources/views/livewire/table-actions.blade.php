@if($table === 'permission-form' || $table === 'role-form' )
    <flux:button 
        icon="eye"
        variant="primary"
        color="cyan"
        class="cursor-pointer"
        wire:click="$dispatch('view-table', { table: {{ json_encode($table ?? 'unknown') }} , row: {{ json_encode($row) }} })"
    >
    </flux:button>
@else
    <flux:button 
        icon="eye"
        variant="primary"
        color="cyan"
        class="cursor-pointer"
        wire:click="view({{ $row->id }})"
    >
    </flux:button>
@endif

    <flux:button icon="pencil"
     wire:click="$dispatch('edit-table', { table: {{ json_encode($table ?? 'unknown') }} , row: {{ json_encode($row) }} })"
      variant="primary" color="cyan" class="cursor-pointer"></flux:button>

  <!-- <flux:button icon="trash"
  wire:click="$dispatch('delete-table', { table: {{ json_encode($table ?? 'unknown') }} , row: {{ json_encode($row) }} })"
  variant="primary" color="rose" class="cursor-pointer">Delete</flux:button>  -->

  <!-- Delete Button – NOW PERFECTLY ALIGNED & SAFE -->
<flux:button icon="trash"
             wire:click="$dispatch('delete-table', { table: {{ json_encode($table ?? 'unknown') }} , row: {{ json_encode($row) }} })"
             wire:loading.remove.delay.short
             wire:loading.attr="disabled"
             variant="primary" 
             color="rose"
             class="cursor-pointer"
             wire:confirm="Delete this row permanently?">

</flux:button>