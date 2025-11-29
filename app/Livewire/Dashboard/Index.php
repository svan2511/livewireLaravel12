<?php

namespace App\Livewire\Dashboard;

use App\Models\Center;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $selectedYear = 2025;
    public $selectedCenter = 'all';
    public $selectedMember = 'all';

    public $userCount = 0;
    public $memberCount = 0;
    public $centerCount = 0;

    public object $centers;
    public object $members; 

    public $graphdata;

    /*--------------------------------------------
     | REUSABLE METHOD TO REFRESH GRAPH DATA
     ---------------------------------------------*/
    private function refreshGraphData()
    {
        $this->graphdata = $this->getGraphData();
        $this->dispatch('update-charts', graphdata: $this->graphdata);
    }

    /*--------------------------------------------
     | FILTER HANDLERS
     ---------------------------------------------*/
    public function updatedSelectedYear()
    {
        $this->refreshGraphData();
    }

    public function updatedSelectedCenter($centerId)
    {
        //dd($centerId);
        $this->refreshGraphData();
    }

    public function updatedSelectedMember()
    {
        $this->refreshGraphData();
    }

    public function applyFilters()
    {
        $this->refreshGraphData();
    }

    /*--------------------------------------------
     | RENDER METHOD
     ---------------------------------------------*/
    public function render()
    {
        // Static data independent of filters
        $memberQuery = Member::whereStatus(1);
        if($this->selectedCenter !== "all") {
            $memberQuery = $memberQuery->where('center_id' , $this->selectedCenter);
        }
        $this->members = $memberQuery->get();
        $memberQuery = $memberQuery->get();
        $this->userCount   = User::count();
        $this->centers     = Center::whereStatus(1)->get();
        $this->memberCount = $this->members->count();
        $this->centerCount = $this->centers->count();

        // Always generate latest graph data on render
        $this->graphdata = $this->getGraphData();

        return view('livewire.dashboard.index');
    }

    /*--------------------------------------------
     | DATABASE QUERY FOR GRAPH DATA
     ---------------------------------------------*/
    private function getGraphData()
    {
        $query = DB::table('financial_summaries')
            ->join('members', 'financial_summaries.member_id', '=', 'members.id')   // ⭐ FIXED JOIN
            ->select(
                'month_name',
                'month_num',
                'year',
                DB::raw('SUM(disbursement_amount) as total_dis'),
                DB::raw('SUM(collection_amount) as total_coll'),
                DB::raw('SUM(outstanding_amount) as total_od'),
                DB::raw('SUM(demand_amount) as total_dem')
            );

        // Filter by year
        $query->when($this->selectedYear, function ($q) {
            $q->where('year', $this->selectedYear);
        });

        // Filter by Member
        $query->when(
            $this->selectedMember && $this->selectedMember !== 'all',
            function ($q) {
                $q->where('member_id', $this->selectedMember);
            }
        );

        // Uncomment if center filter is needed later
       $query->when(
        $this->selectedCenter && $this->selectedCenter !== 'all',
        function ($q) {
            $q->where('members.center_id', $this->selectedCenter);
        }
    );

        $data = $query->groupBy('year', 'month_name', 'month_num')
            ->orderBy('month_num')
            ->get();

        return $this->convertData($data);
    }

    /*--------------------------------------------
     | FORMAT DATABASE RESULT FOR CHART.JS
     ---------------------------------------------*/
    private function convertData($data)
    {
        $yearlyData = [];

        foreach ($data as $item) {
            $year = $item->year;

            if (!isset($yearlyData[$year])) {
                $yearlyData[$year] = [
                    'disb' => array_fill(0, 12, 0),
                    'coll' => array_fill(0, 12, 0),
                    'od'   => array_fill(0, 12, 0),
                    'dem'  => array_fill(0, 12, 0),
                ];
            }

            $index = $item->month_num - 1;

            $yearlyData[$year]['disb'][$index] = $item->total_dis;
            $yearlyData[$year]['coll'][$index] = $item->total_coll;
            $yearlyData[$year]['od'][$index]   = $item->total_od;
            $yearlyData[$year]['dem'][$index]  = $item->total_dem;
        }

        return $yearlyData;
    }
}
