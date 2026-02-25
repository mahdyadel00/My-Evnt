<?php
namespace App\Repositories;

use App\Models\EventCategory;
use Illuminate\Support\Facades\DB;

class EventCategoryRepository
{
    /**
     * Get all sliders
     *
     * @return EventCategory[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return EventCategory::all();
    }

    /**
     * Get slider by id
     *
     * @param int $id
     * @return EventCategory
     */
    public function getById(int $id)
    {
        return EventCategory::find($id);
    }

    /**
     * Create new slider
     *
     * @param array $data
     * @return EventCategory
     */
    public function create(array $data)
    {
        return EventCategory::create($data);
    }

    /**
     * Update slider
     *
     * @param int $id
     * @param array $data
     * @return EventCategory
     */
    public function update(int $id, array $data)
    {
        $slider = EventCategory::find($id);
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
        return EventCategory::destroy($id);
    }
}
