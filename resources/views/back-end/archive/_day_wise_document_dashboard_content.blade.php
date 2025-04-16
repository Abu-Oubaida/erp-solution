<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include the chartjs-plugin-datalabels -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    Chart.register(ChartDataLabels);
</script>
<div id="">
    @if ($lastday_uploaded_data_by_users)
        @php
            $user_wise_l = [];
            $user_wise_dat = [];

            // Get document types dynamically
            $documentTypes = collect();

            foreach ($lastday_uploaded_data_by_users as $item) {
                $documentTypes = $documentTypes->merge(array_keys($item['document_counts']->toArray()));
            }

            $documentTypes = $documentTypes->unique()->values();

            foreach ($documentTypes as $docType) {
                $user_wise_dat[] = [
                    'label' => $docType,
                    'data' => collect($lastday_uploaded_data_by_users)->map(function ($item) use ($docType) {
                        return $item['document_counts']->get($docType, 0);
                    }),
                    'stack' => 'Stack 0',
                ];
            }
            // Calculate max count for any user's total documents
//                                $totalDocumentCount = collect($today_uploaded_data_by_users)->map(function ($item) {
//                                    return $item['document_counts']->sum();
//                                })->max() + 40; // Add extra 2
$user_wise_l = collect($lastday_uploaded_data_by_users)->pluck('user_name');
        @endphp
        <canvas id="lastday_uploaded_data_by_users" width="400" height="200"></canvas>
    @endif
</div>

@if ($lastday_uploaded_data_by_users)
    <script>
        var lastday_ctx = document.getElementById('lastday_uploaded_data_by_users').getContext('2d');

        var lastday_data = {
            labels: {!! json_encode($user_wise_l) !!},
            datasets: {!! json_encode($user_wise_dat) !!}
        };

        // Calculate the sum of values per user (level-wise) and find the highest total sum
        var userTotalSums = lastday_data.labels.map((label, index) => {
            return lastday_data.datasets.reduce((sum, dataset) => {
                return sum + (dataset.data[index] || 0); // Add values for this user across all datasets
            }, 0);
        });

        // Find the maximum sum and add 2 for the max value
        var stepSize = 10;
        // Find the maximum sum and add 2 for the max value
        var maxTotal = Math.round(Math.max(...userTotalSums) / stepSize) * stepSize + stepSize


        var config = {
            type: 'bar',
            data: lastday_data,
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

        new Chart(lastday_ctx, config);
    </script>
@endif
