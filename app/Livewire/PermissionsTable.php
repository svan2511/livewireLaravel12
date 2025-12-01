<?php

namespace App\Livewire;

use App\Exports\PermissionsExport;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Permission;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class PermissionsTable extends DataTableComponent
{
    protected $model = Permission::class;
    

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setEmptyMessage('No permission found');

        $this->setSearchFieldAttributes([
                'class' => 'w-full max-w-md px-3 py-2 text-base rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all',
                'placeholder' => 'Search permission...'
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
        return Excel::download(new PermissionsExport($ids) , 'permissions.xlsx');
    }

     #[On('member-created')]
   public function refreshAfterCreate()
    {
        $this->dispatch('refreshDatatable'); // This forces Rappasoft table to re-query the DB
        $this->clearSelected(); // Optional: clear bulk selection
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            // Column::make("Name", "name")
            //     ->sortable(),
            Column::make("Label", "label")
                ->sortable(),
            Column::make("Desc", "desc")
                ->sortable(),
            // Column::make("Guard name", "guard_name")
            //     ->sortable(),
            Column::make("Module", "module")
                ->sortable(),
            
           Column::make("Created Date", "created_at")
                ->format(fn($value) => Carbon::parse($value)->format('d M Y') ),
           Column::make('Actions')
            ->label(
                fn($row, Column $column) => view('livewire.table-actions',[
                'row'   => $row,
                'modal' => 'permission-form' ,
                 'viewPermisson' => 'view-permission',
                'editPermisson' => 'edit-permission',
                'deletePermisson' => 'delete-permission'
            ])
            ),
        ];
    }

}
