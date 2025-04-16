<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include the chartjs-plugin-datalabels -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    Chart.register(ChartDataLabels);
</script>
@if (auth()->user()->hasPermission('archive_chart_view'))
    <div class="row">
        <div class="col-xl-6 col-md-12 mb-2">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-hard-drive"></i> Storage Information Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <table class="table table-bordered mt-3" style="width: 100%">
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
                                <tr>
                                    <th>Total Unique Reference</th>
                                    <th>:</th>
                                    <td>{{ @$accountVoucherInfosCount }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col">
                            <div class="align-items-center">
                                <div id="chart-container" style="width: 95%; float:right">
                                    <canvas id="levelChart"></canvas>

                                    <div class="progress" title="Total: {{ $diskTotal }} GB">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {!! $totalUsed !!}%; background: rgb(255, 99, 132)"
                                            aria-valuenow="{!! $totalUsed !!}" aria-valuemin="0"
                                            aria-valuemax="{{ $diskTotal }}"
                                            title="Total Used ({!! $totalUsed !!}) GB">
                                            Used ( {!! $totalUsed !!} GB )</div>
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {!! $diskFree !!}%; background: rgb(54, 162, 235)"
                                            aria-valuenow="{!! $diskFree !!}" aria-valuemin="0"
                                            aria-valuemax="{{ $diskTotal }}"
                                            title="Free Spase ({!! $diskFree !!}) GB">Free (
                                            {!! $diskFree !!} GB )
                                        </div>
                                    </div>
                                    <p class="text-center"><strong>Total Storage:</strong> {{ $diskTotal }} GB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-chart-column"></i> Last 7 Days Uploaded Documents</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div style="width: 100%">
                            <canvas id="documentTypeChart" width="600" class="mt-4"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="row mt-2">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="text-capitalize"><i class="fas fa-user"></i> User wise uploaded documents</h3>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="userWiseUpload" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#date-wise-document"
                            type="button" role="tab" aria-controls="date-wise-document" aria-selected="true"
                            onclick="return Obj.dateWiseUserUplodedDocuments(this,{!! @$company_id !!},'today','date-wise-document')">Today</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " data-bs-toggle="tab" data-bs-target="#date-wise-document"
                            type="button" role="tab" aria-controls="date-wise-document" aria-selected="false"
                            onclick="return Obj.dateWiseUserUplodedDocuments(this,{!! @$company_id !!},'yesterday','date-wise-document')">Last
                            Day</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#date-wise-document"
                            type="button" role="tab" aria-controls="date-wise-document" aria-selected="false"
                            onclick="return Obj.dateWiseUserUplodedDocuments(this,{!! @$company_id !!},'last_7_days','date-wise-document')">Last
                            7 Days</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#date-wise-document"
                            type="button" role="tab" aria-controls="date-wise-document" aria-selected="false"
                            onclick="return Obj.dateWiseUserUplodedDocuments(this,{!! @$company_id !!},'last_30_days','date-wise-document')">Last
                            30 Days</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#date-wise-document"
                            type="button" role="tab" aria-controls="date-wise-document" aria-selected="false"
                            onclick="return Obj.dateWiseUserUplodedDocuments(this,{!! @$company_id !!},'last_year','date-wise-document')">Last
                            365 Days</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#date-wise-document"
                            type="button" role="tab" aria-controls="date-wise-document" aria-selected="false"
                                onclick="return Obj.dateWiseUserUplodedDocuments(this,{!! @$company_id !!},'all','date-wise-document')">ALL</button>
                    </li>
                </ul>
                <div class="tab-content" id="userWiseUploadTabContent">
                    <div class="tab-pane fade show active" id="date-wise-document" role="tabpanel"
                        aria-labelledby="home-tab">
                        <div id="">
                            @if ($today_uploaded_data_by_users)
                                @php
                                    $user_wise_labels = [];
                                    $user_wise_datasets = [];

                                    // Get document types dynamically
                                    $documentTypes = collect();

                                    foreach ($today_uploaded_data_by_users as $item) {
                                        $documentTypes = $documentTypes->merge(
                                            array_keys($item['document_counts']->toArray()),
                                        );
                                    }

                                    $documentTypes = $documentTypes->unique()->values();

                                    foreach ($documentTypes as $docType) {
                                        $user_wise_datasets[] = [
                                            'label' => $docType,
                                            'data' => collect($today_uploaded_data_by_users)->map(function ($item) use (
                                                $docType,
                                            ) {
                                                return $item['document_counts']->get($docType, 0);
                                            }),
                                            'stack' => 'Stack 0',
                                        ];
                                    }
                                    // Calculate max count for any user's total documents
//                                $totalDocumentCount = collect($today_uploaded_data_by_users)->map(function ($item) {
//                                    return $item['document_counts']->sum();
//                                })->max() + 40; // Add extra 2
$user_wise_labels = collect($today_uploaded_data_by_users)->pluck('user_name');
                                @endphp
                                <canvas id="user-wise-data-uploaded-today" width="400" height="200"></canvas>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    @if (isset($dataTypes) && count($dataTypes))
        @php
            $colors = ['info-light', 'success-light', 'primary-light', 'warning-light', 'secondary-light'];
            $prevColor = null;
            $prevPrevColor = null;
        @endphp
        @foreach ($dataTypes as $dataType)
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
            <div class="col-md-3">
                <div class="card bg-{{ $chosenColor }} sub-card  mb-4">
                    <div class="card-header">
                        <h5 class="text-capitalize"><i class="fas fa-file-lines"></i> {!! $dataType['voucher_type_title'] !!}
                            ({!! $dataType['company_name'] !!})
                        </h5>
                    </div>
                    <div class="card-body text-capitalize">
                        <div class="row">
                            <div class="col text-start">
                                <h2 class="card-heading-text-color">{!! $dataType['archive_document_infos_count'] !!}</h2>
                                <a class="small  stretched-link text-decoration-none" href="{!! route('uploaded.archive.list.quick', ['c' => $dataType['company_id'], 't' => $dataType['id']]) !!}"
                                    target="_blank">Reference <i class="fas fa-angle-right"></i></a>
                            </div>
                            <div class="col text-end">
                                <h2 class="card-heading-text-color">{!! $dataType['archive_documents_count'] !!}</h2>
                                <a class="small  stretched-link text-decoration-none" href="{!! route('uploaded.archive.list.quick', ['c' => $dataType['company_id'], 't' => $dataType['id']]) !!}"
                                    target="_blank">Documents <i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@if ($otherUsed)
    <script>
        var ctx = document.getElementById('levelChart').getContext('2d');
        var levelChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Free", 'Archive', 'Other', ],
                datasets: [{
                    label: 'Disk Usage',
                    data: [{!! @$diskFree !!}, {!! @$archiveUsed !!}, {!! @$otherUsed !!}, ],
                    backgroundColor: [
                        'rgb(54, 162, 235)', // Free - Blue
                        'rgb(255, 99, 132)', // Archive Used
                        'rgb(255, 205, 86)' // Other Used
                    ],
                    borderColor: [
                        'rgb(54, 162, 235)', // Free - Blue
                        'rgb(255, 99, 132)', // Archive Used
                        'rgb(255, 205, 86)' // Other Used
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    datalabels: {
                        display: false
                    }
                }
            }
        })
    </script>
