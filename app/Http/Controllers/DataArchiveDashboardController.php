<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account_voucher;
use App\Models\company_info;
use App\Models\User;
use App\Models\VoucherType;
use App\Models\VoucherDocument;
use App\Traits\CacheTrait;
use App\Traits\ParentTraitCompanyWise;
use Database\Seeders\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
        $companies = $this->getCompanyModulePermissionWise($permission)->get();
        return view('back-end.archive.dashboard', compact('companies'));
    }


    public function companyWiseArchiveDashboardInstant(Request $request)// Instant Report Max Loading Time
    {
        $permission = $this->permissions()->data_archive;
        try {
            if ($request->ajax()) {
                $request->validate([
                    'company_id' => ['required','integer','exists:company_infos,id'],
                ]);
                extract($request->post());
                $company = company_info::find($company_id);
                $permittedProjectIds = $this->getUserProjectPermissions($this->user->id, $permission)
                    ->pluck('id')
                    ->toArray();

                $path = env('APP_ARCHIVE_DATA');
                $company_dir = $path.'/'.$company->company_code;
                if (!is_dir($company_dir)) {
                    mkdir($company_dir, 0777, true); // recursive mkdir
                }
                $diskTotal = round(disk_total_space($path) / (1024 * 1024 * 1024),2);     // total space in bytes
                $archiveUsed = round($this->getFolderSize($company_dir) / (1024 * 1024 * 1024), 2); // in bytes
                $diskFree = round(disk_free_space($path) / (1024 * 1024 * 1024), 2);       // free space in bytes
                $totalUsed = $diskTotal - $diskFree;
                $otherUsed = $totalUsed - $archiveUsed;


                $dataTypeCount = $this->archiveTypeList($permission)->where('company_id',$company->id)->distinct()->count('id');
                $archiveDocumentCount = $this->getArchiveList($permission)
                    ->where('company_id', $company->id)
                    ->with('voucherDocuments') // only need this relationship
                    ->get()
                    ->flatMap(function ($voucher) {
                        return $voucher->voucherDocuments;
                    })
                    ->pluck('id')
                    ->unique()
                    ->count();
                $accountVoucherInfosCount = Account_voucher::whereIn('voucher_type_id',$this->archiveTypeList($permission)->pluck('id')->toArray())
                    ->whereIn('project_id',$permittedProjectIds)
                    ->where('company_id',$company->id)->distinct()->count('id');

                $today = today();
                $today_uploaded_data_by_users = User::with(['archiveDocuments.accountVoucherInfo.VoucherType'])->whereHas('archiveDocuments', function ($query) use ($today, $company) {
                    $query->whereDate('created_at', $today)->where('company_id', $company->id);
                })
                    ->get()->map(function ($user) use ($today,$company) {
                        $grouped = $user->archiveDocuments
                            ->where('created_at', '>=', $today) // extra check for safety
                            ->where('company_id', $company->id)
                            ->groupBy(fn($doc) => optional($doc->accountVoucherInfo->VoucherType)->voucher_type_title ?? 'Unknown');

                        return [
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'document_counts' => $grouped->map->count()
                        ];
                    });
                $dataTypes = $this->archiveTypeList($permission)->where('status',1)->where('company_id',$company->id)->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company_id' => $item->company_id,
                        'company_name' => $item->company->company_code,
                        'voucher_type_title' => $item->voucher_type_title,
                        'archive_documents_count' => $item->archive_documents_count,
                        'archive_document_infos_count' => $item->archive_document_infos_count,
                    ];
                });

                $startDate = Carbon::now()->copy()->subDays(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();

                $dailyCounts = VoucherDocument::whereBetween('created_at', [$startDate, $endDate])
                    ->where('company_id', $company->id)
                    ->select(
                        DB::raw("DATE(created_at) as date"),
                        DB::raw("DAYNAME(created_at) as day"),
                        DB::raw("COUNT(id) as count")
                    )
                    ->groupBy(DB::raw("DATE(created_at)"), DB::raw("DAYNAME(created_at)"))
                    ->orderBy("date")
                    ->get();

                $labels = [];
                $period = Carbon::parse($startDate)->daysUntil($endDate);

                foreach ($period as $date) {
                    $labels[] = [
                        'key' => $date->format('D (M-d)'), // e.g., "Monday (08-04-2025)"
                        'date' => $date->toDateString(),
                        'day' => $date->format('l'),
                    ];
                }
                $rawCounts = $dailyCounts->keyBy('date');

                $documentCountsPerDay = [];

                foreach ($labels as $label) {
                    $count = $rawCounts[$label['date']]->count ?? 0;
                    $documentCountsPerDay[$label['key']] = $count;
                }
                $totalDocumentCount = (max($documentCountsPerDay)+40);
                $view = view('back-end.archive._dashboard_content', compact('totalUsed','diskTotal','diskFree','dataTypeCount','archiveDocumentCount','dataTypes','archiveUsed','otherUsed','labels','documentCountsPerDay','totalDocumentCount','accountVoucherInfosCount','today_uploaded_data_by_users','company_id'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => $view,
                    'message' => 'Request processed successfully!'
                ]);
            }
            throw new \Exception('Requested method not allowed!');
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }


    public function companyWiseArchiveDashboard(Request $request) // Cache disk/folder size info for 10 minutes, Cache archive-related counts for 5 minutes
    {
        $permission = $this->permissions()->data_archive;
        try {
            if ($request->ajax()) {
                $request->validate([
                    'company_id' => ['required', 'integer', 'exists:company_infos,id'],
                ]);
                extract($request->post());
                $company = company_info::find($company_id);
                $permittedProjectIds = $this->getUserProjectPermissions($this->user->id, $permission)
                    ->pluck('id')
                    ->toArray();

                $path = env('APP_ARCHIVE_DATA');
                $company_dir = $path . '/' . $company->company_code;
                if (!is_dir($company_dir)) {
                    mkdir($company_dir, 0777, true);
                }

                // Cache disk/folder size info for 10 minutes
                $diskInfo = Cache::remember("company_disk_info_{$company->id}_{$this->user->id}", 600, function () use ($path, $company_dir,$company) {
//                    $diskTotal = ($company->archiveStorage && $company->archiveStorage->storage_package_id)
//                        ? $company->archiveStorage->package->package_size
//                        : round(disk_total_space($path) / (1024 * 1024 * 1024), 2);
                    $diskTotal = ($company->archiveStorage && $company->archiveStorage->storage_package_id)
                        ? $company->archiveStorage->package->package_size
                        : 'N/A';
                    $archiveUsed = round($this->getFolderSize($company_dir) / (1024 * 1024 * 1024), 2);
                    $diskFree = ($diskTotal != 'N/A')?$diskTotal-$archiveUsed:'N/A';
                    $totalUsed = $archiveUsed;

                    return compact('diskTotal', 'archiveUsed', 'diskFree', 'totalUsed',);
                });

                // Cache archive-related counts for 5 minutes
                $archiveStats = Cache::remember("company_archive_stats_{$company->id}_{$this->user->id}", 300, function () use ($permission, $company, $permittedProjectIds) {
                    $dataTypeCount = $this->archiveTypeList($permission)->where('company_id', $company->id)->distinct()->count('id');

                    $archiveDocumentCount = $this->getArchiveList($permission)
                        ->where('company_id', $company->id)
                        ->with('voucherDocuments')
                        ->get()
                        ->flatMap(function ($voucher) {
                            return $voucher->voucherDocuments;
                        })
                        ->pluck('id')
                        ->unique()
                        ->count();

                    $accountVoucherInfosCount = Account_voucher::whereIn('voucher_type_id', $this->archiveTypeList($permission)->pluck('id')->toArray())
                        ->whereIn('project_id', $permittedProjectIds)
                        ->where('company_id', $company->id)
                        ->distinct()
                        ->count('id');

                    $dataTypes = $this->archiveTypeList($permission)
                        ->where('status', 1)
                        ->where('company_id', $company->id)
                        ->orderBy('voucher_type_title','asc')
                        ->get()
                        ->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'company_id' => $item->company_id,
                                'company_name' => $item->company->company_code,
                                'voucher_type_title' => $item->voucher_type_title,
                                'archive_documents_count' => $item->archive_documents_count,
                                'archive_document_infos_count' => $item->archive_document_infos_count,
                            ];
                        });

                    return compact('dataTypeCount', 'archiveDocumentCount', 'accountVoucherInfosCount', 'dataTypes');
                });

                // Recent uploads by users (today) — maybe don't cache, because today's uploads are dynamic
                $today = today();
                $today_uploaded_data_by_users = User::with(['archiveDocuments.accountVoucherInfo.VoucherType'])
                    ->whereHas('archiveDocuments', function ($query) use ($today, $company) {
                        $query->whereDate('created_at', $today)->where('company_id', $company->id);
                    })
                    ->get()
                    ->map(function ($user) use ($today, $company) {
                        $grouped = $user->archiveDocuments
                            ->where('created_at', '>=', $today)
                            ->where('company_id', $company->id)
                            ->groupBy(fn($doc) => optional($doc->accountVoucherInfo->VoucherType)->voucher_type_title ?? 'Unknown');

                        return [
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'document_counts' => $grouped->map->count()
                        ];
                    });

                // Daily document counts for last 7 days
                $startDate = Carbon::now()->copy()->subDays(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();

                $dailyCounts = Cache::remember("company_daily_counts_{$company->id}_{$this->user->id}", 300, function () use ($startDate, $endDate, $company) {
                    return VoucherDocument::whereBetween('created_at', [$startDate, $endDate])
                        ->where('company_id', $company->id)
                        ->select(
                            DB::raw("DATE(created_at) as date"),
                            DB::raw("DAYNAME(created_at) as day"),
                            DB::raw("COUNT(id) as count")
                        )
                        ->groupBy(DB::raw("DATE(created_at)"), DB::raw("DAYNAME(created_at)"))
                        ->orderBy("date")
                        ->get();
                });

                $labels = [];
                $period = Carbon::parse($startDate)->daysUntil($endDate);
                foreach ($period as $date) {
                    $labels[] = [
                        'key' => $date->format('D (M-d)'),
                        'date' => $date->toDateString(),
                        'day' => $date->format('l'),
                    ];
                }

                $rawCounts = $dailyCounts->keyBy('date');

                $documentCountsPerDay = [];
                foreach ($labels as $label) {
                    $count = $rawCounts[$label['date']]->count ?? 0;
                    $documentCountsPerDay[$label['key']] = $count;
                }

                $totalDocumentCount = (max($documentCountsPerDay) + 40);

                // Render view
                $view = view('back-end.archive._dashboard_content', array_merge(
                    $diskInfo,
                    $archiveStats,
                    compact('today_uploaded_data_by_users', 'labels', 'documentCountsPerDay', 'totalDocumentCount', 'company_id')
                ))->render();

                return response()->json([
                    'status' => 'success',
                    'data' => $view,
                    'message' => 'Request processed successfully!'
                ]);
            }
            throw new \Exception('Requested method not allowed!');
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    private function getDateRange($rangeType)
    {
        $now = Carbon::now();

        return match ($rangeType) {
            'today' => [
                'from' => $now->copy()->startOfDay(),
                'to'   => $now->copy()->endOfDay(),
            ],
            'last_day' => [
                'from' => $now->copy()->subDay()->startOfDay(),
                'to'   => $now->copy()->subDay()->endOfDay(),
            ],
            'last_7_days' => [
                'from' => $now->copy()->subDays(7)->startOfDay(),
                'to'   => $now->endOfDay(),
            ],
            'last_30_days' => [
                'from' => $now->copy()->subDays(30)->startOfDay(),
                'to'   => $now->endOfDay(),
            ],
            'last_12_months' => [
                'from' => $now->copy()->subMonthsNoOverflow(11)->startOfMonth(),
                'to'   => $now->copy()->endOfMonth(),
            ],
            'last_365_days' => [
                'from' => $now->copy()->subDays(365)->startOfDay(),
                'to'   => $now->endOfDay(),
            ],
            'all' => [
                'from' => null,
                'to'   => null,
            ],
            default => null,
        };
    }

    public function companyWiseArchiveDashboardDateWise(Request $request)
    {
        $request->validate([
            'company_id' => ['required','integer','exists:company_infos,id'],
            'date_range_name' => ['required','string'],
        ]);
        extract($request->post());
        if ($date_range_name == 'today')
        {
            $range = $this->getDateRange('today');
        }
        else if ($date_range_name == 'yesterday' || $date_range_name == 'last_day')
        {
            $range = $this->getDateRange('last_day');
        }
        else if ($date_range_name == 'last_7_days')
        {
            $range = $this->getDateRange('last_7_days');
        }
        else if ($date_range_name == 'last_30_days')
        {
            $range = $this->getDateRange('last_30_days');
        }
        else if ($date_range_name == 'last_365_days' || $date_range_name == 'last_year' || $date_range_name == 'last_12_months')
        {
            $range = $this->getDateRange('last_12_months');
        }
        else {
            $range = $this->getDateRange('all');
        }

        $lastday_uploaded_data_by_users=$this->getCompanyWiseDashboardDocuments3Param($company_id,$range['from'], $range['to']);
        $view = view('back-end.archive._day_wise_document_dashboard_content', compact('lastday_uploaded_data_by_users'))->render();
        return response()->json([
            'status' => 'success',
            'data' => $view,
            'message' => 'Request processed successfully!'
        ]);
    }


    public function getCompanyWiseDashboardDocuments3Param($company_id,$start,$end){
        $company = company_info::find($company_id);
        if($start == null || $end == null){
            return User::with(['archiveDocuments.accountVoucherInfo.VoucherType'])
            ->whereHas('archiveDocuments', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->get()
            ->map(function ($user) use ($company) {
                $grouped = $user->archiveDocuments
                    ->where('company_id', $company->id)
                    ->groupBy(fn($doc) => optional($doc->accountVoucherInfo->VoucherType)->voucher_type_title ?? 'Unknown');

                return [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'document_counts' => $grouped->map->count()
                ];
            });
        }
        else
        {
            return User::with(['archiveDocuments.accountVoucherInfo.VoucherType'])
            ->whereHas('archiveDocuments', function ($query) use ($start, $end, $company) {
                $query->whereBetween('created_at', [$start, $end])
                    ->where('company_id', $company->id);
            })
            ->get()
            ->map(function ($user) use ($start, $end, $company) {
                $grouped = $user->archiveDocuments
                    ->whereBetween('created_at', [$start, $end])
                    ->where('company_id', $company->id)
                    ->groupBy(fn($doc) => optional($doc->accountVoucherInfo->VoucherType)->voucher_type_title ?? 'Unknown');

                return [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'document_counts' => $grouped->map->count()
                ];
            });
        }

    }
}
