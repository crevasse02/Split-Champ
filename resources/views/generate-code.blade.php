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

                                            <button type="button" class="btn btn-primary btn-sm openModalBtn"
                                                data-toggle="modal" data-target="#modalDetail"
                                                data-experiment="{{ $experiment }}"
                                                data-variant-list="{{ json_encode($dataVariant->where('eksperimen_id', $experiment['eksperimen_id'])->values()) }}">
                                                Details
                                            </button>
                                            <form id="delete-form-{{ $experiment['eksperimen_id'] }}"
                                                action="{{ route('experiment.destroy', $experiment['eksperimen_id']) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm "
                                                    onclick="confirmDelete('{{ $experiment['eksperimen_id'] }}')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <x-modal-detail>
                            </x-modal-detail>
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
                            <li class=" page-item {{ $dataExperiment->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $dataExperiment->nextPageUrl() }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </section>
        {{-- <script src="assets/js/testing.js"> --}}
        </script>
        <script src="assets/js/generatecode.js"></script>
    </main>
@endsection
