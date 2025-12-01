<?php

namespace App\Repositories;

use App\Models\Member;

class MemberRepository
{
    public function find($id)
    {
        return Member::find($id);
    }

    public function findOrFail($id)
    {
        return Member::findOrFail($id);
    }

    public function create(array $data)
    {
        return Member::create($data);
    }

    public function update(Member $member, array $data)
    {
        return $member->update($data);
    }

    public function delete($id)
    {
        return Member::destroy($id);
    }
}
