    <div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input validate" type="radio" name="gridRadios{{ $index }}"
                                id="button-type{{ $index }}" value="button" checked>
                            <div>
                                <label for="button-click{{ $index }}" class="form-label">Button Click</label>
                                <input type="text" class="form-control validate-input-button"
                                    id="button-click{{ $index }}" placeholder="Insert Classname Button">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:20px ">
                        <div class="form-check">
                            <input class="form-check-input validate" type="radio" name="gridRadios{{ $index }}"
                                id="form-type{{ $index }}" value="form">
                            <div>
                                <label for="form-id{{ $index }}" class="form-label">Submit Form</label>
                                <input type="text" class="form-control validate-input-form"
                                    id="form-id{{ $index }}" placeholder="Insert Submit Form ID">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary saved" data-dismiss="modal"
                        data-index="{{ $index }}">Save changes</button>
                </div>
            </div>
        </div>
    </div>
