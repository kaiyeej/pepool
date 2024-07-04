<form action="" method='POST' id='frm_submit'>
  <div class="modal modal-blur fade" id="modalEntry" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Job Types</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <input type="hidden" class="form-control modal_type" name="type">
            <input type="hidden" class="form-control" id="hidden_id" name="input[job_type_id]">
            <div class="col-sm-12">
              <div class="mb-3">
                <label class="form-label">Job Type</label>
                <input type="text" class="form-control input-item" id="job_type" name="input[job_type]" autocomplete="off" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btn_submit_entry" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</form>