@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
                <a href="{{\Request::path()}}" class="text-capitalize text-chl">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
            </li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <svg class="svg-inline--fa fa-table me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="table" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M64 256V160H224v96H64zm0 64H224v96H64V320zm224 96V320H448v96H288zM448 256H288V160H448v96zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"></path></svg><!-- <i class="fas fa-table me-1"></i> Font Awesome fontawesome.com -->
                DataTable Example
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
                        <th>Forward To </th>
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
                        <th>Forward To</th>
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
                                <td>@if($c->status == 1) {!! '<span class="text-primary">Active</span>' !!} @elseif($c->status == 2) {!! '<strong class="text-warning">Processing</strong>' !!} @elseif($c->status == 3) {!! '<strong class="text-danger">Solved</strong>' !!}@elseif($c->status == 4) {!! 'Pending' !!} @elseif($c->status == 5) {!! 'Reject' !!} @else {!! '<span class="text-success">Unknown</span>' !!} @endif</td>
                                <td>@if($c->priority == 1) {!! '<span class="text-primary">Normal</span>' !!} @elseif($c->priority == 2) {!! '<strong class="text-warning">Urgent</strong>' !!} @elseif($c->priority == 3) {!! '<strong class="text-danger">Very Urgent</strong>' !!} @else {!! '<span class="text-success">Lazy</span>' !!} @endif</td>
                                <td>{!! $c->created_at !!}</td>
                                <td>@if($c->name) {!! $c->name !!}@else{!! '<span class="text-info">Undefined</span>' !!} @endif</td>
                                <td><a href="{{route('single.view.complain',['complainID'=>\Illuminate\Support\Facades\Crypt::encryptString($c->id)])}}" class="btn btn-sm btn-primary"> View </a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-danger">Not found!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
