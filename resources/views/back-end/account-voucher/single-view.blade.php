@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-5">
                <h1 class="modal-title fs-5 d-inline-block" id="v_document_name">{!! $document->document !!}</h1>
            </div>
            <div class="col-md-7">
                <span><strong>Reference Number</strong>:{!! $document->accountVoucherInfo->voucher_number !!}</span>
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
            <embed src="{{ url($document->filepath.$document->document) }}#toolbar=0" style="width:100%; height:100vh;" />
            {{--                                                                @elseif (in_array($fileExtension, ['dox', 'excel', 'xlsx', 'txt','docx']))--}}
            {{--                                                                    <iframe src="https://docs.google.com/viewer?url={{ url($d->filepath.$d->document) }}&embedded=true" style="width: 100%; height: 600px;"></iframe>--}}
            @elseif ($fileExtension === ['mp4'])
                <video controls style="width: 80%">
                    <source src="{{ url($document->filepath.$document->document) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @else
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>
                        <a class="btn btn-success text-center" href="{{ url($document->filepath.$document->document) }}" download>
                            Click To Download
                        </a>
                    </div>
                </div>

            @endif
{{--            <embed src="{{ url($document->filepath.$document->document) }}#toolbar=0" style="width:100%; height:1000px;" />--}}
        </div>
    </div>
@stop
