<?php

namespace App\Livewire;

use App\Exports\MembersExport;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class MembersTable extends DataTableComponent
{
    protected $model = Member::class;
    public $centerId = null;

    public function mount($centerId = null) {
        $this->centerId = $centerId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
          $this->setEmptyMessage('No members found');

        $this->setSearchFieldAttributes([
                'class' => 'w-full max-w-md px-3 py-2 text-base rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all',
                'placeholder' => 'Search centers...'
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
        return Excel::download(new MembersExport($ids) , 'members.xlsx');
    }

     #[On('member-created')]
   public function refreshAfterCreate()
    {
        $this->dispatch('refreshDatatable'); // This forces Rappasoft table to re-query the DB
        $this->clearSelected(); // Optional: clear bulk selection
    }

    // public function query()
    // {
    //     return Member::with('center')->where('center_id',1); // ← Load relation (no count)
    // }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Member::query()->with('center');

        if ($this->centerId) {
            $query->where('center_id', $this->centerId);
        }

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "mem_name")
                ->sortable(),
           Column::make("Photo", "mem_img")
                ->format(fn ($value) =>
                    '<img src="'.Storage::url($value).'"
                        class="h-10 w-10 rounded-full object-cover" />'
                )
                ->html(),
            Column::make("Center", "center.center_name")
                ->sortable(),
            Column::make("Disb amount", "disb_amount")
                ->sortable(),
            Column::make("Tenor", "mem_tenor")
                ->sortable(),
            // Column::make("Monthly inst", "monthly_inst")
            //     ->sortable(),
            // Column::make("Phone", "mem_phone")
            //     ->sortable(),
            Column::make("Disb Date", "disb_date")
                ->format(fn($value) => Carbon::parse($value)->format('d M Y') ),
            Column::make('Actions')
            ->label(
                fn($row, Column $column) => view('livewire.table-actions',[
                'row'   => $row,
                'table' => 'member-form'   // ← This tells you which table
            ])
            ),
            
        ];
    }

     public function view($id) {
    return $this->redirect(route('singlemember', $id), navigate: true);
    }
}
