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
                                    <form class="row g-3 pt-3">
                                        <div class="col-md-12">
                                            <label for="inputName5" class="form-label">Experiment Name</label>
                                            <input type="text" class="form-control" id="experiment-name">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="time-stamp" class="form-label">Domain</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="domain-name"
                                                    aria-describedby="basic-addon1" id="domain-name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="created-by" class="form-label">Created By</label>
                                            <input disabled type="text" class="form-control" id="created-by"
                                                value="Hecky Riadi">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="time-stamp" class="form-label">Time Stamp</label>
                                            <div class="input-group">
                                                <input type="text" disabled class="form-control" aria-label="time-stamp"
                                                    aria-describedby="basic-addon1" id="time-stamp"
                                                    value="<?= date('Y-m-d H:i:s') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button id="create-experimen" type="button"
                                                class="btn btn-primary rounded-pill bgc-primary">Submit</button>
                                        </div>
                                </div>
                            </div><!-- End Form Variant Add -->
                        </div>
                    </div>
                </div>
        </section>
    </main>
@endsection
