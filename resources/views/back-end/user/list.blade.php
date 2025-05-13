@extends('layouts.back-end.main')
@section('mainContent')
    <style>
        #userTable tr th input{
            font-size: 12px!important;
        }
    </style>
    <div class="container-fluid px-4">
        <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-danger btn-sm float-end"><i
                class="fas fa-chevron-left"></i> Go Back</a>
        {{--        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1> --}}
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#"
                            class="text-capitalize">{{ str_replace('.', ' ', \Route::currentRouteName()) }}</a>
                    </li>
                </ol>
            </div>
        </div>
        @php
            use Illuminate\Support\Facades\Cache;
        @endphp
        <div class="row">
            @if(auth()->user()->hasPermission('add_user'))
                <div class="col">
                    <div class="float-start">
                        <strong>For uploading your employee.xlsx use this section. <a href="{!! route('export.employee.data.prototype') !!}">Prototype is here</a> Please flowing the exact format</strong>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="employee_file_upload">
                            <label class="input-group-text" for="employee_file_upload"><i class="fa-solid fa-cloud-arrow-up"></i> &nbsp; Upload</label>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal modal-xl fade" id="myModal" tabindex="-1" aria-labelledby="userDataModelLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-fullscreen-custom">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="userDataModelLabel">Title</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location.reload()"></button>
                                </div>
                                <div class="modal-body">
                                    <table id="data-table" class="table"></table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location.reload()">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="return confirm('Are you sure?'), Obj.userExcelFileSubmit(this)">Save changes</button>
                                </div>
                            </div>
                        </div>
                        <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
                            <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
                        </div>
                    </div>
                </div>
            @endif
            @if(auth()->user()->isSystemSuperAdmin() || auth()->user()->companyWiseRoleName() == 'superadmin')
                <div class="col">
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        This page data is being fetched from cache. <a href="{!! route('clear.cache') !!}">Clear Cache</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
        <div class="card mb-4">
            <div class="card-header text-capitalize">
                <div class="row">
                    <div class="col-md-8">
                        <h3>
                            <i class="fa-solid fa-users"></i>
                            {{ str_replace('.', ' ', \Route::currentRouteName()) }}
                        </h3>
                    </div>
                    <div class="col-md-4">
                        @if(auth()->user()->hasPermission('add_user'))
                            <div class="float-end m-1 mt-2">
                                <a class="btn btn-success btn-sm" href="{{ route('add.user') }}" target="_blank"><i
                                        class="fa-solid fa-circle-plus"></i> Add User</a>
                            </div>
                        @endif
                        @if (auth()->user()->hasPermission('add_user_project_permission'))
                        <div class="float-end m-1 mt-2">
                            <a class="btn btn-info btn-sm" target="_blank" href="{{ route('user.project.permission') }}"><i class="fa-solid fa-users-gear"></i> Project Permission</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="userTable" class="display" style="width: 100%; font-size: 12px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Company (Role)</th>
                            <th>Branch</th>
                            <th>Joining Date</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Dept.</th>
                            <th>Designation</th>
                            {{--                        <th>Role</th> --}}
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Company (Role)</th>
                            <th>Branch</th>
                            <th>Joining Date</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Dept.</th>
                            <th>Designation</th>
                            {{--                        <th>Role</th> --}}
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @if (count($users))
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($users as $u)
                                <tr>
                                    <td>{!! $i++ !!}</td>
                                    <td>{!! $u->employee_id !!}</td>
                                    <td>{!! $u->name !!}</td>
                                    <td class="text-center text-capitalize">
                                        <span class="badge bg-success" title="Mother Company">{!! isset($u->getCompany->company_name) ? @$u->getCompany->company_name : 'N/A' !!}
                                            ({!! @$u->roles->first()->display_name !!})</span>
                                        @foreach (@$u->companyPermissions as $cp)
                                            <span class="badge bg-info" title="Permission Company">{!! $cp->company->company_code !!}
                                                ({!! $cp->userRole->display_name !!})</span>
                                        @endforeach
                                    </td>
                                    <td>{!! isset($u->branch->branch_name) ? $u->branch->branch_name : 'N/A' !!}</td>
                                    <td>{!! date('d-M-y', strtotime($u->joining_date)) !!}</td>
                                    <td>{!! $u->phone !!}</td>
                                    <td>{!! $u->email !!}</td>
                                    <td>{!! isset($u->department->dept_name) ? $u->department->dept_name : 'N/A' !!}</td>
                                    <td>{!! isset($u->designation) ? $u->designation->title : 'N/A' !!}</td>
                                    {{--                                <td> --}}
                                    {{--                                    @foreach ($u->roles as $role) --}}
                                    {{--                                        <span class="badge bg-success">{{ $role->display_name }}</span> --}}
                                    {{--                                    @endforeach --}}
                                    {{--                                    @foreach ($u->companyWiseRoles as $cwr) --}}
                                    {{--                                        <span class="badge bg-info">{{ $cwr->display_name }}</span> --}}
                                    {{--                                    @endforeach --}}
                                    {{--                                </td> --}}
                                    <td>
                                        @if ($u->status == 1)
                                            {!! '<span class="badge bg-primary">Active</span>' !!}
                                        @else
                                            {!! '<span class="badge bg-danger">Inactive</span>' !!}
                                        @endif
                                    </td>
                                    <td class="">
                                        @if (auth()->user()->hasPermission('view_user'))
                                            <a href="{{ route('user.single.view', ['userID' => \Illuminate\Support\Facades\Crypt::encryptString($u->id)]) }}"
                                                class="text-primary" title="View"><i class='fas fa-eye'></i></a>
                                        @endif
                                        @if (auth()->user()->hasPermission('delete_user'))
                                            <form action="{{ route('user.delete') }}" class="display-inline" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($u->id) !!}">
                                                <button class="text-danger border-0 inline-block bg-none"
                                                    onclick="@if ($u->status == 5) return confirm('Attention! ⚠️⚠️⚠️⚠️⚠️⚠️ Are you sure permanently delete the user?') @else return confirm('Are you sure delete the user?') @endif"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                            <div class="dropdown">
                                            <button class="border-0 inline-block bg-none dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                More
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if (auth()->user()->hasPermission('edit_user'))
                                                    <li><a class="dropdown-item" type="button"
                                                            href="{{ route('user.edit', ['userID' => \Illuminate\Support\Facades\Crypt::encryptString($u->id)]) }}"
                                                            title="Edit" target="_blank"><i class='fas fa-edit'></i> Edit
                                                            User</a></li>
                                                @endif

                                                @if (@$u->roles()->first()->name !== 'systemsuperadmin')
                                                    <li><a href="" class="dropdown-item" type="button"
                                                            target="_blank"><i class="fa-solid fa-shield-halved"></i>
                                                            Company Permission</a></li>
                                                    @if (auth()->user()->hasPermission('user_screen_permission'))
                                                        <li><a class="dropdown-item" type="button"
                                                                href="{{ route('user.screen.permission', ['userID' => \Illuminate\Support\Facades\Crypt::encryptString($u->id)]) }}"
                                                                title="User Screen Permission" target="_blank"><i
                                                                    class="fa-solid fa-user"></i> User Screen Permission</a>
                                                        </li>
                                                    @endif

                                                    @if (auth()->user()->hasPermission('file_manager_permission'))
                                                        <li><a class="dropdown-item" type="button"
                                                                href="{{ route('file.manager.permission', ['userID' => \Illuminate\Support\Facades\Crypt::encryptString($u->id)]) }}"
                                                                title="File Manager Permission" target="_blank"><i
                                                                    class="fa-solid fa-file"></i> File Manager
                                                                Permission</a></li>
                                                    @endif

                                                        @if (auth()->user()->hasPermission('add_archive_data_type_user_permission') && auth()->user()->haspermission('archive_data_type_list'))
                                                            <li><a class="dropdown-item" type="button"
                                                                   href="{!! route('archive.data.type.list') !!}"
                                                                   title="Archive Data Type Permission" target="_blank"><i class="fas fa-receipt" aria-hidden="true"></i> Archive Data Type Permission</a>
                                                            </li>
                                                        @endif
                                                @endif
                                            </ul>
                                        </div>
                                        {{--                                    <button type="button" class="btn btn-sm btn-primary" value="{!! $u->id !!}" onclick="return Obj.receivedComplainAction(this,'complain-action')" data-bs-toggle="modal" data-bs-target="#complain-action"> View </button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="13" class="text-center text-danger">Not found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="modal fade" id="complain-action" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Understood</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
