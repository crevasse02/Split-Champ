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
    <script>
        $(document).ready(function() {
            let appendCount = 0;
            const maxAppends = 3;

            // function get conversion type val
            function toggleInput(index) {
                const isButtonChecked = $('#button-type' + index).is(':checked');
                const isFormChecked = $('#form-type' + index).is(':checked');

                $('#button-click' + index).val(isButtonChecked ? '' : '');
                $('#form-id' + index).val(isFormChecked ? '' : '');

                return isButtonChecked ? 'button' : isFormChecked ? 'form' : null;
            }

            // function append variant
            function getAppendedSection(index) {
                var removeButtonHtml = '';
                if (index === appendCount) {
                    removeButtonHtml = `
                <button type="button" class="btn btn-default remove-btn-static remove-btn${index}" style="color:red; margin-left:10px" data-index="${index}">
                    <i class="bi bi-dash-circle"></i>
                </button>`;
                }

                return $(`
            <div class="appended-section row" data-index="${index}">
                <div class="col-md-4">
                    <label for="url${index}" class="form-label">URL ${index + 1}</label>
                    <input type="text" name="url_variant_${index}" class="form-control" id="url${index}">
                </div>
                <div class="col-md-3">
                    <label for="variant${index}" class="form-label">Variant ${index + 1}</label>
                    <div class="input-group">
                        <input type="text" name="variant_name_${index}" class="form-control" aria-label="Variant" aria-describedby="basic-addon1" id="variant${index}">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="d-flex align-center">
                        <label for="conversion${index}" class="form-label">Conversion Type ${index + 1}</label>
                        <label hidden style="margin-left: 10px; color:green; text-transform:capitalize; font-weight:700" id="selectedType${index}"></label>
                    </div>
                    <div class="d-flex">
                        <div class="input-group d-flex">
                            <input type="text" name="variant_${index}" value="" style="background-color: lightgray;" readonly class="form-control" aria-label="Conversion" aria-describedby="basic-addon1" id="conversion${index}">
                            <button type="button" class="btn btn-primary" id="buttonConversion${index}" style="border-radius: 0px 10px 10px 0px" data-toggle="modal" data-target="#modalCenter${index}">
                                <i class="bi bi-plus"></i> Select Type
                            </button>
                        </div>
                        ${removeButtonHtml} <!-- Append the remove button conditionally -->
                    </div>
                    <x-modal-center id="modalCenter${index}" title="Select Conversion Type" index="${index}"></x-modal-center>
                </div>
            </div>`);
            }

            // function validate save change button
            function checkInputs() {
                let allFilled = true;

                // Loop through all appended sections to check their input values
                $('.appended-section').each(function() {
                    let index = $(this).data('index'); // Get the current section index

                    // Get the values of the inputs in the current section
                    let experimentValue = $('#nama_eksperimen').val();
                    let urlValue = $('#url' + index).val();
                    let variantValue = $('#variant' + index).val();
                    let conversionValue = $('#conversion' + index).val().trim().replace(
                        /[^\w\s]/gi, '');

                    // If any field is empty, set allFilled to false
                    if (experimentValue == 0 || !urlValue || !variantValue || !conversionValue) {
                        allFilled = false;
                    }
                });

                $('#save-change').prop('disabled', !allFilled);
            }

            // function validate save change call
            $('#variantForm').on('input', 'input', function() {
                checkInputs();
            });
            $('#nama_eksperimen').on('change', function() {
                checkInputs();
            });

            // function append variant call
            $('#append-btn').on('click', function() {
                if (appendCount < maxAppends) {
                    $('.remove-btn' + appendCount).hide();
                    appendCount++;
                    var newSection = getAppendedSection(appendCount);
                    $("#form-container").append(newSection);
                    if (appendCount == maxAppends) {
                        $('#append-btn').hide();
                    }
                    $('#save-change').prop('disabled', true)
                } else {
                    alert("You can only add up to 4 sections!");
                }

            });

            // function remove appended variant
            $(document).on('click', '.remove-btn-static', function() {
                var index = $(this).data('index'); // Get the index from the data attribute
                $('.appended-section[data-index="' + index + '"]').remove(); // Remove the appended section
                appendCount--; // Decrement appendCount
                $('.remove-btn' + appendCount).show();
                $('#append-btn').show(); // Show append button again
                checkInputs()
            });

            // function save conversion type dialog
            $(document).on('click', '.saved', function() {
                const index = $(this).data('index');
                const selectedType = $('input[name="gridRadios' + index + '"]:checked').val();
                const tempData = (selectedType === 'button') ? '.' + $('#button-click' + index).val()
                    .replace(/\s+/g, '_') :
                    '#' + $('#form-id' + index).val().replace(/\s+/g, '_');

                if (selectedType) {
                    $('#selectedType' + index).text('(' + selectedType + ')').removeAttr('hidden');
                }

                $('input[name="variant_' + index + '"]').val(tempData);
                $('#button-click' + index + ', #form-id' + index).val(''); // Clear inputs
                checkInputs()
            });

            // function submit and send to db
            $('#variantForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                const csrfToken = $('#_token').val();
                const eksperimenId = $('#nama_eksperimen').val();

                // Check if limit of 4 variants is reached for the eksperimen_id
                checkVariantLimit(eksperimenId, csrfToken)
                    .then(limitReached => {
                        if (limitReached) {
                            Swal.fire({
                                icon: "error",
                                title: "Limit Reached",
                                text: "You cannot add more than 4 variants for this eksperimen."
                            });
                        } else {
                            const data = prepareFormData(eksperimenId);
                            storeVariants(data, csrfToken);
                        }
                    })
                    .catch(error => {
                        console.error('Error checking variant count:', error);
                        alert('An error occurred while checking the variant count.');
                    });
            });

            // Function to check variant limit
            function checkVariantLimit(eksperimenId, csrfToken) {
                return $.ajax({
                    url: "/api/check-variant-count",
                    type: 'POST',
                    data: {
                        eksperimen_id: eksperimenId
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                }).then(response => response.limitReached);
            }

            // Function to gather form data
            function prepareFormData(eksperimenId) {
                const data = [];
                $('.appended-section').each(function(index) {
                    const urlValue = $(`#url${index}`).val();
                    const variantValue = $(`#variant${index}`).val();
                    const conversionTypeValue = toggleInput(index);
                    const buttonClickName = conversionTypeValue === 'button' ? $(`#conversion${index}`)
                        .val() : null;
                    const submitFormName = conversionTypeValue === 'form' ? $(`#conversion${index}`).val() :
                        null;

                    data.push({
                        eksperimen_id: eksperimenId,
                        url_variant: urlValue,
                        variant_name: variantValue,
                        conversion_type: conversionTypeValue,
                        button_click_name: buttonClickName ? sanitizeInput(buttonClickName) :
                            undefined,
                        submit_form_name: submitFormName ? sanitizeInput(submitFormName) :
                            undefined,
                    });
                });
                return data;
            }

            // Function to sanitize input
            function sanitizeInput(input) {
                return input.trim().replace(/[^\w\s]/gi, '');
            }

            // Function to send main AJAX request to store variants
            function storeVariants(data, csrfToken) {
                $.ajax({
                    url: "/api/store-variant",
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                        }).then(() => {
                            $('#variantForm')[0].reset();
                            location.reload()
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown, ) {
                        const responseErrors = jqXHR.responseJSON.message;
                        const errorMessage = jqXHR.responseJSON.message ||
                        "An error occurred."; // Fallback message

                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: errorMessage,
                        });
                    }
                });
            }

        });
    </script>
@endsection
