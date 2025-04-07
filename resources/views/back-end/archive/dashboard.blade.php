@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="" class="text-capitalize text-chl">Dashboard</a></li>
        </ol>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-hard-drive"></i> Storage Information Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Module Name</th>
                                        <th>:</th>
                                        <td>Data Archive</td>
                                    </tr>
                                    <tr>
                                        <th>Total Disk Space</th>
                                        <th>:</th>
                                        <td>{{ @$diskTotal }} GB</td>
                                    </tr>
                                    <tr>
                                        <th>Total Free Space</th>
                                        <th>:</th>
                                        <td>{{ @$diskFree }} GB</td>
                                    </tr>
                                    <tr>
                                        <th>Total Used Space</th>
                                        <th>:</th>
                                        <td>{{ @$totalUsed }} GB</td>
                                    </tr>
                                    <tr>
                                        <th>Archived</th>
                                        <th>:</th>
                                        <td>{{ @$archiveUsed }} GB</td>
                                    </tr>
                                    <tr>
                                        <th>Other</th>
                                        <th>:</th>
                                        <td>{{ @$otherUsed }} GB</td>
                                    </tr>
                                    <tr>
                                        <th>Total Data Type</th>
                                        <th>:</th>
                                        <td>{{ @$dataTypeCount }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Document</th>
                                        <th>:</th>
                                        <td>{{ @$archiveDocumentCount }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col">
                                <div class="align-items-center">
                                    <div id="chart-container" style="width: 300px; float:right">
                                        <canvas id="levelChart"
                                            style="width: 100% !important;height: 100% !important;"></canvas>

                                        <div class="progress" title="Total: {{ $diskTotal }} GB">
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                style="width: {!! $totalUsed !!}%"
                                                aria-valuenow="{!! $totalUsed !!}" aria-valuemin="0"
                                                aria-valuemax="{{ $diskTotal }}" title="Total Used ({!! $totalUsed !!}) GB">
                                                Used ( {!! $totalUsed !!} GB )</div>
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {!! $diskFree !!}%"
                                                aria-valuenow="{!! $diskFree !!}" aria-valuemin="0"
                                                aria-valuemax="{{ $diskTotal }}" title="Free Spase ({!! $diskFree !!}) GB">Free ( {!! $diskFree !!} GB )
                                            </div>
                                        </div>
                                        <p class="text-center"><strong>Total Storage:</strong> {{ $diskTotal }} GB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-hard-drive"></i> Storage Information Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            @if(isset($dataTypes) && count($dataTypes))
                @php
                    $colors = ['info', 'success', 'primary', 'warning','secondary'];
                    $prevColor = null;
                    $prevPrevColor = null;
                @endphp
                @foreach($dataTypes as $dataType)
                    @php
                        // Filter available colors
                        $availableColors = array_filter($colors, function ($color) use ($prevColor, $prevPrevColor) {
                            return $color !== $prevColor && $color !== $prevPrevColor;
                        });

                        $availableColors = array_values($availableColors);
                        $chosenColor = $availableColors[array_rand($availableColors)];

                        // Update tracking colors
                        $prevPrevColor = $prevColor;
                        $prevColor = $chosenColor;
                    @endphp
                    <div class="col">
                        <div class="card bg-{{ $chosenColor }} text-white mb-4">
                            <div class="card-header">
                                <h4 class="text-capitalize"><i class="fas fa-file"></i> {!! $dataType['voucher_type_title'] !!}</h4>
                            </div>
                            <div class="card-body text-capitalize">
                                <div class="row">
                                    <div class="col text-start">
                                        <h2 class="text-chl">{!! $dataType['archive_document_infos_count'] !!}</h2>
                                        <a class="small text-white stretched-link text-decoration-none" href="#">Reference <i class="fas fa-angle-right"></i></a>
                                    </div>
                                    <div class="col text-end">
                                        <h2 class="text-chl">{!! $dataType['archive_documents_count'] !!}</h2>
                                        <a class="small text-white stretched-link text-decoration-none" href="#">Documents <i class="fas fa-angle-right"></i></a>
                                    </div>
                                </div>
{{--                                <ul>--}}
{{--                                    <li>Unique Archive Reference <h5 class="display-inline float-end"></h5></li>--}}
{{--                                    <li>Data type wise document count <h5 class="display-inline float-end">{!! $dataType['archive_documents_count'] !!}</h5></li>--}}
{{--                                </ul>--}}
                            </div>
{{--                            <div class="card-footer d-flex align-items-center justify-content-between">--}}
{{--                                <a class="small text-white stretched-link" href="#">View--}}
{{--                                    Details</a>--}}
{{--                                <div class="small text-white">--}}
{{--                                    <i class="fas fa-angle-right"></i>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('levelChart').getContext('2d');
        const levelChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Free", 'Archive', 'Other',],
                datasets: [{
                    label: 'Disk Usage',
                    data: [{!! @$diskFree !!}, {!! @$archiveUsed !!}, {!! @$otherUsed !!},],
                    backgroundColor: [
                        '#198754', // Free - Blue
                        '#da3545', // Archive Used
                        '#8a8a8a', // Other Used
                    ],
                    borderColor: [
                        '#198754', // Free - Blue
                        '#da3545', // Archive Used
                        '#8a8a8a', // Other Used
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@stop
