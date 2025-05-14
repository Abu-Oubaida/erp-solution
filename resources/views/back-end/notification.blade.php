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
            <div class="card mb-4" style="min-height: 800px">
                <div class="card-header">
                    <i class="fa-solid fa-envelopes-bulk"></i>
                    Notifications
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <input type="checkbox" name="" id="select_all">
                            <button class="badge bg-danger border-0 mb-2" onclick="return NotificationDelete(this)"><i class="fas fa-trash"></i> Delete</button>
                            <button class="badge bg-success border-0 mb-2" onclick="return NotificationReadUnread(this,1)"><i class="fa-solid fa-envelope-open"></i> Make Read</button>
                            <button class="badge bg-primary border-0 mb-2" onclick="return NotificationReadUnread(this,0)"><i class="fa-solid fa-envelope"></i> Make Unread</button>
                        </div>
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
                            <form method="GET">
                                <select class="form-control form-control-sm" onchange="this.form.submit()" name="status">
                                    <option value="all" @if(request()->get('status') == 'all') selected @endif>All</option>
                                    <option value="read" @if(request()->get('status') == 'read') selected @endif>Read</option>
                                    <option value="unread" @if(request()->get('status') == 'unread') selected @endif>Unread</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <table class="table table-hover table-responsive-sm">
                        <tbody>
                        @if(isset($notifications) && count($notifications))
                            @foreach($notifications as $notification)
                                <tr @if(request()->get('n') == $notification->id) class="bg-secondary-light" @elseif($notification->read_at == null) class="bg-primary-light" @endif>
                                    <td>
                                        <input class="mt-3 check-box" type="checkbox" value="{{ $notification->id }}">
                                    </td>
                                    <td>
                                        @if($notification->read_at) <small class="badge bg-success" style=" font-size: 8px"><i class="fa-solid fa-envelope-open"></i> Read</small>@else <small class="badge bg-primary" style=" font-size: 8px"><i class="fa-solid fa-envelope"></i> Unread</small>@endif
                                        <small class="float-end mt-2" style=" font-size: 10px" >{!! date('d-M-y h:i:s A',strtotime($notification->created_at)) !!}</small>
                                        <a href="{{ route("notification.view",["n"=>$notification->id]) }}" class="d-block text-decoration-none text-black" style="text-align: justify">{{ $notification->data['message']??$notification->data['title'] }} </a>
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
            <div class="card mb-4" style="min-height: 800px; background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 0.5rem; padding: 1rem">
                @if(@$notification_data)
                    <div class="car-header"><h3><i class="fa-solid fa-envelope-open"></i> Details View</h3></div>
                    <div class="card-body p-5 pt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{!! optional($notification_data->data)['title'] !!} @if($notification_data->read_at) <sup class="badge bg-success" style=" font-size: 12px"><i class="fa-solid fa-envelope-open"></i> Read</sup>@else <sup class="badge bg-danger" style=" font-size: 12px"><i class="fa-solid fa-envelope"></i> Unread</sup>@endif</h4>
                                <small>Read Time: {!! date('d-M-y h:i:s A',strtotime($notification_data->read_at)) !!}</small>
                                <small class="float-end">Received Time: {!! date('d-M-y h:i:s A',strtotime($notification_data->created_at)) !!}</small>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <h5>{!! optional($notification_data->data)['greeting'] !!}</h5>
                                @foreach(optional($notification_data->data)['body'] as $key => $value)
                                    <p>{!! $value !!}</p>
                                @endforeach
                                @if(optional($notification_data->data)['action_text'] && optional($notification_data->data)['action_url'])
                                <div class="d-flex justify-content-center">
                                    <a class="btn btn-outline-success" href="{!! optional($notification_data->data)['action_url'] !!}"><i class="fas fa-link"></i> {!! optional($notification_data->data)['action_text'] !!}</a>
                                </div>
                                @endif
                                @if(optional($notification_data->data)['footer'])
                                    <h5 class="">{!! optional($notification_data->data)['footer'] !!}</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>
<script>
    $("#select_all").change(function () {
        $(".check-box").prop("checked", this.checked);
    });
    function NotificationReadUnread(e,status)
    {
        let selected = [];
        $(".check-box:checked").each(function () {
            selected.push($(this).val());
        });
        if (selected.length === 0 && status.length === 0) {
            alert("Please select at least one record to delete.");
            return false
        }
        if(!confirm("Are you sure?"))
            return false
        const url = window.location.origin + sourceDir +"/notification-read-unread";
        $.ajax({
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            data: {
                notification_ids: selected,
                status: status,
            },
            success: function (response) {
                if(response.status === 'error')
                {
                    alert("Error: "+response.message)
                    return false
                }
                else {
                    alert("Success: "+response.message)
                    window.location.href = "{{ route('notification.view') }}";
                    return true
                }
            },
        })
    }
    function NotificationDelete(e)
    {
        let selected = [];
        $(".check-box:checked").each(function () {
            selected.push($(this).val());
        });
        if (selected.length === 0) {
            alert("Please select at least one record to delete.");
            return false
        }
        if(!confirm("Are you sure?"))
            return false
        const url = window.location.origin + sourceDir +"/notification-delete";
        $.ajax({
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            data: {
                notification_ids: selected
            },
            success: function (response) {
                if(response.status === 'error')
                {
                    alert("Error: "+response.message)
                    return false
                }
                else {
                    alert("Success: "+response.message)
                    window.location.href = "{{ route('notification.view') }}";
                    return true
                }
            },
        })
    }
</script>
@stop

