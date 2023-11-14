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
            /*font-family: Arial, sans-serif;*/
            padding-top: 3cm;
            font-family: "Times New Roman";
        }
        @font-face {
            font-family: "Times New Roman";
        }

    </style>
</head>
<body class="sb-nav-fixed">
<div id="layoutSidenav" class="bg-image-dashboard">
    <div class="container-fluid">
{{--        <div class="letterhead">--}}
{{--            <!-- Add content for letterhead/header here -->--}}
{{--            <img src="{{url("image/logo/chl_logo.png")}}" alt="Company Logo" style="max-height: 100px;">--}}
{{--            <p>House #15, Road #13/A, Dhanmondi R/A, Dhaka</p>--}}
{{--        </div>--}}
        <div class="row">
            <h3 class="text-center text-uppercase">To whom it may concern</h3>
            <br>
            <p>{!! date('F d,Y',strtotime(now())) !!}</p>
            <br>
            <div class="col-md-12">
                <div class="row">
                    <p class="text-justify" style="text-align: justify">This is certify that <b>{!! $data->userInfo->name !!},</b> {!! $data->userInfo->getDesignation->title !!} of Credence Housing Ltd. of House #15, Road #13/A, Dhanmondi R/A, Dhaka has been pain a sum of Taka. {!! ($total = $data->basic + $data->house_rent + $data->conveyance + $data->medical_allowance + $data->festival_bonus + $data->others) !!} ({!! amountToWords(($total),'Taka','Paisa','BDT') !!}) as salary & allowances during the financial year <b>{!! date('F-Y', strtotime($data->financial_yer_from)) !!} - {!! date('F-Y', strtotime($data->financial_yer_to)) !!},</b> corresponding the assessment year <b>{!! date('F-Y', strtotime($data->financial_yer_to)) !!} - {!! date('F-Y', strtotime($data->financial_yer_to.' +1 year')) !!} as per the following breakup:</b></p>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th>Basic</th>
                                <td>=</td>
                                <td>Tk. {!! $data->basic !!}/=</td>
                            </tr>
                            <tr>
                                <th>House Rent</th>
                                <td>=</td>
                                <td>Tk. {!! $data->house_rent !!}/=</td>
                            </tr>
                            <tr>
                                <th>Conveyance</th>
                                <td>=</td>
                                <td>Tk. {!! $data->conveyance !!}/=</td>
                            </tr>
                            <tr>
                                <th>Medical Allowance</th>
                                <td>=</td>
                                <td>Tk. {!! $data->medical_allowance !!}/=</td>
                            </tr>
                            <tr>
                                <th>Festival Bonus</th>
                                <td>=</td>
                                <td>Tk. {!! $data->festival_bonus !!}/=</td>
                            </tr>
                            <tr>
                                <th>Others</th>
                                <td>=</td>
                                <td>Tk. {!! $data->others !!}/=</td>
                            </tr>
                            <tr class="border-top border-bottom ">
                                <th>Total</th>
                                <th>=</th>
                                <th>Tk. {!! $total !!}/=</th>
                            </tr>
                        </table>
                        <strong>({!! amountToWords(($total),'Taka','Paisa','BDT') !!})</strong>
                    </div>
                    @if($transactions && count($transactions))
                        @php($tTotal =0)
                        @php($tType='')
                        @foreach($transactions as $t)
                            @php($tTotal += $t->amount)
                            @php($tType .= $t->type.', ')
                        @endforeach
                        <div class="col-md-12">
                            <br>
                            <div class="row">
                                <p>We further certify that <b>Tk. {!! $tTotal !!}/=</b> only has been deducted as advance income tax against remuneration and deposited the same to the <b>{!! $tType !!}</b> through challan nos. is as follows:</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                @foreach($transactions as $t)
                                <tr>
                                    <td>Challan no: {!! $t->challan_no !!}</td>
                                    <td>Dated: {!! date('d-m-Y',strtotime($t->dated)) !!}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <br>
                        <div class="row">
                            <p>For <b>Credence Housing Ltd.</b></p>
                        </div>
                        <br><br>
                        Md. Towhid Al Islam
                        <br><b>(AGM, Accounts & Finance)</b>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<x-back-end._footer-script/>
{{--<script src="https://cdn.jsdelivr.net/npm/@canvas-fonts/times-new-roman@1.0.4/index.min.js"></script>--}}
</body>
</html>