@endif
@if ($documentCountsPerDay)
    <script>
        var documentData = {!! json_encode($documentCountsPerDay) !!};
        var dataValues = Object.values(documentData).map(Number);
        var stepSize = 10;
        var maxYValue = Math.ceil(Math.max(...dataValues) / stepSize) * stepSize + stepSize;

        var barCtx = document.getElementById('documentTypeChart').getContext('2d');

        var documentTypeChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: Object.keys({!! json_encode($documentCountsPerDay) !!}),
                datasets: [{
                    label: 'Documents Uploaded Last 7 Days',
                    data: Object.values({!! json_encode($documentCountsPerDay) !!}).map(Number),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(255, 205, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(201, 203, 207, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(255, 205, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(201, 203, 207, 0.5)'
                    ],
                    borderWidth: 1,
                    barPercentage: 0.5, //Decrease width of bars, 50% of the available space for each bar
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Hides the legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y} documents`; // Custom tooltip text
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        // color: '#000000', // Light gray color for data labels
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value, context) {
                            return value; // Simply displays the value
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: maxYValue, // Y-axis range from 0 to max
                        ticks: {
                            stepSize: stepSize // Increase by 10, i.e., 0, 10, 20
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // Enables the ChartDataLabels plugin
        });
    </script>
@endif
@if ($today_uploaded_data_by_users)
    <script>
        var today_ctx = document.getElementById('user-wise-data-uploaded-today').getContext('2d');

        var today_data = {
            labels: {!! json_encode($user_wise_labels) !!},
            datasets: {!! json_encode($user_wise_datasets) !!}
        };

        // Calculate the sum of values per user (level-wise) and find the highest total sum
        var userTotalSums = today_data.labels.map((label, index) => {
            return today_data.datasets.reduce((sum, dataset) => {
                return sum + (dataset.data[index] || 0); // Add values for this user across all datasets
            }, 0);
        });

        var stepSize = 10;
        // Find the maximum sum and add 2 for the max value
        var maxTotal = Math.round(Math.max(...userTotalSums) / stepSize) * stepSize + stepSize


        var config = {
            type: 'bar',
            data: today_data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false,
                        text: 'Document Counts by User'
                    },
                    // Add the datalabels plugin to show user-wise total on each bar
                    datalabels: {
                        display: true,
                        // color: 'black', // Adjust text color
                        anchor: 'end', // Position the text at the top of the bars
                        align: 'top', // Align text above the bars
                        font: {
                            weight: 'bold',
                            size: 12, // Adjust font size
                        },
                        formatter: function(value, context) {
                            if (context.datasetIndex === 0) { // Ensure labels appear only once
                                return userTotalSums[context.dataIndex];
                            }
                            return ""; // Hide for other datasets
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            // align: 'start', // Align label text left
                        },
                        offset: true, // Remove center spacing
                        grid: {
                            offset: false
                        },
                        categoryPercentage: 0.5,
                        barPercentage: 0.5,
                        // padding: 20,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        min: 0,
                        max: maxTotal, // Use the calculated max + 2
                        ticks: {
                            stepSize: stepSize // Or 2/5/10 as per your preferred spacing
                        }
                    }
                },
                datasets: {
                    bar: {
                        barThickness: 40, // Make bars thinner
                    }
                }
            },
        };

        new Chart(today_ctx, config);
    </script>
@endif
