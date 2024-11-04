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
                                                            // Clear the area chart and display "Data Not Found" message
                                                            if (areaChart) {
                                                                areaChart.destroy(); // Destroy any existing chart instance
                                                            }
                                                            $("#areaChart").html("<p>Data Not Found</p>"); // Display message
                                                            return; // Exit the function
                                                        } else {
                                                            $("#areaChart").html(''); // Clear the chart container
                                                        }

                                                        // Set up series data for each conversion type
                                                        const seriesData = [{
                                                                name: 'Button Clicks',
                                                                data: [...data.variants.map(variant => variant.button_click || 0),
                                                                    null] // Add null for last bar
                                                            },
                                                            {
                                                                name: 'Form Submits',
                                                                data: [...data.variants.map(variant => variant.form_submit || 0),
                                                                    null] // Add null for last bar
                                                            },
                                                            {
                                                                name: 'Views (Variants)',
                                                                data: [...data.variants.map(variant => variant.view || 0),
                                                                    null] // Add null for last bar
                                                            },
                                                            {
                                                                name: 'Experiment View Count',
                                                                data: Array(data.variants.length).fill(null).concat(data
                                                                    .experiment_view_count || 0) // Fill nulls, add count at end
                                                            }
                                                        ];

                                                        // Destroy existing chart if it exists
                                                        if (areaChart) {
                                                            areaChart.destroy();
                                                        }

                                                        // Initialize and render the bar chart
                                                        areaChart = new ApexCharts(document.querySelector("#areaChart"), {
                                                            chart: {
                                                                type: 'bar',
                                                                toolbar: {
                                                                    show: false // Hide toolbar if not needed
                                                                }
                                                            },
                                                            plotOptions: {
                                                                bar: {
                                                                    horizontal: false, // Set to vertical bars
                                                                    dataLabels: {
                                                                        position: 'top' // Display data labels on top of bars
                                                                    }
                                                                }
                                                            },
                                                            series: seriesData,
                                                            xaxis: {
                                                                categories: [
                                                                    ...data.variants.map(variant => variant.variant_name),
                                                                    'Experiment View Count' // Add as separate x-axis category
                                                                ],
                                                                title: {
                                                                    text: 'Conversion Type'
                                                                }
                                                            },
                                                            yaxis: {
                                                                title: {
                                                                    text: 'Count'
                                                                }
                                                            },
                                                            dataLabels: {
                                                                enabled: true,
                                                                formatter: (val) => val ? val.toString() :
                                                                    '' // Ensure data labels display as text, hide nulls
                                                            },
                                                            tooltip: {
                                                                y: {
                                                                    formatter: (val) => `${val} interactions` // Customize tooltip
                                                                }
                                                            },
                                                            legend: {
                                                                position: 'right'
                                                            }
                                                        });

                                                        areaChart.render();
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
