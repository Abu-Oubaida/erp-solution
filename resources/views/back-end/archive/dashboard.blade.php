@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="" class="text-capitalize text-chl">Dashboard</a></li>
        </ol>
@if(@$companies)
    @if(count($companies) > 1)
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                @foreach($companies as $company)
                    <button class="nav-link text-uppercase @if(\Illuminate\Support\Facades\Auth::user()->companyInfo()->id == $company->id)active @endif" id="nav-company-tab" data-bs-toggle="tab" data-bs-target="#nav-company" type="button" role="tab" aria-controls="nav-home" aria-selected="true" onclick="return Obj.companyWiseArchiveDashboard(this,{!! $company->id !!},'nav-company')">
                        {!! $company->company_code !!}</button>
                @endforeach
            </div>
        </nav>
    @endif
@endif
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-company" role="tabpanel" aria-labelledby="nav-company-tab">
                <script>
                    (function ($) {
                        $(document).ready(function () {
                            Obj.companyWiseArchiveDashboard(this,{!! \Illuminate\Support\Facades\Auth::user()->companyInfo()->id !!},'nav-company')
                        });
                    }(jQuery))
                </script>
            </div>
        </div>
    </div>

@stop
