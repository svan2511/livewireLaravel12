<?php

namespace App\Livewire;

use App\Exports\CentersExport;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Center;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;

class CentersTable extends DataTableComponent
{

    protected $model = Center::class;

    public function configure(): void
    {
      
        $this->setEmptyMessage('No centers found');
         $this->setPrimaryKey('id');

$this->setSearchFieldAttributes([
        'class' => 'w-full max-w-md px-3 py-2 text-base rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all',
        'placeholder' => 'Search centers...'
    ]);

    // Optional: make it look even better
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
        return Excel::download(new CentersExport($ids) , 'centers.xlsx');
    }

     #[On('center-created')]
   public function refreshAfterCreate()
    {
        $this->dispatch('refreshDatatable'); // This forces Rappasoft table to re-query the DB
        $this->clearSelected(); // Optional: clear bulk selection
    }


    public function deleteConfirmed($id)
    {
        Center::destroy($id);
        $this->dispatch('$refresh'); // or your refresh event
    }

    public function query()
    {
        return Center::with('members'); // ← Load relation (no count)
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()->searchable(),
            Column::make("Center name", "center_name")
                ->sortable()->searchable(),
            Column::make("Center address", "center_address")
                ->searchable(),
            // THIS IS THE ONLY WAY THAT WORKS ON SQLITE
            Column::make('Total Members')
                ->label(fn($row) => 
                    '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">'
                    . $row->members->count() . 
                    '</span>'
                )
                ->html()
                ,
            Column::make("Created On", "created_at")
                ->format(fn($value) => $value->diffForHumans()),
            Column::make('Actions')
            ->label(
                fn($row, Column $column) => view('livewire.table-actions',[
                'row'   => $row,
                'modal' => 'center-form',
                 'viewPermisson' => 'view-center',
                'editPermisson' => 'edit-center',
                'deletePermisson' => 'delete-center'
            ])
            ),
        
        ];
    }

      public function view($memberId) {
        return $this->redirect(route('singlecenter', $memberId), navigate: true);
    }

}
