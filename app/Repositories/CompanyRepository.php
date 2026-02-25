<?php
namespace App\Repositories;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyRepository
{
    /**
     * Get all sliders
     *
     * @return Company[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Company::all();
    }

    /**
     * Get slider by id
     *
     * @param int $id
     * @return Company
     */
    public function getById(int $id)
    {
        return Company::find($id);
    }

    /**
     * Create new slider
     *
     * @param array $data
     * @return Company
     */
    public function create(array $data)
    {
        return Company::create($data);
    }

    /**
     * Update slider
     *
     * @param int $id
     * @param array $data
     * @return Company
     */
    public function update(int $id, array $data)
    {
        $slider = Company::find($id);
        $slider->update($data);

        return $slider;
    }

    /**
     * Delete slider
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return Company::destroy($id);
    }
}
