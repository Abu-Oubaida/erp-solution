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
                                        <th>Total Archive Used</th>
                                        <th>:</th>
                                        <td>{{ @$totalUsed }} GB</td>
                                    </tr>
                                    <tr>
                                        <th>Total Free Disk Space</th>
                                        <th>:</th>
                                        <td>{{ @$diskFree }} GB</td>
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
                                    <div id="chart-container" style="width: 250px; float:right">
                                        <canvas id="levelChart"
                                            style="width: 100% !important;height: 100% !important;"></canvas>

                                        <div class="progress" title="Total: {{ $diskTotal }} GB">
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                style="width: {!! $totalUsed !!}%"
                                                aria-valuenow="{!! $totalUsed !!}" aria-valuemin="0"
                                                aria-valuemax="{{ $diskTotal }}">
                                                Used ( {!! $totalUsed !!} GB )</div>
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {!! $diskFree !!}%"
                                                aria-valuenow="{!! $diskFree !!}" aria-valuemin="0"
                                                aria-valuemax="{{ $diskTotal }}">Free ( {!! $diskFree !!} GB )
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
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Primary Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View
                            Details</a>
                        <div class="small text-white"><svg class="svg-inline--fa fa-angle-right" aria-hidden="true"
                                focusable="false" data-prefix="fas" data-icon="angle-right" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z">
                                </path>
                            </svg><!-- <i class="fas fa-angle-right"></i> Font Awesome fontawesome.com -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('levelChart').getContext('2d');
        const levelChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Used", "Free"],
                datasets: [{
                    label: 'Disk Usage',
                    data: [{!! @$totalUsed !!}, {!! @$diskFree !!}],
                    backgroundColor: [
                        '#dc3545', // Used - Red
                        '#198754', // Free - Blue
                    ],
                    borderColor: [
                        '#dc3545', // Used - Red
                        '#198754', // Free - Blue
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
