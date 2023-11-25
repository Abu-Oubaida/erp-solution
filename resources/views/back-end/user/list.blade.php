@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <div class="float-end">
                    <a class="btn btn-success btn-sm" href="{{route('add.user')}}"><i class="fa-solid fa-circle-plus"></i> Add User</a>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header text-capitalize">
                <svg class="svg-inline--fa fa-table me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="table" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M64 256V160H224v96H64zm0 64H224v96H64V320zm224 96V320H448v96H288zM448 256H288V160H448v96zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"></path></svg><!-- <i class="fas fa-table me-1"></i> Font Awesome fontawesome.com -->
                {{str_replace('.', ' ', \Route::currentRouteName())}}
            </div>
            <div class="card-body">
                <table id="permissionstable">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Joining Date</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Dept.</th>
                        <th>Designation</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Joining Date</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Dept.</th>
                        <th>Designation</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @if(count($users))
                        @php
                        $i=1;
                        @endphp
                        @foreach($users as $u)
                            <tr>
                                <td>{!! $i++ !!}</td>
                                <td>{!! $u->employee_id !!}</td>
                                <td>{!! $u->name !!}</td>
                                <td>{!! date('d-M-y',strtotime($u->joining_date)) !!}</td>
                                <td>{!! $u->phone !!}</td>
                                <td>{!! $u->email !!}</td>
                                <td>{!! $u->getDepartment->dept_name !!}</td>
                                <td>{!! ($u->getDesignation != null)?$u->getDesignation->title:"N/A" !!}</td>
                                <td>@foreach ($u->roles as $role)
                                        {{ $role->display_name }}
                                    @endforeach</td>
                                <td>@if($u->status == 1) {!! '<span class="text-primary">Active</span>' !!}  @else {!! '<span class="text-danger">Inactive</span>' !!} @endif</td>
                                <td class="">
                                    <a href="{{route('user.single.view',["userID"=>\Illuminate\Support\Facades\Crypt::encryptString($u->id)])}}" class="text-primary" title="View"><i class='fas fa-eye'></i></a>
                                    <a href="{{route('user.edit',["userID"=>\Illuminate\Support\Facades\Crypt::encryptString($u->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                                    <form action="{{route('user.delete')}}" class="display-inline" method="post">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($u->id) !!}">
                                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the user?')"><i class="fas fa-trash"></i></button>
                                    </form>

{{--                                    <button type="button" class="btn btn-sm btn-primary" value="{!! $u->id !!}" onclick="return Obj.receivedComplainAction(this,'complain-action')" data-bs-toggle="modal" data-bs-target="#complain-action"> View </button>--}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-danger">Not found!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="modal fade" id="complain-action" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
