<?php

namespace App\Livewire;

use App\Exports\RolesExport;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Role;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class RolesTable extends DataTableComponent
{
    protected $model = Role::class;

    public function configure(): void
    {
       $this->setPrimaryKey('id');
        $this->setEmptyMessage('No Role found');

        $this->setSearchFieldAttributes([
                'class' => 'w-full max-w-md px-3 py-2 text-base rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all',
                'placeholder' => 'Search role...'
            ]);

        $this->setSearchVisibilityStatus(true);
        $this->setSearchDebounce(500);

        $this->setSearchPlaceholder('Search...');
        $this->setDefaultSort('id', 'desc');
        $this->setPerPageAccepted([10,20,30,40,50]);
        $this->setPerPage(10); 
        $this->setQueryStringStatus(false);
        $this->setQueryStringDisabled();
        $this->setBulkActions([
            "exportExcel" => "Export Data to Excel"
        ]);
    }

      public function exportExcel() {
        $ids = $this->getSelected();
        if (empty($ids)) {
        $this->dispatch('toast', message: "Please Select atleast One Row.", type: 'error');
        return;
        }
        return Excel::download(new RolesExport($ids) , 'roles.xlsx');
    }


     #[On('member-created')]
   public function refreshAfterCreate()
    {
        $this->dispatch('refreshDatatable'); // This forces Rappasoft table to re-query the DB
        $this->clearSelected(); // Optional: clear bulk selection
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Role::with('permissions');
    }



    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
         Column::make("Name", "label")
            ->sortable()
            ->html(),
            // Column::make("Label", "label")
            //     ->sortable(),
          Column::make("Permissions")
            ->label(function ($row) {
                // If role name is admin → show All Permissions
                if (strtolower($row->label) === 'admin') {
                    return "<span class='px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs'>
                                All Permissions
                            </span>";
                }
                // If no permissions
                if ($row->permissions->isEmpty()) {
                    return '<span class="text-gray-400 text-sm">No Permissions</span>';
                }
                // Show all permissions
                return $row->permissions
                    ->pluck('label')
                    ->map(fn($label) => "<span class='px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs'>$label</span>")
                    ->join(' ');
            })
            ->html(),

           Column::make("Created Date", "created_at")
                ->format(fn($value) => Carbon::parse($value)->format('d M Y') ),
           Column::make('Actions')
            ->label(
                fn($row, Column $column) => view('livewire.table-actions',[
                'row'   => $row,
                'modal' => 'role-form'  ,
                 'viewPermisson' => 'view-role',
                'editPermisson' => 'edit-role',
                'deletePermisson' => 'delete-role'
            ])
            ),
        ];
    }
}
