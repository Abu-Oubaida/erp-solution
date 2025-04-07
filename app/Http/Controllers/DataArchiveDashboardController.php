<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Log;

class DataArchiveDashboardController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }

    function index(Request $request){
        $permission = $this->permissions()->data_archive;
        $permission_archive_data_list = $this->permissions()->archive_data_list;
        $path = env('APP_FILE_MANAGER').'\Account Document'; 

        $diskTotal = round(disk_total_space($path) / (1024 * 1024 * 1024),2);     // total space in bytes
        $totalUsed = round($this->getFolderSize($path) / (1024 * 1024 * 1024), 2); // in bytes
        
        $diskFree = round(disk_free_space($path) / (1024 * 1024 * 1024), 2);       // free space in bytes
        $dataTypeCount = $this->archiveTypeList($permission)->distinct()->count('id');
        $archiveDocumentCount = $this->getArchiveList($permission_archive_data_list)->get()->pluck('voucherDocuments')->flatten(1)->pluck('id')->count();
        return view('back-end.archive.dashboard', compact('totalUsed','diskTotal','diskFree','dataTypeCount','archiveDocumentCount'));
    }

    private function getFolderSize($dir)
    {
        $size = 0;
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            $size += is_file($path) ? filesize($path) : $this->getFolderSize($path);
        }
        return $size;
    }
}
