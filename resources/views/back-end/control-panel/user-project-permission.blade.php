@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{route('control.panel')}}" class="text-capitalize text-chl">Control Panel</a>
        </li>
        <li class="breadcrumb-item"><a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-9 mb-1">
                            <label for="user">User Name<span class="text-danger">*</span></label>
                            <select id="user" name="user" class="select-search cursor-pointer">
                                <option value="">Pick options...</option>
                                @if(count($employees))
                                    @foreach($employees as $e)
                                        <option @if(Request::get('e') !== null && Request::get('e') == $e->id)selected @endif value="{!! $e->id !!}">{!! $e->name !!} (ID: {!! $e->employee_id !!}, {!! $e->getDesignation->title !!}, {!! $e->getDepartment->dept_name !!})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3 mt-4">
                            <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.userProjectPermissionSearch(this)">
                                <i class="fa fa-search"></i> search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row" id="user-project-permission-add-list">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

