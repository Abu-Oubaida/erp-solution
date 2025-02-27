@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
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
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize"> <i class="fa-solid fa-file-circle-plus"></i> {{str_replace('add','create',str_replace('.', ' ', \Route::currentRouteName()))}}</h3>
                            </div>
                            <div class="col">
                                <a class="btn btn-primary btn-sm float-end mt-1 mb-1" style="margin-left: 10px" href=""><i class="fa-solid fa-paper-plane"></i>  Sent List</a>
                                <a class="btn btn-success btn-sm float-end mt-1 mb-1 mr-1" href=""><i class="fa-solid fa-inbox"></i>  Received List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('document.requisition.add') }}" method="POST">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseUsersForReq(this,'user')">
                                            <option value="">--select a option--</option>
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-1">
                                    <label for="user">Users Name<span class="text-danger">*</span></label>
                                    <select id="user" name="users[]" class="select-search cursor-pointer" onchange="" multiple>
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="deadline">Deadline<span class="text-danger">*</span></label>
                                        <input class="form-control" name="deadline" id="deadline" type="date" value="{!! old("deadline") !!}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="d_count">Number of Need Document</label>
                                        <input class="form-control" value="{!! old('d_count') !!}" name="d_count" id="d_count" type="number">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    @if(old('d_count'))
                                        <button class="btn btn-outline-danger float-end mt-4" onclick="Obj.document_field_operation(this)"><i class='fa-solid fa-clock-rotate-left'></i> Reset</button>
                                    @else
                                        <a class="btn btn-outline-primary float-end mt-4" onclick="Obj.document_field_operation(this)">Next <i class="fa-solid fa-arrow-right"></i></a>
                                    @endif
                                </div>
                            </div>
                            <div class="row" id="document_field">
                                @if($d_count=old('d_count'))
                                    @php($counter = 1)
                                    @while($d_count >= $counter)
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="d_title_{!! $counter !!}">Document {!! $counter !!} Title</label>
                                                <input class="form-control" name="d_title_{!! $counter !!}" id="d_title_{!! $counter !!}" type="text" value="{!! old("d_title_".$counter) !!}">
                                            </div>
                                        </div>
                                        @php($counter++)
                                    @endwhile

                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="subject">Subject<span class="text-danger">*</span></label>
                                        <input class="form-control" name="subject" id="subject" type="text" value="{!! old('subject') !!}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="details" id="details" cols="30" rows="10">{{old('details')}}</textarea>
                                        <label for="details">Details</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-chl float-end"><i class="fas fa-save"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3> <i class="fas fa-list"></i> My Requisition List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm" id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th title="Requisition date">Req. Date</th>
                                <th>Title</th>
                                <th>Sender</th>
                                <th>Receiver count</th>
                                <th>Deadline</th>
                                <th>Response need</th>
                                <th>Response count</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($documents))
                            @php($n = 1)
                            @foreach($documents as $d)
                                <tr>
                                    <td>{!! $n++ !!}</td>
                                    <td>{!! date('d-M-y', strtotime(@$d->created_at)) !!}</td>
                                    <td>{!! @$d->subject !!}</td>
                                    <td>{!! @$d->sender->name !!}</td>
                                    <td><a href="#" onclick="return Obj.requisitionDocumentUsersInfo(this)" ref="{!! @$d->id !!}">{!! count(@$d->receivers) !!}</a></td>
                                    <td>{!! date('d-M-y', strtotime(@$d->deadline)) !!}</td>
                                    <td><a href="#" onclick="return Obj.requisitionDocumentNeed(this)" ref="{!! @$d->id !!}">{!! count(@$d->attachmentInfos) !!}</a></td>
                                    <td>{!! $d->attachmentInfos->flatMap->attestedDocument->unique('id')->count() !!}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>

                        <!-- Modal For Receiver List -->
                        <div class="modal modal-xl fade" id="receiverList" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="receiverListLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="heading"></h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="documentPreview"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal-2 For Share -->
                        <div class="modal modal-xl fade" id="shareModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="shareModelLabel" aria-hidden="true">
                            <div class="modal-dialog" id="model_dialog">

                            </div>
                            <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
                                <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

