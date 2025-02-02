@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
{{--        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
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
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-capitalize"><i class="fas fa-key"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
{{--                        <form action="{!! route('permission.input') !!}" method="POST">--}}
{{--                            @csrf--}}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="permission_parent">Permission Parent<span class="text-danger">*</span></label>
                                        <select class="select-search" name="permission_parent" id="permission_parent" required>
                                            <option value="">Pick options...</option>
                                        @foreach($permissions as $p)
                                            <option value="{!! $p->id !!}" @if(old('permission_parent') == $p->id) selected @endif>{!! $p->display_name !!}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="permission_name" name="permission_name" type="text" placeholder="Enter Permission Name" value="{{old('permission_name')}}" required/>
                                        <label for="permission_name">Permission_Name<span class="text-danger">*</span></label>
                                        <sub>Only acceptable character are " a-z and 0-9_ " </sub>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="permission_display_name" name="permission_display_name" type="text" placeholder="Enter Permission Display Name" value="{{old('permission_display_name')}}" required/>
                                        <label for="permission_display_name">Display Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="description" name="description"> {!! old('description') !!}</textarea>
                                        <label for="remarks">Description</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="is_parent" class="mt-3">
                                        <input type="checkbox" name="is_parent" id="is_parent" value="0"> Is parent ?
                                    </label>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-floating mt-3 float-end">
                                        <button type="submit" value="Add" class="btn btn-chl-outline" name="submit" onclick="return Obj.permissionInput(this)"><i class="fas fa-save"></i> Add</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <strong>For uploading your permission.csv/xlsx is here. <a href="{{route("permission.export.prototype")}}">Prototype here</a></strong>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="permission_input_file">
                                        <label class="input-group-text" for="permission_input_file"><i class="fas fa-upload"></i> &nbsp; Upload</label>
                                    </div>
                                </div>
                            </div>
{{--                        </form>--}}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="text-capitalize"><i class="fas fa-file-contract"></i> Permission list</h3>
                    </div>
                    <div class="card-body" id="permission-list">
                        @include('back-end.programmer._permission-list')
                    </div>
                </div>
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
                    <div id="data-table" class="table"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location.reload()">Close</button>
                    <button type="button" class="btn btn-primary" onclick="return confirm('Are you sure?'), Obj.permissionExcelFileSubmit(this)">Save changes</button>
                </div>
            </div>
        </div>
        <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
            <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
        </div>
    </div>
@stop

