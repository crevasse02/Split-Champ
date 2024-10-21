@extends('layout.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Campaign</h1>
            <nav style="--bs-breadcrumb-divider: '|';">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Campaign</li>
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
                                            <label for="nama_eksperimen" class="form-label">Nama Eksperimen</label>
                                            <select class="form-control" id="nama_eksperimen" name="nama_eksperimen">
                                                <option value="">Select Experimen</option>
                                                @foreach ($data as $experiment)
                                                    <option value="{{ $experiment['id'] }}">
                                                        {{ $experiment['eksperimen_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="url" class="form-label">URL</label>
                                            <input type="text" class="form-control" id="url">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputPassword5" class="form-label">Variant</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Variant"
                                                    aria-describedby="basic-addon1" id="">
                                            </div>
                                        </div>

                                    </form><!-- End Multi Columns Form -->

                                    <h3 class="text-primary my-4">Conversion</h3>
                                    <form class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gridRadios"
                                                    id="button-type" value="button-type" checked="">
                                                <div>
                                                    <label for="button-click" class="form-label">Button Click</label>
                                                    <input type="text" class="form-control" id="button-click"
                                                        placeholder="Insert Classname Button">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gridRadios"
                                                    id="form" value="form">
                                                <div>
                                                    <label for="form-id" class="form-label">Submit Form</label>
                                                    <input type="text" class="form-control" id="form-id"
                                                        placeholder="Insert Submit Form ID">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button type="button" id="add-variant"
                                                class="btn btn-primary rounded-pill bgc-primary">Add
                                                Variant</button>
                                        </div>
                                    </form><!-- End Multi Columns Form -->
                                </div>
                            </div><!-- End Form Variant Add -->
                        </div>
                    </div>
                </div>
        </section>
    </main><!-- End #main -->
@endsection
