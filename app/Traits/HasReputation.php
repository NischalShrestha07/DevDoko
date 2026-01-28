<?php

namespace App\Traits;

use App\Models\ReputationLog;

trait HasReputation
{
    public function reputationLogs()
    {
        return $this->hasMany(ReputationLog::class);
    }

    public function addReputation($action, $points)
    {
        $this->reputation_score += $points;
        $this->save();

        $this->reputationLogs()->create([
            'action' => $action,
            'points' => $points,
        ]);
    }

    public function getReputationChange($days = 30)
    {
        return $this->reputationLogs()
            ->where('created_at', '>=', now()->subDays($days))
            ->sum('points');
    }
}
