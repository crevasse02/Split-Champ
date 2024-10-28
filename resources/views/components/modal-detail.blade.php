<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="{{ $variantList->isNotEmpty() ? 'col-md-6' : 'col-md-12' }}">
                        <div class="d-flex justify-content-between align-center mb-2">
                            <h5 style="font-weight: 700">{{ $experimentData->eksperimen_name }}</h5>
                            {{-- <button type="button" id="triggerEdit{{$id}}" class="btn btn-sm btn-warning " style="{{ $variantList->isNotEmpty() ? 'display: block' : 'display: none' }}"><i
                                    class="bi bi-pencil-square" style="color: white; "></i></button> --}}
                        </div>
                        <div style="max-height: 500px; overflow:auto;" class="p-2">
                            @if ($variantList->isNotEmpty())
                                @foreach ($variantList as $variant)
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Variant {{ $loop->iteration }}</h5>
                                            <form id="updateVariantForm-{{ $variant->variant_id }}"
                                                class="variant-form">
                                                @csrf
                                                <div>

                                                    <label for="variant-name" class="col-form-label">Variant
                                                        Name</label>
                                                    <input type="text" class="form-control"
                                                        id="variant-name{{ $variant->variant_id }}" readonly
                                                        value="{{ $variant->variant_name }}">
                                                </div>
                                                <div>
                                                    <label for="url-name" class="col-form-label">URL</label>
                                                    <input type="text" class="form-control"
                                                        id="url-name{{ $variant->variant_id }}" readonly
                                                        value="{{ $experimentData->domain_name . '/' . $variant->url_variant }}">
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <label for="conversion-name" class="col-form-label">Conversion
                                                            Type</label>
                                                        <select class="form-select" aria-label="Select conversion"
                                                            id="conversion-name{{ $variant->variant_id }}"
                                                            name="conversion_type" disabled>
                                                            <option value="button"
                                                                {{ $variant->conversion_type == 'button' ? 'selected' : '' }}>
                                                                Button</option>
                                                            <option value="form"
                                                                {{ $variant->conversion_type == 'form' ? 'selected' : '' }}>
                                                                Form</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="idorclass-name" class="col-form-label">ID/Class
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="idorclass-name{{ $variant->variant_id }}" readonly
                                                            value="{{ isset($variant->button_click_name) ? '.' . $variant->button_click_name : '#' . $variant->submit_form_name }}">
                                                    </div>
                                                </div>
                                                <input type="hidden" class="form-control"
                                                    id="variantId{{ $variant->variant_id }}"
                                                    value="{{ $variant->variant_id }}">
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>No variant details available.</p>
                            @endif
                        </div>
                        <button type="button" class="btn btn-md btn-warning w-100 mt-2"
                            id="editButton{{ $id }}" style="display: none;">
                            <i class="bi bi-floppy"></i> Save Edit
                        </button>
                    </div>
                    <div class="col-md-6 d-flex justify-content-between flex-column">
                        @if ($variantList->isNotEmpty())
                            <button type="button" id="generateButton{{ $experimentData->eksperimen_id }}"
                                class="btn btn-md btn-primary w-100">
                                <i class="bi bi-arrow-clockwise"></i> Generate Code
                            </button>
                            <textarea class="form-control w-100 mt-2" id="scriptTextArea{{ $experimentData->eksperimen_id }}" readonly
                                style="height: 100%;"></textarea>
                            <button type="button" class="btn btn-md btn-secondary w-100 mt-2"
                                id="copyButton{{ $experimentData->eksperimen_id }}" style="display: none;">
                                <i class="fas fa-copy"></i> Copy Code
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var modalId = null
        // Use event delegation to bind events dynamically
        $(document).on('click', '[data-toggle="modal"]', function() {
            // Get the modal ID from the data-target attribute
            const tempId = $(this).data('target');

            modalId = tempId.replace('#modal', '').trim();
        });
        $(document).on('click', '[id^=generateButton]', function() {
            // Get the experiment ID from the button's ID
            const experimentId = $(this).attr('id').replace('generateButton', '');
            const slugs = [];


            $(this).closest('.modal').find('[id^=idorclass-name]').each(function() {
                const urlInput = $(this).closest('.card-body').find('input').eq(
                    1); // Get the URL input of the variant
                if (urlInput.length) {
                    slugs.push(urlInput.val());
                }
            });

            const scriptContent = `const slugs = ${JSON.stringify(slugs)};`;

            const combinedScript = 'document.addEventListener("DOMContentLoaded", function () {' +
                scriptContent + redirectScript + '});';

            // Set the textarea's value to the combined script content
            $('#scriptTextArea' + experimentId).val('<script> ' + combinedScript + ' </ script>');
            // Show the copy button after code generation
            $('#copyButton' + experimentId).show();


        });

        $(document).on('click', '[id^=copyButton]', function() {
            // Get the experiment ID from the button's ID
            const experimentId = $(this).attr('id').replace('copyButton', '');

            // Select the textarea content
            $('#scriptTextArea' + experimentId).select();

            // Copy the text to the clipboard
            document.execCommand('copy');

            // Optionally, provide feedback to the user
            Swal.fire({
                icon: "success",
                title: "Copied!",
                text: "The code has been copied to your clipboard.",
            });
        });

        $(document).on('click', '[id^=triggerEdit]', function() {
            $('[id^=editButton]').show();
            $("[id^='variant-name']").prop("readonly", false);
            $("[id^='url-name']").each(function() {
                var currentValue = $(this).val();
                var updatedValue = currentValue.split('/').pop(); // Get text after the last '/'
                $(this).val(updatedValue);
                $("[id^='url-name']").prop("readonly", false);
            })
            $("[id^='idorclass-name']").each(function() {
                var currentValue = $(this).val();
                var updatedValue = currentValue.replace(/^[.#]/,
                    ""); // Trim '.' or '#' at the start
                $(this).val(updatedValue).prop("readonly",
                    false); // Set new value and remove readonly
            });
            $("[id^='conversion-name']").prop("disabled", false);
        })

        $(document).on('hidden.bs.modal', '[class*="modal fade"]', function() {
            // Use the modal's dynamic ID to reset values
            const modalId = $(this).attr('id');

            $(`#${modalId} [id^='variant-name']`).each(function() {
                $(this).val($(this).data('default')).prop("readonly",
                    true); // Reset to default and make it readonly
            });
            $(`#${modalId} [id^='url-name']`).each(function() {
                $(this).val($(this).data('default')).prop("readonly",
                    true); // Reset to default and make it readonly
            });
            $(`#${modalId} [id^='conversion-name']`).each(function() {
                $(this).val($(this).data('default')).prop("disabled",
                    true); // Reset to default and disable
            });
            $(`#${modalId} [id^='idorclass-name']`).each(function() {
                $(this).val($(this).data('default')).prop("readonly",
                    true); // Reset to default and make it readonly
            });
            $('[id^=editButton]').hide();
        });

        $(document).on('show.bs.modal', '[class*="modal fade"]', function() {
            // Store default values again to ensure they're current
            $("[id^='variant-name']").each(function() {
                $(this).data('default', $(this).val());
            });
            $("[id^='url-name']").each(function() {
                $(this).data('default', $(this).val());
            });
            $("[id^='conversion-name']").each(function() {
                $(this).data('default', $(this).val());
            });
            $("[id^='idorclass-name']").each(function() {
                $(this).data('default', $(this).val());
            });
        });

        $('[id^=editButton]').click(function() {
            var forms = $('.variant-form'); // Select all forms

            forms.each(function() {
                var form = $(this);
                // const variantId = $(this).closest('.card-body').find('input').eq(
                //     1); // Get the URL input of the variant
                // if (urlInput.length) {
                //     slugs.push(urlInput.val());
                // }
                var variantId = form.find('[id^=variantId]').val();

                console.log(variantId)
                // Gather form data
                // var formData = form.serialize();

                // // Send AJAX request to update variant
                // $.ajax({
                //     url: '/update-variants/' + variantId, // Adjust the URL as necessary
                //     type: 'PUT', // Use PUT method for update
                //     data: formData,
                //     success: function(response) {
                //         console.log('Variant updated successfully:', response);
                //         // Optionally, hide save button and show edit button again
                //         $('#editButton').hide();
                //         // $('#tri').show();
                //         // Optionally disable fields again
                //         $('[id^="variant-name"]').prop("readonly", true);
                //         $('[id^="url-name"]').prop("readonly", true);
                //         $('[id^="conversion-name"]').prop("disabled", true);
                //         $('[id^="idorclass-name"]').prop("readonly", true);
                //     },
                //     error: function(xhr) {
                //         console.error('Update failed:', xhr.responseJSON);
                //         // Optionally handle errors (e.g., show alert)
                //     }
                // });
            });
        });
    });
</script>
