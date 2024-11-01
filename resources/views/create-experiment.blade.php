<?php
// Setting default time zone to jakarta
date_default_timezone_set('Asia/Jakarta');
?>
@extends('layout.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Create Experiment</h1>
            <nav style="--bs-breadcrumb-divider: '|';">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Create Experiment</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">
                        <!-- Form Variant Add -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Multi Columns Form -->
                                    <form class="row g-3 pt-3" id="formExperiment" novalidate>
                                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                        <div class="col-md-12">
                                            <label for="inputName5" class="form-label">Experiment Name</label>
                                            <input name="eksperimen_name" type="text" class="form-control"
                                                id="eksperimen-name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="time-stamp" class="form-label">Domain</label>
                                            <div class="input-group">
                                                <input name="domain_name" type="text" class="form-control"
                                                    aria-label="domain-name" aria-describedby="basic-addon1"
                                                    id="domain-name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="created-by" class="form-label">Created By</label>
                                            <input name="created_by" readonly type="text" class="form-control"
                                                id="created-by" value="{{Auth::user()->name}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="time-stamp" class="form-label">Time Stamp</label>
                                            <div class="input-group">
                                                <input name="time_stamp" readonly type="text" class="form-control"
                                                    aria-label="time-stamp" aria-describedby="basic-addon1" id="time-stamp"
                                                    value="<?= date('Y-m-d H:i:s') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button id="create-experimen" type="submit"
                                                class="btn btn-primary rounded-pill bgc-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- End Form Variant Add -->
                        </div>
                    </div>
                </div>
        </section>
    </main>
    <script>
        $(document).ready(function() {
            $('#formExperiment').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                var form = $('#formExperiment')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                var csrfToken = $('#_token').val();
                // Send form data using jQuery.ajax
                $.ajax({
                    url: "/api/store-experiment",
                    type: 'POST',
                    data: formData,
                    contentType: false, // Necessary for FormData object
                    processData: false, // Necessary for FormData object
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token from hidden input
                    },
                    success: function(response) {
                        $('#eksperimen-name').val('');
                        $('#domain-name').val('');
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                        });

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown); // Log any errors
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
