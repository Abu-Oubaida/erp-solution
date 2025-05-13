@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
{{--    <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-bell"></i>
                    Notifications
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <form method="GET" class="d-flex align-items-center">
                                    @foreach(request()->except('per_page') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <label class="me-2 mb-0">Items per page:</label>
                                    <select name="per_page" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                                        @foreach([10, 25, 50, 100, 'all'] as $size)
                                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                                {{ $size === 'all' ? 'All' : $size }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="col-4">
                            <select class="form-control form-control-sm" name="" id="">
                                <option value="">All</option>
                                <option value="">Read</option>
                                <option value="">Unread</option>
                            </select>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <tbody>
                        @if(isset($notifications) && count($notifications))
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>
                                        <input class="mt-3" type="checkbox" name="" id="">
                                    </td>
                                    <td>
                                        @if($notification->read_at) <small class="badge bg-success" style=" font-size: 8px">Read</small>@else <small class="badge bg-danger" style=" font-size: 8px">Unread</small>@endif
                                        <small class="float-end mt-2" style=" font-size: 10px" >{!! date('d-M-y h:i:s A',strtotime($notification->created_at)) !!}</small>
                                        <a href="{{ route("notification.view",["n"=>$notification->id]) }}" class="d-block text-decoration-none text-black" style="text-align: justify">{{ $notification->data['message'] }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12">
                            {{-- Pagination --}}
                            @if ($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-center" id="pagination">
                                    <style>
                                        #pagination .pagination {
                                            --bs-pagination-padding-x: 0.5rem;
                                            --bs-pagination-padding-y: 0.1rem;
                                            --bs-pagination-font-size: 0.875rem; /* Bootstrap's default sm size */
                                            font-size: 8px;
                                        }
                                    </style>
                                    {{ $notifications->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

