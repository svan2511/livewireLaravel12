<?php

namespace App\Livewire;

use App\Exports\UsersExport;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
         $this->setEmptyMessage('No User found');

        $this->setSearchFieldAttributes([
                'class' => 'w-full max-w-md px-3 py-2 text-base rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all',
                'placeholder' => 'Search user...'
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
        return Excel::download(new UsersExport($ids) , 'users.xlsx');
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name')->toArray();
       if(!in_array('admin', $userRoles)) {
            return User::where('id', $user->id);
            } else {
                return User::query();
            }
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
            Column::make("Name", "name")
                ->sortable()->searchable(),
            Column::make("Email", "email")
                ->sortable()->searchable(),
                // ✅ ROLES COLUMN
            Column::make("Roles")
            ->label(
                fn ($row) => $row->roles->pluck('name')
                           ->map(fn($r) => "<span class='px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs'>$r</span>")
                           ->implode(' ')
            )
            ->html(),

             // ✅ PERMISSIONS COLUMN
            Column::make("Permissions")
            ->label(function ($row) {

                // Check if the user has the admin role
                if ($row->hasRole('admin')) {
                    return "<span class='px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs'>
                                All Permissions
                            </span>";
                }
                // Otherwise show assigned permissions
                $permissions = $row->getAllPermissions()->pluck('name');
                if ($permissions->isEmpty()) {
                    return "<span class='px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs'>
                                No Permissions
                            </span>";
                }
                return $permissions
                    ->map(fn($p) => "<span class='px-2 py-1 bg-green-100 text-green-700 rounded text-xs'>$p</span>")
                    ->implode(' ');
            })
            ->html(),
                
             Column::make('Actions')
            ->label(
                fn($row, Column $column) => view('livewire.table-actions',[
                'row'   => $row,
                'modal' => 'user-form',
                'viewPermisson' => 'view-user',
                'editPermisson' => 'edit-user',
                'deletePermisson' => 'delete-user',
            ])
            ),
        ];
    }
}
