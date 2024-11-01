$(document).ready(function () {
    $(".openModalBtn").click(function () {
        // Clear previous data
        $("#scriptTextArea").val("");
        $("#copyButton").hide();

        const experiment = $(this).data("experiment");
        const variantList = $(this).data("variant-list");
        let variantContent = `<h5 style="font-weight: 700">${experiment.eksperimen_name}</h5>`;

        // Handle empty variant list
        if (variantList.length === 0) {
            $("#generateButton").hide();
            $("#scriptTextArea").hide();
            variantContent =
                '<div style="max-height: 500px; overflow:auto;" class="p-2"><p>No variant details available.</p></div>';
        } else {
            $("#generateButton").show();
            $("#scriptTextArea").show();
            // Generate the variant list content
            variantContent += variantList
                .map(
                    (variant, index) => `
          <div class="card mb-2">
              <div class="card-body">
                  <h5 class="card-title">Variant ${index + 1}</h5>
                  <form class="variant-form">
                      <div>
                          <label for="variant-name" class="col-form-label">Variant Name</label>
                          <input type="text" class="form-control" id="variant-name${
                              variant.variant_id
                          }" readonly value="${variant.variant_name}">
                      </div>
                      <div>
                          <label for="url-name" class="col-form-label">URL</label>
                          <input type="text" class="form-control" id="url-name${
                              variant.variant_id
                          }" readonly value="${experiment.domain_name}/${
                        variant.url_variant
                    }">
                      </div>
                      <div class="d-flex justify-content-between">
                          <div>
                              <label for="conversion-name" class="col-form-label">Conversion Type</label>
                              <select class="form-select" id="conversion-name${
                                  variant.variant_id
                              }" disabled>
                                  <option value="button" ${
                                      variant.conversion_type === "button"
                                          ? "selected"
                                          : ""
                                  }>Button</option>
                                  <option value="form" ${
                                      variant.conversion_type === "form"
                                          ? "selected"
                                          : ""
                                  }>Form</option>
                              </select>
                          </div>
                          <div>
                              <label for="idorclass-name" class="col-form-label">ID/Class Name</label>
                              <input type="text" id="idorclass-name${
                                  variant.variant_id
                              }" class="form-control" readonly value="${
                        variant.button_click_name
                            ? "." + variant.button_click_name
                            : "#" + variant.submit_form_name
                    }">
                          </div>
                          <input type="hidden" class="form-control" id="eksperimenId${
                              variant.variant_id
                          }" value="${variant.eksperimen_id}">
                          <input type="hidden" id="_token" value="{{ csrf_token() }}">
                      </div>
                  </form>
              </div>
          </div>
      `
                )
                .join("");
        }

        // Update modal title and content
        $("#modalTitle").text(
            "Detail for Experiment: " + experiment.eksperimen_name
        );
        $("#experimentDetails").html(variantContent);
        $("#modalDetail").modal("show");
    });

    // Handle button clicks dynamically
    $(document).on("click", '[data-toggle="modal"]', function () {
        modalId = $(this).data("target").replace("#modal", "").trim();
    });

    $("#generateButton").click(function () {
        const slugs = $(this)
            .closest(".modal")
            .find("[id^=url-name]")
            .map(function () {
                return $(this).val();
            })
            .get()
            .filter((url) => url.length); // Filter out empty values
        const selectors = $(this)
            .closest(".modal")
            .find("[id^=idorclass-name]")
            .map(function () {
                return $(this).val();
            })
            .get()
            .filter((slc) => slc.length);
        const token = $(this)
            .closest(".modal")
            .find("[id^=eksperimenId]")
            .val();
        const variants = $(this)
            .closest(".modal")
            .find("[id^=variant-name]")
            .map(function () {
                return $(this).val();
            })
            .get()
            .filter((slc) => slc.length);

        // Create script content
        const tokenContent = `const token = ${JSON.stringify(token)};`;

        let dataMappingContent = `const dataMapping = [\n`;

        slugs.forEach((slug, index) => {
            // Ensure there's a matching selector and variant for each slug
            const selector = selectors[index] || "";
            const variant = variants[index] || "";

            // Append each entry to dataMappingContent
            dataMappingContent += `    { slug: "${slug}", selector: "${selector}", variant: "${variant}" },\n`;
        });

        dataMappingContent += `];`;

        const combinedScript = `${dataMappingContent} ${tokenContent} ${redirectScript}`;
        $("#scriptTextArea").val(`<script> ${combinedScript}</ script>`);
        $("#copyButton").show(); // Show copy button
    });

    $("#copyButton").click(function () {
        $("#scriptTextArea").select(); // Select textarea content
        document.execCommand("copy"); // Copy to clipboard

        // Feedback for the user
        Swal.fire({
            icon: "success",
            title: "Copied!",
            text: "The code has been copied to your clipboard.",
        });
    });

    $(document).on("click", "[id^=triggerEdit]", function () {
        $(".edit-button").show();
        $("[id^='variant-name'], [id^='url-name']")
            .prop("readonly", false)
            .each(function () {
                const currentValue = $(this).val();
                const updatedValue = $(this).is("[id^=url-name]")
                    ? currentValue.split("/").pop()
                    : currentValue;
                $(this).val(updatedValue); // Update value
            });
        $("[id^='idorclass-name']").each(function () {
            const updatedValue = $(this).val().replace(/^[.#]/, ""); // Trim '.' or '#' at the start
            $(this).val(updatedValue).prop("readonly", false); // Update and remove readonly
        });
        $("[id^='conversion-name']").prop("disabled", false);
    });

    // Clean up modal state on close
    $(document).on("hidden.bs.modal", function () {
        $(".modal-backdrop").remove(); // Remove the overlay after the modal is closed
    });
});

// function soft delete eksperimen database
function confirmDelete(experimentId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, submit the form
            document.getElementById("delete-form-" + experimentId).submit();
            Swal.fire(
                "Deleted!",
                "Your experiment has been deleted.",
                "success"
            );
        }
    });
}
