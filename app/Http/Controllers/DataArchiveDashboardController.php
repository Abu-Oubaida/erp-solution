<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VoucherType;
use App\Models\VoucherDocument;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Log;
use Carbon\Carbon;

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
        $archiveUsed = round($this->getFolderSize($path) / (1024 * 1024 * 1024), 2); // in bytes
        $diskFree = round(disk_free_space($path) / (1024 * 1024 * 1024), 2);       // free space in bytes
        $totalUsed = $diskTotal - $diskFree;
        $otherUsed = $totalUsed - $archiveUsed;
        $dataTypeCount = $this->archiveTypeList($permission)->distinct()->count('id');
        $archiveDocumentCount = $this->getArchiveList($permission_archive_data_list)->get()->pluck('voucherDocuments')->flatten(1)->pluck('id')->count();
        $dataTypes = $this->archiveTypeList($permission)->where('status',1)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'company_id' => $item->company_id,
                'voucher_type_title' => $item->voucher_type_title,
                'archive_documents_count' => $item->archive_documents_count,
                'archive_document_infos_count' => $item->archive_document_infos_count,
            ];
        });
        $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday','Saturday', 'Sunday'];

        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        
        $documents = VoucherDocument::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->get();
        
        $documentCountsPerDay = [
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0,
        ];
        
        foreach ($documents as $doc) {
            $day = Carbon::parse($doc->created_at)->format('l'); // Get full day name
            $documentCountsPerDay[$day]++;
        }
        $totalDocumentCount = array_sum($documentCountsPerDay);
        return view('back-end.archive.dashboard', compact('totalUsed','diskTotal','diskFree','dataTypeCount','archiveDocumentCount','dataTypes','archiveUsed','otherUsed','labels','documentCountsPerDay','totalDocumentCount'));
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
