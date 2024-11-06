<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{str_replace('-', ' | ', config('app.name'))}}</title>
    <link rel="icon" href="{{url("image/logo/chl_logo.png")}}">
    <x-back-end._header-link/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        body {
            padding: 0.5cm;
            font-family: "Times New Roman";
            font-size: 12px;
        }
        table {
            width: 100%;
            table-layout: auto;
            border-collapse: collapse;
        }
        .container-fluid {
            width: 100%;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .print-header, .print-footer {
                /*position: fixed;*/
                width: 100%;
                left: 0;
                background: white;
                box-sizing: border-box;
            }

            .print-header {
                top: 0;
            }

            .print-body {
                box-sizing: border-box;
                margin-top: 10px;
            }


            .print-footer {
                margin-top: 100px;
                bottom: 0;
                page-break-before: auto;
            }
        }
    </style>
</head>
<body class="sb-nav-fixed">
<div id="layoutSidenav" class="bg-image-dashboard">
    <div class="container-fluid" style="width: 100%; padding: 1cm;">
        <header class="row print-header" style="margin-bottom: 15px;">
            <div class="col-md-3" style="width: 35%;">
                <table class="table table-borderless text-left table-sm m-0 p-0">
                    <!-- Header Information -->
                    <tr>
                        <th>Project Name</th>
                        <th>: </th>
                        <td class="underline">{!! $withRefData->branch->branch_name !!}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>: </th>
                        <td class="underline">{!! $withRefData->branch->address !!}</td>
                    </tr>
                    <tr>
                        <th style="width: 32%">Purpose of use</th>
                        <th>: </th>
                        <td class="underline">{!! $withRefData->purpose !!}</td>
                    </tr>
                    <tr>
                        <th>Remarks</th>
                        <th>: </th>
                        <td class="underline">{!! $withRefData->remarks !!}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6 text-center" style="width: 35%;">
                <!-- Logo or Company Name -->
                @if(!empty(@$withRefData->company->logo))
                    <img src="{{url($withRefData->company->logo)}}" alt="Company Logo" style="max-height: 80px;">
                @else
                    <h3 class="text-center text-chl text-uppercase">{!! $withRefData->company->company_name !!}</h3>
                @endif
                <p>{!! @$withRefData->company->location !!}</p>
                <h5>Fixed Asset Distribution With Reference</h5>
            </div>
            <div class="col-md-3" style="width: 30%;">
                <table class="table table-borderless text-left table-sm m-0 p-0">
                    <!-- Reference Information -->
                    <tr>
                        <th>Reference Type</th>
                        <th>: </th>
                        <td class="underline">{!! $withRefData->refType->name !!}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>: </th>
                        <td class="underline">{!! date('F d,Y',strtotime($withRefData->date)) !!}</td>
                    </tr>
                    <tr>
                        <th>Reference No</th>
                        <th>: </th>
                        <td class="underline">{!! $withRefData->references !!}</td>
                    </tr>
                </table>
            </div>
        </header>
        <div class="row">
            <div class="col-md-12 print-body">
                <table class="table table-bordered" style="width: 100%; table-layout: auto">
                    <thead>
                    <tr>
                        <th>SL.</th>
                        <th>Date</th>
                        <th>Materials (Code)</th>
                        <th>Specifications</th>
                        <th>Unit</th>
                        <th>Rate</th>
                        <th>Qty.</th>
                        <th>Price</th>
                        <th>Purpose</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($withRefData->withSpecifications) && count($withRefData->withSpecifications))
                        @php
                            $n=1;
                            $total_qnt = 0;
                            $total = 0;
                        @endphp
                        @foreach($withRefData->withSpecifications as $opm)
                            <tr style="page-break-inside: avoid;">
                                <td>{!! $n++ !!}</td>
                                <td>{!! date('d-M-Y', strtotime($opm->date)) !!}</td>
                                <td>{!! $opm->asset->materials_name !!} ({!! $opm->asset->recourse_code !!})</td>
                                <td>{!! $opm->specification->specification !!}</td>
                                <td>{!! $opm->asset->unit !!}</td>
                                <td>{!! $opm->rate !!}</td>
                                <td>{!! $opm->qty !!}</td>
                                <td>{!! (float)($opm->qty * $opm->rate) !!}</td>
                                <td>{!! (isset($opm->purpose))?$opm->purpose:'' !!}</td>
                                <td>{!! (isset($opm->remarks))?$opm->remarks:'' !!}</td>
                            </tr>
                            @php
                                $total_qnt += $opm->qty;
                                $total += (float)($opm->qty * $opm->rate);
                            @endphp
                        @endforeach

                        <tr>
                            <th colspan="6">Total Price Amount:</th>
                            <th>{!! $total_qnt !!}/=</th>
                            <th>{!! $total !!}/=</th>
                            <th colspan="2"></th>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center" colspan="10">Not Found!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="mb-5"><strong>Narration:</strong> <span>{!! $withRefData->narration !!}</span></div>
            </div>
        </div>
        <footer class="row print-footer" style="bottom: 0;width: 100%;">
            <div class="col-md-12">
                <div class="row">
                    <!-- Footer Signatories -->
                    <div class="col text-center">
                        {!! (isset($withRefData->createdBy->name))?$withRefData->createdBy->name:'-' !!}
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Created BY
                    </div>
                    <div class="col text-center">
                        {!! (isset($withRefData->created_at))?date('F d,Y',strtotime($withRefData->created_at)):'-' !!}
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Created Date
                    </div>
                    <div class="col text-center">
                        {!! (isset($withRefData->updatedBy->name))?$withRefData->updatedBy->name:'-' !!}
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Updated BY
                    </div>

                    <div class="col text-center">
                        {!! (isset($withRefData->updated_at))?date('F d,Y',strtotime($withRefData->updated_at)):'-' !!}
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Updated Date
                    </div>
                    <div class="col text-center">
                        <br>
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Approve 1
                    </div>
                    <div class="col text-center">
                        <br>
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Approve 2
                    </div>
                    <div class="col text-center">
                        <br>
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Approve 3
                    </div>
                </div>
                <hr>
                <small class="text-capitalize text-secondary">Print source: {{str_replace('-', ' | ', config('app.name'))}}, | {{str_replace('.', ' ', \Route::currentRouteName())}},   {{\Illuminate\Support\Facades\Auth::user()->name}},  {!! date('F d, Y h:i:s A',strtotime(now())) !!}</small>
            </div>
        </footer>
    </div>
</div>
<x-back-end._footer-script/>
<script>
    $(document).ready(function (){
        window.print()
    })
</script>
</body>
</html>
