<?php
namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingRepository
{
    public function getSetting($key)
    {
        return Setting::where('key', $key)->first();
    }

    public function updateSetting($key, $value)
    {
        return Setting::where('key', $key)->update(['value' => $value]);
    }

    public function getSettings()
    {
        return Setting::all();
    }

    public function updateSettings($settings)
    {
        DB::beginTransaction();
        try {
            foreach ($settings as $key => $value) {
                Setting::where('key', $key)->update(['value' => $value]);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
