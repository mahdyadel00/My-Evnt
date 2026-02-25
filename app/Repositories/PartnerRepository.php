<?php

namespace App\Repositories;

use App\Models\Partner;
use Illuminate\Support\Facades\DB;

class PartnerRepository
{
    /**
     * Get all partners
     *
     * @return Partner[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Partner::with('media')->ordered()->get();
    }

    /**
     * Get active partners only
     *
     * @return Partner[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getActive()
    {
        return Partner::with('media')->active()->ordered()->get();
    }

    /**
     * Get partner by id
     *
     * @param int $id
     * @return Partner
     */
    public function getById(int $id)
    {
        return Partner::with('media')->find($id);
    }

    /**
     * Create new partner
     *
     * @param array $data
     * @return Partner
     */
    public function create(array $data)
    {
        return Partner::create($data);
    }

    /**
     * Update partner
     *
     * @param int $id
     * @param array $data
     * @return Partner
     */
    public function update(int $id, array $data)
    {
        $partner = Partner::find($id);
        $partner->update($data);

        return $partner;
    }

    /**
     * Delete partner
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return Partner::destroy($id);
    }
}