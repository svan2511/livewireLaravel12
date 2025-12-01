<?php

namespace App\Services;

use App\Repositories\DashboardRepository;

class DashboardService
{
    protected $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    public function loadCenters()
    {
        return $this->repo->getCenters();
    }

    public function loadMembers($centerId)
    {
        return $this->repo->getMembers($centerId);
    }

    public function counts($centerId)
    {
        $members = $this->repo->getMembers($centerId);

        return [
            'users'   => $this->repo->userCount(),
            'members' => $members->count(),
            'centers' => $this->repo->getCenters()->count(),
            'membersData' => $members,
        ];
    }

    public function getGraphData($filters)
    {
        $data = $this->repo->getGraphQuery($filters);
        return $this->format($data);
    }

    private function format($data)
    {
        $result = [];

        foreach ($data as $item) {
            $yr = $item->year;

            if (!isset($result[$yr])) {
                $result[$yr] = [
                    'disb' => array_fill(0, 12, 0),
                    'coll' => array_fill(0, 12, 0),
                    'od'   => array_fill(0, 12, 0),
                    'dem'  => array_fill(0, 12, 0),
                ];
            }

            $i = $item->month_num - 1;

            $result[$yr]['disb'][$i] = $item->total_dis;
            $result[$yr]['coll'][$i] = $item->total_coll;
            $result[$yr]['od'][$i]   = $item->total_od;
            $result[$yr]['dem'][$i]  = $item->total_dem;
        }

        return $result;
    }
}
