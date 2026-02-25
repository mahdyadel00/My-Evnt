<?php
namespace App\Repositories;

use App\Models\Currency;
use Illuminate\Support\Facades\DB;

class CurrencyRepository
{
    /**
     * Get all sliders
     *
     * @return Currency[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Currency::all();
    }

    /**
     * Get slider by id
     *
     * @param int $id
     * @return Currency
     */
    public function getById(int $id)
    {
        return Currency::find($id);
    }

    /**
     * Create new slider
     *
     * @param array $data
     * @return Currency
     */
    public function create(array $data)
    {
        return Currency::create($data);
    }

    /**
     * Update slider
     *
     * @param int $id
     * @param array $data
     * @return Currency
     */
    public function update(int $id, array $data)
    {
        $slider = Currency::find($id);
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
        return Currency::destroy($id);
    }
}
