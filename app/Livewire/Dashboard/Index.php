<?php

namespace App\Livewire\Dashboard;

use App\Services\DashboardService;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Index extends Component
{
    public $selectedYear = 2025;
    public $selectedCenter = 'all';
    public $selectedMember = 'all';

    public $userCount = 0;
    public $memberCount = 0;
    public $centerCount = 0;

    public $centers = [];
    public $members = [];
    public $graphdata = [];

    protected DashboardService $service;

    public function boot(DashboardService $service)
    {
        $this->service = $service;
    }

    public function updated($field)
    {
        $this->refreshData();
    }

    public function applyFilters()
    {
        $this->refreshData();
    }

    private function refreshData()
    {
        
        $filters = [
            'year'   => $this->selectedYear,
            'center' => $this->selectedCenter,
            'member' => $this->selectedMember,
        ];
        try{
        $this->graphdata = $this->service->getGraphData($filters);
        }
        catch(Exception $ex) {
        Log::info("ERROR:DASH1 : " . $ex->getMessage());
        $this->dispatch('toast', message: "Some internal Error !", type: "error");
        }

        $this->dispatch('update-charts', graphdata: $this->graphdata);
    }

    public function render()
    {
        try{
        $this->centers = $this->service->loadCenters();

        // Load member lists depending on center
        $this->members = $this->service->loadMembers($this->selectedCenter);

        // Load counts
        $counts = $this->service->counts($this->selectedCenter);

        $this->userCount = $counts['users'];
        $this->memberCount = $counts['members'];
        $this->centerCount = $counts['centers'];
        // Graph data
        $this->refreshData();
        }
        catch(Exception $ex) {
        Log::info("ERROR:DASH2 : " . $ex->getMessage());
        $this->dispatch('toast', message: "Some internal Error !", type: "error");
        }
        return view('livewire.dashboard.index');
    }
        
        
    
}
