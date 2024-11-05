<!-- Modal Component -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Detail Variant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column for Experiment Data -->
                    <div class="col-md-6" id="experimentDetails" style="max-height: 500px; overflow:auto">
                        <!-- Content will be injected here by JavaScript -->
                    </div>


                    <!-- Right Column for Generate Code and Copy Code buttons -->
                    <div class="col-md-6 d-flex justify-content-between flex-column ">
                        <button type="button" id="generateButton" class="btn btn-md btn-primary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Generate Code
                        </button>
                        <textarea class="form-control w-100 mt-2" id="scriptTextArea" readonly style="height: 100%;"></textarea>
                        <button type="button" class="btn btn-md btn-secondary w-100 mt-2 button" id="copyButton" style="display: none;">
                            <i class="fas fa-copy"></i> Copy Code
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

