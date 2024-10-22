@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
{{--        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
        <div class="row">
            <div class="col-md-4">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-8">
                <div class="float-end">
                    <strong>For uploading your employee.xlsx use this section. <a href="{!! route('export.employee.data.prototype') !!}">Prototype is here</a> Please flowing the exact format</strong>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" id="employee_file_upload">
                        <label class="input-group-text" for="employee_file_upload"><i class="fa-solid fa-cloud-arrow-up"></i> &nbsp; Upload</label>
                    </div>
                    {{--                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">--}}
                    {{--                        Launch demo modal--}}
                    {{--                    </button>--}}
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="text-capitalize"><i class="fas fa-user-plus"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-info btn-sm float-end m-1 me-0" href="{{route('user.list')}}"><i class="fas fa-list-check"></i>  User List</a>
                        <a class="btn btn-success btn-sm float-end m-1 me-0" href="{{route('add.department')}}"><i class="fa-solid fa-building-user"></i> Add Department</a>
                        <a class="btn btn-success btn-sm float-end m-1 me-0" href="{{route('add.designation')}}"><i class="fa-solid fa-user-tag"></i> Add Designation</a>
                        <a class="btn btn-success btn-sm float-end m-1 me-0" href="{{route('add.branch')}}"><i class="fa-solid fa-code-branch"></i> Add Branch</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('add.user') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="company">Company Name <span class="text-danger">*</span></label>
                                <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.changeUserCompany(this)">
                                    <option value="">Pick options...</option>
                                    @if(isset($companies) || (count($companies) > 0))
                                        @foreach($companies as $c)
                                            <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="name" name="name" type="text" placeholder="Enter full name" value="{{old('name')}}"/>
                                <label for="name">Full name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="phone" name="phone" type="number" placeholder="Enter phone number" value="{{old('phone')}}"/>
                                <label for="phone">Phone number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="Enter email address" value="{{old('email')}}"/>
                                <label for="email">Email address <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="joining_date" name="joining_date" type="date" placeholder="Joining Date" value="{!! old('joining_date') !!}" onchange="return Obj.makeEmployeeID('dept_menu','company','joining_date')"/>
                                <label for="joining_date">Joining Date <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="branch">Branch Name <span class="text-danger">*</span></label>
                                <select class="text-capitalize select-search" id="branch_menu" name="branch">
                                    <option value="">Pick options...</option>
{{--                                    @if(isset($branches) || (count($branches) > 0))--}}
{{--                                        @foreach($branches as $branch)--}}
{{--                                            <option value="{{$branch->id}}" @if(old('branch') == $branch->id) selected @endif>{{$branch->branch_name}} ({!! $branch->company->company_name !!})</option>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="department">Department <span class="text-danger">*</span></label>
                            <select class="select-search" id="dept_menu" name="dept" onchange="return Obj.makeEmployeeID('dept_menu','company','joining_date')">
                                <option value="">Pick options...</option>
{{--                                @if(isset($depts) || (count($depts) > 0))--}}
{{--                                    @foreach($depts as $d)--}}
{{--                                        <option value="{{$d->id}}" @if(old('dept') == $d->id) selected @endif>{{$d->dept_name}} ({!! $d->company->company_name !!})</option>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                            <input class="form-control" id="employee_id" name="employee_id" type="text" placeholder="System automatically assigned it" value="{{old('employee_id')}}" readonly/>
                            <input type="hidden" name="employee_id_hidden" id="employee_id_hide" value="{!! old('employee_id_hidden') !!}">
                        </div>
                        <div class="col-md-3">
                            <label for="designation">Designation <span class="text-danger">*</span></label>
                            <select class="select-search" id="designation_menu" name="designation">
                                <option value="">Pick options...</option>
{{--                                @if(isset($designations) || (count($designations) > 0))--}}
{{--                                    @foreach($designations as $d)--}}
{{--                                        <option value="{{$d->id}}" @if(old('designation') == $d->id) selected @endif>{{$d->title}} ({!! $d->company->company_name !!})</option>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="roll">User Role <span class="text-danger">*</span></label>
                            <select class="select-search" id="role_menu" name="role">
                                <option value="">Pick options...</option>
{{--                                @if(isset($roles) || (count($roles) > 0))--}}
{{--                                    @foreach($roles as $r)--}}
{{--                                        <option value="{{$r->id}}" @if(old('role') == $r->id) selected @endif>{{$r->display_name}} ({!! $d->company->company_name !!})</option>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="pass" name="password" type="password" placeholder="Enter password" value=""/>
                                <label for="password">New password <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="conform" name="password_confirmation" type="password" placeholder="Enter conform password" value=""/>
                                <label for="conform">Conform password <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating mb-3 float-end">
                                <button type="submit" class="btn btn-chl-outline" name="submit" > <i class="fas fa-save"></i> Save User</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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

@stop

