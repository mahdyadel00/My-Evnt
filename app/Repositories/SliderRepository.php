<?php
namespace App\Repositories;

use App\Models\Slider;
use Illuminate\Support\Facades\DB;

class SliderRepository
{
    /**
     * Get all sliders
     *
     * @return Slider[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Slider::all();
    }

    /**
     * Get slider by id
     *
     * @param int $id
     * @return Slider
     */
    public function getById(int $id)
    {
        return Slider::find($id);
    }

    /**
     * Create new slider
     *
     * @param array $data
     * @return Slider
     */
    public function create(array $data)
    {
        return Slider::create($data);
    }

    /**
     * Update slider
     *
     * @param int $id
     * @param array $data
     * @return Slider
     */
    public function update(int $id, array $data)
    {
        $slider = Slider::find($id);
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
        return Slider::destroy($id);
    }
}
