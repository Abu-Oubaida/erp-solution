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

        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                        </div>
                        <form action="{{ route('add.fixed.asset.specification') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="recourse_code" list="recourse_code_list" name="recourse_code" type="text" placeholder="Enter Recourse Code or Name" value="{{old('recourse_code')}}" required/>
                                        <datalist id="recourse_code_list">
                                            @if(count($fixed_assets))
                                                @foreach($fixed_assets as $fx)
                                                    <option value="{!! $fx->recourse_code !!}">{!! $fx->materials_name !!}</option>
                                                @endforeach
                                            @endif
                                        </datalist>
                                        <label for="recourse_code">Materials Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                                            <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="spec"  name="spec" type="text" placeholder="Enter Specification" value="{{old('spec')}}" required/>
                                        <label for="spec">Materials Specification<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <input type="submit" value="+ Add" class="btn btn-chl-outline" name="submit" >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">Fixed Asset Materials Specification List</h3>
                        </div>
                        <table class="table table-sm" id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Recourse Code</th>
                                <th>Materials</th>
                                <th>Specification</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot></tfoot>
                            <tbody>
                            @if(count($fixed_asset_specifications))
                                @php
                                    $no= 1;
                                @endphp
                                @foreach($fixed_asset_specifications as $fas)
{{--                                    <tr class="{!! (isset($fixed_asset) && $fas->id == $fixed_asset->id)?'text-primary':'' !!}">--}}
                                    <tr>
                                        <td>{!! $no++ !!}</td>
                                        <td>{!! $fas->fixed_asset->recourse_code !!}</td>
                                        <td>{!! $fas->fixed_asset->materials_name !!}</td>
                                        <td>{!! $fas->specification !!}</td>
                                        <td>@if($fas->status==1) <span class='badge bg-success'> Active</span> @else <span class='badge bg-danger'>Inactive </span>@endif</td>

                                        <td>
                                            <div class="text-center">
                                                @if(auth()->user()->hasPermission('fixed_asset_edit'))
                                                    <a href="{!! route('fixed.asset.edit',['fixedAssetID'=>\Illuminate\Support\Facades\Crypt::encryptString($fas->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                                                @endif
                                                @if(auth()->user()->hasPermission('fixed_asset_delete'))
                                                    <form action="{{route('fixed.asset.delete')}}" class="display-inline" method="post">
                                                        @method('delete')
                                                        @csrf
                                                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($fas->id) !!}">
                                                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center text-danger">Not found!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

