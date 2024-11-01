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

                        <!--Experiment lists -->
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
                                                    <tr class="experiment-row" data-eksperimen-id="{{ $experiment['eksperimen_id'] }}">
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
                                            <li class=" page-item {{ $dataExperiment->hasMorePages() ? '' : 'disabled' }}">
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
                                    {{-- <div id="areaChart"></div>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", () => {
                                            // Initialize the chart with empty data
                                            const chart = new ApexCharts(document.querySelector("#areaChart"), {
                                                chart: {
                                                    type: 'bar'
                                                },
                                                plotOptions: {
                                                    bar: {
                                                        horizontal: true
                                                    }
                                                },
                                                series: [{
                                                    data: []
                                                }]
                                            });
                                            chart.render();
                                    
                                            // Listen for clicks on table rows
                                            document.querySelectorAll('.experiment-row').forEach(row => {
                                                row.addEventListener('click', async (event) => {
                                                    const eksperimenId = row.getAttribute('data-eksperimen-id');
                                    
                                                    try {
                                                        // Fetch data from the server for the selected experiment
                                                        const response = await fetch(`/api/experiment-data/${eksperimenId}`);
                                                        const data = await response.json();
                                    
                                                        // Update the chart with the fetched data
                                                        chart.updateSeries([{
                                                            data: [
                                                                { x: 'Button Click', y: data.button_click },
                                                                { x: 'Form Submit', y: data.form_submit }
                                                            ]
                                                        }]);
                                                    } catch (error) {
                                                        console.error('Error fetching experiment data:', error);
                                                    }
                                                });
                                            });
                                        });
                                    </script> --}}
                                    <!-- End Area Chart -->

                                </div>
                            </div>
                        </div><!-- End Reports -->


                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
