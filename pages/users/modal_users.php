<form action="" method='POST' id='frm_add'>
  <div class="modal modal-blur fade" id="modal_entry" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <input type="hidden" class="form-control modal_type" name="type">
            <input type="hidden" class="form-control" id="user_id" name="user_id">
            <div class="col-sm-4">
              <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" id="user_fname" name="user_fname" autocomplete="off" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="mb-3">
                <label class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="user_mname" name="user_mname" autocomplete="off">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="mb-3">
                <label class="form-label">Last Name <strong style="color:red;">*</strong></label>
                <input type="text" class="form-control" id="user_lname" name="user_lname" autocomplete="off" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <label class="form-label">Username <strong style="color:red;">*</strong></label>
                <input type="text" class="form-control" id="username" name="username" autocomplete="off" required>
              </div>
            </div>
            <div class="col-sm-6" id="div_password">
              <div class="mb-3">
                <label class="form-label">Password <strong style="color:red;">*</strong></label>
                <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
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