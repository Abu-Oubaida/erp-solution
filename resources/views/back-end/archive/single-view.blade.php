@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
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
{{--        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>--}}
        <div class="row">
            <div class="col-md-12">
                <h1 class="modal-title fs-5 d-inline-block" id="v_document_name"><b>File Name</b>: {!! $document->document !!}</h1>
            </div>
            <div class="col">
                <span><strong>Reference Number</strong>:{!! $document->accountVoucherInfo->voucher_number !!}</span>
            </div>
            <div class="col">
                <span class="float-end"><strong>Type</strong>:{!! $document->accountVoucherInfo->VoucherType->voucher_type_title !!}</span>
            </div>
            <div class="col-md-12">
                <strong>Remarks:</strong> {!! $document->accountVoucherInfo->remarks !!}
            </div>
            @php
                // Extract the file extension
                $fileExtension = pathinfo($document->filepath.$document->document,PATHINFO_EXTENSION);
            @endphp

            @if (in_array($fileExtension, ['pdf', 'doc', 'txt','jpg','jpeg','png','JPG'])) <!-- Add your allowed extensions -->
            <!-- Embed the PDF using iframe -->
            <embed src="{{ asset('storage/archive_data/'.$document->filepath.$document->document) }}#toolbar=0" style="width:100%; height:100vh;" />
            {{--                                                                @elseif (in_array($fileExtension, ['dox', 'excel', 'xlsx', 'txt','docx']))--}}
            {{--                                                                    <iframe src="https://docs.google.com/viewer?url={{ url($d->filepath.$d->document) }}&embedded=true" style="width: 100%; height: 600px;"></iframe>--}}
            @elseif ($fileExtension === ['mp4'])
                <video controls style="width: 80%">
                    <source src="{{ asset('storage/archive_data/'.$document->filepath.$document->document) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @else
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>
                        <a class="btn btn-success text-center" href="{{ asset('storage/archive_data/'.$document->filepath.$document->document) }}" download>
                            Click To Download
                        </a>
                    </div>
                </div>

            @endif
{{--            <embed src="{{ url($document->filepath.$document->document) }}#toolbar=0" style="width:100%; height:1000px;" />--}}
        </div>
    </div>
@stop
