<?php
// Setting default time zone to jakarta
date_default_timezone_set('Asia/Jakarta');
?>
@extends('layout.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Generate Code</h1>
            <nav style="--bs-breadcrumb-divider: '|';">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Generate Code</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section generate-code">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Eksperimen Name</th>
                                <th scope="col">Domain Name</th>
                                <th scope="col">Created By</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dataExperiment->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">Data Not Found</td>
                                </tr>
                            @else
                                @foreach ($dataExperiment as $index => $experiment)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th> <!-- Row number -->
                                        <td>{{ $experiment['eksperimen_name'] }}</td>
                                        <td>{{ $experiment['domain_name'] }}</td>
                                        <td>{{ $experiment['created_by'] }}</td>
                                        <td>{{ $experiment['created_at'] }}</td>
                                        <td>

                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modal{{ $experiment['eksperimen_id'] }}">Generate</button>

                                            <form id="delete-form-{{ $experiment['eksperimen_id'] }}"
                                                action="{{ route('experiment.destroy', $experiment['eksperimen_id']) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('{{ $experiment['eksperimen_id'] }}')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <x-modal-detail id="modal{{ $experiment['eksperimen_id'] }}"
                                        title="Detail Variant" :experimentData="$experiment" :variantList="$dataVariant->where('eksperimen_id', $experiment['eksperimen_id'])">
                                    </x-modal-detail>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example" class="d-flex justify-content-end">
                        <ul class="pagination">
                            <li class="page-item {{ $dataExperiment->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $dataExperiment->previousPageUrl() }}">Previous</a>
                            </li>
                            @for ($i = 1; $i <= $dataExperiment->lastPage(); $i++)
                                <li class="page-item {{ $i == $dataExperiment->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $dataExperiment->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $dataExperiment->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $dataExperiment->nextPageUrl() }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </section>
    </main>
    <script>
        // function soft delete eksperimen database
        function confirmDelete(experimentId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    document.getElementById('delete-form-' + experimentId).submit();
                    Swal.fire(
                        'Deleted!',
                        'Your experiment has been deleted.',
                        'success'
                    );
                }
            });
        }

        
    </script>
@endsection
