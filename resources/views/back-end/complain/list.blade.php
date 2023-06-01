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
                    <a class="btn btn-success btn-sm" href="{{route('departmental.list.complain')}}"><i class="fas fa-list-ol"></i> Departmental List</a>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header text-capitalize">
                <svg class="svg-inline--fa fa-table me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="table" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M64 256V160H224v96H64zm0 64H224v96H64V320zm224 96V320H448v96H288zM448 256H288V160H448v96zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"></path></svg><!-- <i class="fas fa-table me-1"></i> Font Awesome fontawesome.com -->
                {{str_replace('.', ' ', \Route::currentRouteName())}}
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Submitted Date</th>
                        <th>Submitted By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Submitted Date</th>
                        <th>Submitted By</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @if(count($complains))
                        @php
                        $i=1;
                        @endphp
                        @foreach($complains as $c)
                            <tr>
                                <td>{!! $i++ !!}</td>
                                <td>{!! $c->title !!}</td>
                                <td>@if($c->status == 1) {!! '<span class="text-primary">Active</span>' !!} @elseif($c->status == 2) {!! '<strong class="text-warning">Processing</strong>' !!} @elseif($c->status == 3) {!! '<strong class="text-danger">Solved</strong>' !!}@elseif($c->status == 4) {!! 'Pending' !!} @elseif($c->status == 5) {!! 'Reject' !!} @elseif($c->status == 7) {!! 'Deleted' !!} @else {!! '<span class="text-success">Unknown</span>' !!} @endif</td>
                                <td>@if($c->priority == 1) {!! '<span class="text-primary">Normal</span>' !!} @elseif($c->priority == 2) {!! '<strong class="text-warning">Urgent</strong>' !!} @elseif($c->priority == 3) {!! '<strong class="text-danger">Very Urgent</strong>' !!} @else {!! '<span class="text-success">Lazy</span>' !!} @endif</td>
                                <td>{!! $c->created_at !!}</td>
                                <td>{!! $c->name !!}</td>
                                <td>
                                    <a href="{{route('single.view.complain',['complainID'=>\Illuminate\Support\Facades\Crypt::encryptString($c->id)])}}" class="btn btn-sm btn-primary" title="View"><i class='fas fa-eye'></i></a>
{{--                                    <button type="button" class="btn btn-sm btn-primary" value="{!! $c->id !!}" onclick="return Obj.receivedComplainAction(this,'complain-action')" data-bs-toggle="modal" data-bs-target="#complain-action"> View </button>--}}
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
