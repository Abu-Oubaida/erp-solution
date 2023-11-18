@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-6">
                <div class="float-start">
                    <strong>For Upload the file_name.xlsx use this section. <a href="{!! route('export.user.salary.prototype') !!}">Prototype is here</a> Please flowing the exact format</strong>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" id="file_upload">
                        <label class="input-group-text" for="file_upload"><i class="fa-solid fa-cloud-arrow-up"></i> &nbsp; Upload</label>
                    </div>
{{--                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">--}}
{{--                        Launch demo modal--}}
{{--                    </button>--}}
                </div>
            </div>
            <div class="col-md-6">
                <div class="float-end">
                    @if(auth()->user()->hasPermission('salary_certificate_list'))
                        <a class="btn btn-success btn-sm" href="{{route("salary.certificate.list")}}"><i class="fas fa-list-check"></i> List View</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}} individual</h3>
                </div>
                <form action="{{ route('input.salary.certificate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="officer" name="officer" type="text" placeholder="Enter officer name" value="{{old('officer')}}" list="officer-list" required/>
                                        <datalist id="officer-list">
                                            @if(count($users))
                                                @foreach($users as $user)
                                                    <option value="{!! $user->name !!}">{!! $user->employee_id !!}, {!! $user->getDepartment->dept_name !!} , {!! $user->getBranch->branch_name !!} </option>
                                                @endforeach
                                            @endif
                                        </datalist>
                                        <label for="officer">Officer Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="from" name="from" type="month" placeholder="Enter Financial Year From" value="{{old('from')}}" required/>
                                        <label for="voucher_date">Financial Year From <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="to" name="to" type="month" placeholder="Enter Financial Year To" value="{{old('to')}}" required/>
                                        <label for="to">Financial Year To <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="total" type="number" placeholder="Enter total salary" value="{{old('total')}}" title="Total salary amount" onchange="return Obj.salaryDistribute(this)"/>
                                        <label for="total">Total</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="basic" name="basic" type="number" placeholder="Enter basic salary" value="{{old('basic')}}" title="Basic salary amount" required />
                                        <label for="to">Basic<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="house_rent" name="house_rent" type="number" placeholder="Enter house rent" value="{{old('house_rent')}}" title="House Rent amount" required/>
                                        <label for="to">House Rent<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="conveyance" name="conveyance" type="number" placeholder="Enter conveyance" value="{{old('conveyance')}}" title="Conveyance amount" required/>
                                        <label for="to">Conveyance<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="medical" name="medical" type="number" placeholder="Enter medical amount" value="{{old('medical')}}" title="Medical allowance" required/>
                                        <label for="medical"> Medical Allow. <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="bonus" name="bonus" type="number" placeholder="Enter basic salary" value="{{old('bonus')}}" title="Festival bonus amount" required/>
                                        <label for="bonus">Festival Bonus<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="others" name="others" type="number" placeholder="Enter basic salary" value="{{old('others')}}"/>
                                        <label for="others" >Others</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="remarks" >Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Remarks"> </textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3 float-end">
                                <button type="submit" class="btn btn-chl-outline"><i class="fa-regular fa-floppy-disk"></i> Save</button>
{{--                                <input type="submit" value="Save" class="btn btn-chl-outline" name="submit" >--}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}} List (your entered only)</h3>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('back-end.account-voucher.salary._input_certificate_list')
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
                    <table id="data-table" class="table"></table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location.reload()">Close</button>
                    <button type="button" class="btn btn-primary" onclick="return confirm('Are you sure?'), Obj.employeeDataSubmit(this)">Save changes</button>
                </div>
            </div>
        </div>
        <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
            <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
        </div>
    </div>

@stop

