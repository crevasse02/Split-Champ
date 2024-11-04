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
                        <form class="col-12" novalidate id="variantForm">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Multi Columns Form -->
                                    <div class="row g-3 pt-3">
                                        <input type="hidden" id="_token" value="{{ csrf_token() }}">

                                        <div class="col-md-12">
                                            <label for="nama_eksperimen" class="form-label">Experiment Name</label>
                                            <select class="form-control" id="nama_eksperimen" name="eksperimen_id">
                                                <option value="0">Select Experimen</option>
                                                @foreach ($data as $experiment)
                                                    <option value="{{ $experiment['eksperimen_id'] }}">
                                                        {{ $experiment['eksperimen_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="form-container" class="row ">
                                            <!-- First section already visible -->
                                            <div class="appended-section row" data-index="0">
                                                <div class="col-md-4">
                                                    <label for="url" class="form-label">URL 1</label>
                                                    <input type="text" name="url_variant_0" class="form-control"
                                                        id="url0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputPassword5" class="form-label">Variant 1</label>
                                                    <div class="input-group">
                                                        <input type="text" name="variant_name_0" class="form-control"
                                                            aria-label="Variant" aria-describedby="basic-addon1"
                                                            id="variant0">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="d-flex align-center">
                                                        <label for="inputPassword5" class="form-label">Conversion Type
                                                            1</label>
                                                        <label hidden
                                                            style="margin-left: 10px; color:green; text-transform:capitalize; font-weight:700"
                                                            id="selectedType0"></label>
                                                    </div>
                                                    <div class="input-group d-flex">
                                                        <input type="text" name="variant_0" value="" readonly
                                                            style="background-color: lightgray;" class="form-control "
                                                            aria-label="Conversion" aria-describedby="basic-addon1"
                                                            id="conversion0">
                                                        <button type="button" class="btn btn-primary"
                                                            id="buttonConversion0" style="border-radius: 0px 10px 10px 0px"
                                                            data-toggle="modal" data-target="#modalCenter0">
                                                            <i class="bi bi-plus"></i> Select Type
                                                        </button>
                                                        <x-modal-center id="modalCenter0" title="Select Conversion Type"
                                                            index="0"></x-modal-center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button type="button" id="append-btn" class="btn btn-primary bgc-primary"
                                                style="width: 100%">
                                                <i class="bi bi-plus"></i> Add Variant</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- End Form Variant Add -->
                            <button type="submit" id="save-change" disabled style="width: 100%"
                                class="btn btn-primary bgc-primary">Save Change</button>
                        </form>
                    </div>
                </div>
        </section>
    </main><!-- End #main -->
    <script src="assets/js/formvariant.js"></script>
@endsection
