<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Member;
use App\Models\Center;

class DashboardRepository
{
    public function getCenters()
    {
        return Center::whereStatus(1)->get();
    }

    public function getMembers($centerId)
    {
        $q = Member::whereStatus(1);
        if ($centerId !== 'all') {
            $q->where('center_id', $centerId);
        }
        return $q->get();
    }

    public function userCount()
    {
        return User::count();
    }

    public function getGraphQuery($filters)
    {
        $query = DB::table('financial_summaries')
            ->join('members', 'financial_summaries.member_id', '=', 'members.id')
            ->select(
                'month_name',
                'month_num',
                'year',
                DB::raw('SUM(disbursement_amount) AS total_dis'),
                DB::raw('SUM(collection_amount) AS total_coll'),
                DB::raw('SUM(outstanding_amount) AS total_od'),
                DB::raw('SUM(demand_amount) AS total_dem')
            );

        if ($filters['year'])
            $query->where('year', $filters['year']);

        if ($filters['member'] !== 'all')
            $query->where('member_id', $filters['member']);

        if ($filters['center'] !== 'all')
            $query->where('members.center_id', $filters['center']);

        return $query->groupBy('year', 'month_name', 'month_num')
            ->orderBy('month_num')
            ->get();
    }
}
