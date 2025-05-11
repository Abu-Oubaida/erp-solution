<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    private function clearCache()
    {
        try {
            // Clear all related caches
//            Cache::forget("company_disk_info_{$companyId}");
//            Cache::forget("company_archive_stats_{$companyId}");
//            Cache::forget("company_daily_counts_{$companyId}");
            Cache::flush();
            return true;
        }catch (\Throwable $exception) {
            return false;
        }
    }
}
