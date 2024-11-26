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
                        <th colspan="2">From,</th>
                    </tr>
                    <tr>
                        <th>Company</th>
                        <th>: </th>
                        <td class="underline">{!! $transferData->companyFrom->company_name !!}</td>
                    </tr>
                    <tr>
                        <th>Project</th>
                        <th>: </th>
                        <td class="underline">{!! $transferData->branchFrom->branch_name !!}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>: </th>
                        <td class="underline">{!! $transferData->branchFrom->address !!}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6 text-center" style="width: 35%;">
                <!-- Logo or Company Name -->
{{--                @if(!empty(@$transferData->companyFrom->logo))--}}
{{--                    <img src="{{url($transferData->companyFrom->logo)}}" alt="Company Logo" style="max-height: 80px;">--}}
{{--                @else--}}
{{--                    <h3 class="text-center text-chl text-uppercase">{!! $transferData->companyFrom->company_name !!}</h3>--}}
{{--                @endif--}}
{{--                <p>{!! @$transferData->companyFrom->location !!}</p>--}}
                <h3>Fixed Asset Gate Pass Document</h3>
            </div>
            <div class="col-md-3" style="width: 30%;">
                <table class="table table-borderless text-left table-sm m-0 p-0">
                    <!-- Header Information -->
                    <tr>
                        <th colspan="2">To,</th>
                    </tr>
                    <tr>
                        <th>Company</th>
                        <th>: </th>
                        <td class="underline">{!! $transferData->companyTo->company_name !!}</td>
                    </tr>
                    <tr>
                        <th>Project</th>
                        <th>: </th>
                        <td class="underline">{!! $transferData->branchTo->branch_name !!}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>: </th>
                        <td class="underline">{!! $transferData->branchTo->address !!}</td>
                    </tr>
                </table>
            </div>
        </header>
        <div class="row">
            <div class="col-md-12 print-body">
                <div class="row">
                    <div class="col "><b>Reference:</b> {!! $transferData->reference !!}</div>
                    <div class="col text-center"><b>Status:</b> @if($transferData->status == 0) Processing @else Stage-{!! $transferData->status !!} @endif</div>
                    <div class="col text-end"><b>Date:</b> {!! date('d-M-Y', strtotime($transferData->date)) !!}</div>
                </div>
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
                    @if(isset($transferData->specifications) && count($transferData->specifications))
                        @php
                            $n=1;
                            $total_qnt = 0;
                            $total = 0;
                        @endphp
                        @foreach($transferData->specifications as $opm)
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
                            <th colspan="6">Total Amount:</th>
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
                <div class="mb-5"><strong>Narration:</strong> <span>{!! $transferData->narration !!}</span></div>
            </div>
        </div>
        <footer class="row print-footer" style="bottom: 0;width: 100%;">
            <div class="col-md-12">
                <div class="row">
                    <!-- Footer Signatories -->
                    <div class="col text-center">
                        {!! (isset($transferData->createdBy->name))?$transferData->createdBy->name:'-' !!}
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Created BY
                    </div>
                    <div class="col text-center">
                        {!! (isset($transferData->created_at))?date('F d,Y',strtotime($transferData->created_at)):'-' !!}
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Created Date
                    </div>
                    <div class="col text-center">
                        {!! (isset($transferData->updatedBy->name))?$transferData->updatedBy->name:'-' !!}
                        <hr style="margin: 0;padding: 0;border-style: dotted">
                        Updated BY
                    </div>

                    <div class="col text-center">
                        {!! (isset($transferData->updated_at))?date('F d,Y',strtotime($transferData->updated_at)):'-' !!}
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
                <small class="text-end float-end">This is system generated document, no need to signature.</small>
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
