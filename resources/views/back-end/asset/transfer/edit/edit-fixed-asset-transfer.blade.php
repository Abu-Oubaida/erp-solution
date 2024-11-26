@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i
                class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('fixed.asset.transfer')}}" class="text-capitalize text-chl">Fixed Transfer</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#"
                           class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-capitalize"><i
                                        class="fas fa-edit"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}
                                </h5>
                            </div>
                            <div class="col">
                                <a href="#documents" class="float-end btn-sm btn btn-outline-info"><i
                                        class="fas fa-file-edit"></i> Edit Documents ({!! count($item->documents) !!}
                                    )</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form
                            action="{!! route('edit.fixed.asset.transfer',['ftid'=>\Illuminate\Support\Facades\Crypt::encryptString($item->id)]) !!}"
                            enctype="multipart/form-data" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col">
                                    <label for="from_company">From Company<span class="text-danger">*</span></label>
                                    <select id="from_company" name="from_company_id" class="select-search cursor-pointer"
                                            onchange="return Obj.userWiseCompanyProjectPermissions(this,{!! Auth::user()->id !!},'from_project')">
                                        <option value="">Pick options...</option>
                                        @if(count(@$companies))
                                            @foreach($companies as $c)
                                                <option @if(isset($item) && $item->from_company_id == $c->id)selected
                                                        @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="project">From Project Name<span class="text-danger">*</span></label>
                                    <select id="from_project" name="from_branch_id" class="select-search cursor-pointer">
                                        <option value="">Pick options...</option>
                                        @if(@$projects !== null)
                                            @foreach($projects as $p)
                                                <option @if(isset($item) && $item->from_project_id == $p->id)selected
                                                        @endif value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="company">To Company<span class="text-danger">*</span></label>
                                    <select id="to_company" name="to_company_id" class="select-search cursor-pointer"
                                            onchange="return Obj.userWiseCompanyProjectPermissions(this,{!! Auth::user()->id !!},'to_project')">
                                        <option value="">Pick options...</option>
                                        @if(count(@$companies))
                                            @foreach($companies as $c)
                                                <option @if(isset($item) && $item->to_company_id == $c->id)selected
                                                        @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="project">To Project Name<span class="text-danger">*</span></label>
                                    <select id="to_project" name="to_branch_id" class="select-search cursor-pointer">
                                        <option value="">Pick options...</option>
                                        @if(@$projects !== null)
                                            @foreach($projects as $p)
                                                <option @if(isset($item) && $item->to_project_id == $p->id)selected
                                                        @endif value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="ref">GP Reference No<span class="text-danger">*</span></label>
                                    <input class="form-control" value="{!! @$item->reference !!}" name="gp_reference" type="text"
                                           placeholder="GP Reference number..." id="gp_ref">
                                </div>
                                <div class="col">
                                    <label for="gp_date">GP Date<span class="text-danger">*</span></label>
                                    <input class="form-control"
                                           value="{!! @$item->date ?date('Y-m-d',strtotime(@$item->date)):'' !!}"
                                           name="gp_date" type="date" id="gp_date">
                                </div>

                                <div class="col-md-7">
                                    <div>
                                        <label for="remarks">Narration:</label>
                                        <textarea class="form-control form-control-sm" id="narration"
                                                  name="narration">{!! (@$item->narration)?$item->narration:'' !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="attachments">Attachments: ({!! count(@$item->documents) !!})</label>
                                    <input class="form-control" type="file" name="attachments[]" id="attachments"
                                           multiple>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-chl-outline float-end mt-4"><i
                                            class="fas fa-save"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="transfer-body">
            @include('back-end.asset.transfer.edit._edit_transfer_body_part_1')
        </div>
    </div>
    <div class="modal modal-xl fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="v_document_name"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="fixed-asset-spec-edit">
                    {{--                <div class="row" >--}}
                    {{--                </div>--}}
                    {{--                <div id="documentPreview"></div>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{--                <button type="button" class="btn btn-primary">Understood</button>--}}
                </div>
                <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
                    <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
                </div>
            </div>
        </div>
    </div>
@stop

