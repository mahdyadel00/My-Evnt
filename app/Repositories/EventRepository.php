<?php
namespace App\Repositories;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventRepository
{
    /**
     * Get all sliders
     *
     * @return Event[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Event::all();
    }

    /**
     * Get slider by id
     *
     * @param int $id
     * @return Event
     */
//    public function getById(int $id)
//    {
//        return Event::find($id);
//    }

    /**
     * Create new slider
     *
     * @param array $data
     * @return Event
     */
    public function create(array $data)
    {
        return Event::create($data);
    }

    /**
     * Update slider
     *
     * @param int $id
     * @param array $data
     * @return Event
     */
    public function update(int $id, array $data)
    {
        $slider = Event::find($id);
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
        return Event::destroy($id);
    }
}
