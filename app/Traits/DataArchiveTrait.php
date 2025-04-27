<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait DataArchiveTrait
{
    private function clearArchiveDashboardCache($companyId)
    {
        try {
            // Clear all related caches
            Cache::flush();
            return true;
        }catch (\Throwable $exception) {
            return false;
        }
    }
}
