<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function logging($model, $event)
    {
        $userId = Auth::id();

        if (!$userId || !\App\Models\User::where('id', $userId)->exists()) {
            return;
        }

        AuditLog::create([
            'model_type' => $model->getTable(),
            'model_id'   => $model->id,
            'event'      => $event,
            'data'       => json_encode($model->toArray()),
            'user_id'    => $userId,
        ]);
    }

    public function created($model)
    {
        $this->logging($model, 'created');
    }

    public function updated($model)
    {
        $this->logging($model, 'updated');
    }

    public function deleted($model)
    {
        $this->logging($model, 'deleted');
    }

    public function restored($model)
    {
        $this->logging($model, 'restored');
    }
}
