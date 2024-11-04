@extends('layout.app')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav style="--bs-breadcrumb-divider: '|';">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Experiment lists -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Lists Of Experiment</h5>

                                    <!-- Table with hoverable rows -->
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Eksperimen Name</th>
                                                <th scope="col">Domain Name</th>
                                                <th scope="col">Created By</th>
                                                <th scope="col">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($dataExperiment->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="text-center">Data Not Found</td>
                                                </tr>
                                            @else
                                                @foreach ($dataExperiment as $index => $experiment)
                                                    <tr class="experiment-row"
                                                        data-eksperimen-id="{{ $experiment['eksperimen_id'] }}">
                                                        <th scope="row">{{ $index + 1 }}</th> <!-- Row number -->
                                                        <td>{{ $experiment['eksperimen_name'] }}</td>
                                                        <td>{{ $experiment['domain_name'] }}</td>
                                                        <td>{{ $experiment['created_by'] }}</td>
                                                        <td>{{ $experiment['created_at'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <!-- End Table with hoverable rows -->
                                    <nav aria-label="Page navigation example" class="d-flex justify-content-end">
                                        <ul class="pagination">
                                            <li class="page-item {{ $dataExperiment->onFirstPage() ? 'disabled' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $dataExperiment->previousPageUrl() }}">Previous</a>
                                            </li>
                                            @for ($i = 1; $i <= $dataExperiment->lastPage(); $i++)
                                                <li
                                                    class="page-item {{ $i == $dataExperiment->currentPage() ? 'active' : '' }}">
                                                    <a class="page-link"
                                                        href="{{ $dataExperiment->url($i) }}">{{ $i }}</a>
                                                </li>
                                            @endfor
                                            <li class="page-item {{ $dataExperiment->hasMorePages() ? '' : 'disabled' }}">
                                                <a class="page-link" href="{{ $dataExperiment->nextPageUrl() }}">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div><!-- End Experiment lists -->

                        <!-- Reports -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Reports Chart</h5>

                                    <!-- Area Chart -->
                                    <div id="areaChart" class="text-center"></div>
                                    <div id="experimentViewChart" ></div>

                                    <script>
                                        $(document).ready(function() {
                                            let areaChart; // Declare variable to hold the chart instance
                                            let selectedRow; // Variable to keep track of the selected row

                                            // Function to select the first row on initial load
                                            function selectFirstRow() {
                                                const firstRow = $(".experiment-row").first(); // Get the first row
                                                if (firstRow.length) {
                                                    firstRow.addClass('selected-row'); // Add class to the first row
                                                    selectedRow = firstRow; // Update the selected row variable
                                                    const eksperimenId = firstRow.data('eksperimen-id'); // Get its eksperimenId
                                                    fetchData(eksperimenId); // Fetch data for the first row
                                                }
                                            }

                                            // Function to fetch data and render the chart
                                            function fetchData(eksperimenId) {
                                                // Fetch data from the API endpoint
                                                fetch(`/api/get-variant/${eksperimenId}`)
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        // Check if `variants` data is empty
                                                        if (!data || !data.variants || data.variants.length === 0) {
                                                            // Clear the charts and display "Data Not Found" message
                                                            if (areaChart) {
                                                                areaChart.destroy();
                                                            }
                                                            if (experimentViewChart) {
                                                                experimentViewChart.destroy();
                                                            }
                                                            $("#areaChart").html("<p>Data Not Found</p>");
                                                            $("#experimentViewChart").html(""); // Clear second chart container
                                                            return;
                                                        } else {
                                                            $("#areaChart").html('');
                                                            $("#experimentViewChart").html('');
                                                        }

                                                        // Prepare data for the variant chart
                                                        const variantSeriesData = [{
                                                                name: 'Button Clicks',
                                                                data: data.variants.map(variant => variant.button_click || 0)
                                                            },
                                                            {
                                                                name: 'Form Submits',
                                                                data: data.variants.map(variant => variant.form_submit || 0)
                                                            },
                                                            {
                                                                name: 'Variant Views (Variants)',
                                                                data: data.variants.map(variant => variant.view || 0)
                                                            }
                                                        ];

                                                        // Destroy existing charts if they exist
                                                        if (areaChart) {
                                                            areaChart.destroy();
                                                        }
                                                        if (experimentViewChart) {
                                                            experimentViewChart.destroy();
                                                        }

                                                        // Initialize and render the variant chart
                                                        areaChart = new ApexCharts(document.querySelector("#areaChart"), {
                                                            chart: {
                                                                type: 'bar',
                                                                toolbar: {
                                                                    show: false
                                                                }
                                                            },
                                                            plotOptions: {
                                                                bar: {
                                                                    horizontal: false,
                                                                    dataLabels: {
                                                                        position: 'top'
                                                                    }
                                                                }
                                                            },
                                                            series: variantSeriesData,
                                                            xaxis: {
                                                                categories: data.variants.map(variant => variant.variant_name),
                                                                title: {
                                                                    text: 'Variant Lists'
                                                                }
                                                            },
                                                            yaxis: {
                                                                title: {
                                                                    text: 'Count'
                                                                }
                                                            },
                                                            dataLabels: {
                                                                enabled: true,
                                                                formatter: (val) => val.toString()
                                                            },
                                                            tooltip: {
                                                                y: {
                                                                    formatter: (val) => `${val} interactions`
                                                                }
                                                            },
                                                            legend: {
                                                                position: 'right'
                                                            }
                                                        });

                                                        areaChart.render();

                                                        // Initialize and render the experiment view count chart
                                                        experimentViewChart = new ApexCharts(document.querySelector("#experimentViewChart"), {
                                                            chart: {
                                                                type: 'bar',
                                                                toolbar: {
                                                                    show: false
                                                                }
                                                            },
                                                            plotOptions: {
                                                                bar: {
                                                                    horizontal: true,
                                                                    dataLabels: {
                                                                        position: 'top'
                                                                    }
                                                                }
                                                            },
                                                            series: [{
                                                                name: 'Experiment View Count',
                                                                data: [data.experiment_view_count || 0]
                                                            }],
                                                            xaxis: {
                                                                categories: ['Experiment View Count'],
                                                                title: {
                                                                    text: 'Experiment Metric'
                                                                }
                                                            },
                                                            yaxis: {
                                                                title: {
                                                                    text: 'Count'
                                                                }
                                                            },
                                                            dataLabels: {
                                                                enabled: true,
                                                                formatter: (val) => val.toString()
                                                            },
                                                            tooltip: {
                                                                y: {
                                                                    formatter: (val) => `${val} views`
                                                                }
                                                            },
                                                            legend: {
                                                                position: 'right'
                                                            }
                                                        });

                                                        experimentViewChart.render();
                                                    })
                                                    .catch(error => console.error("Error fetching data:", error));
                                            }



                                            // Listen for clicks on each experiment row
                                            $(".experiment-row").on("click", function() {
                                                const eksperimenId = $(this).data('eksperimen-id'); // Get eksperimenId using jQuery

                                                // Change background color of the selected row
                                                if (selectedRow) {
                                                    selectedRow.removeClass('selected-row'); // Remove class from previously selected row
                                                }
                                                $(this).addClass('selected-row'); // Add class to the clicked row
                                                selectedRow = $(this); // Update the selected row

                                                fetchData(eksperimenId); // Fetch data for the clicked row
                                            });

                                            // Select the first row on initial load
                                            selectFirstRow();
                                        });
                                    </script>

                                    <!-- End Area Chart -->

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
