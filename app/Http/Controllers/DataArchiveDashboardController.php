<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account_voucher;
use App\Models\company_info;
use App\Models\User;
use App\Models\VoucherType;
use App\Models\VoucherDocument;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use DB;
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
//        $companies = null;
//        if ($this->user->isSystemSuperAdmin()) {
//            $companies = company_info::select('company_code')->get();
//        }
        $path = env('APP_ARCHIVE_DATA');
//        dd($path);
        $diskTotal = round(disk_total_space($path) / (1024 * 1024 * 1024),2);     // total space in bytes
        $archiveUsed = round($this->getFolderSize($path) / (1024 * 1024 * 1024), 2); // in bytes
        $diskFree = round(disk_free_space($path) / (1024 * 1024 * 1024), 2);       // free space in bytes
        $totalUsed = $diskTotal - $diskFree;
        $otherUsed = $totalUsed - $archiveUsed;
        $dataTypeCount = $this->archiveTypeList($permission)->distinct()->count('id');
        $archiveDocumentCount = $this->getArchiveList($permission)->get()->pluck('voucherDocuments')->flatten(1)->pluck('id')->count();
        $accountVoucherInfosCount = Account_voucher::whereIn('voucher_type_id',$this->archiveTypeList($permission)->pluck('id')->toArray())->distinct()->count('id');
        $today = today();
        $today_uploaded_data_by_users = User::with(['archiveDocuments.accountVoucherInfo.VoucherType'])->whereHas('archiveDocuments', function ($query) use ($today) {
            $query->whereDate('created_at', $today);
        })
        ->get()->map(function ($user) use ($today) {
                $grouped = $user->archiveDocuments
                    ->where('created_at', '>=', $today) // extra check for safety
                    ->groupBy(fn($doc) => optional($doc->accountVoucherInfo->VoucherType)->voucher_type_title ?? 'Unknown');

                return [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'document_counts' => $grouped->map->count()
                ];
            });
        $dataTypes = $this->archiveTypeList($permission)->where('status',1)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'company_id' => $item->company_id,
                'company_name' => $item->company->company_code,
                'voucher_type_title' => $item->voucher_type_title,
                'archive_documents_count' => $item->archive_documents_count,
                'archive_document_infos_count' => $item->archive_document_infos_count,
            ];
        });

        $startDate = Carbon::today()->subDays(6);
        $endDate  = Carbon::now();
        // dd($startDate->format('d-m-y'),$endDate->format('d-m-y'),);
        $dailyCounts = VoucherDocument::whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('DAYNAME(created_at) as day','created_at'), DB::raw('COUNT(id) as count'))
        ->groupBy('day')
        ->orderByRaw("FIELD(day, 'Saturday', 'Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')")
        ->get();

        $rawCounts = $dailyCounts->keyBy('day');
        $labels = ['Saturday', 'Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $documentCountsPerDay = [];

        foreach ($labels as $day) {
            $documentCountsPerDay[$day] = $rawCounts[$day]->count ?? 0;
        }

        $totalDocumentCount = (max($documentCountsPerDay)+2);
        return view('back-end.archive.dashboard', compact('totalUsed','diskTotal','diskFree','dataTypeCount','archiveDocumentCount','dataTypes','archiveUsed','otherUsed','labels','documentCountsPerDay','totalDocumentCount','accountVoucherInfosCount','today_uploaded_data_by_users'));
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
