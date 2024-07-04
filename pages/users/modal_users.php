<form action="" method='POST' id='frm_submit'>
  <div class="modal modal-blur fade" id="modalEntry" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <input type="hidden" class="form-control modal_type" name="type">
            <input type="hidden" class="form-control" id="hidden_id" name="input[user_id]">
            <div class="col-sm-4">
              <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control input-item" id="user_fname" name="input[user_fname]" autocomplete="off" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="mb-3">
                <label class="form-label">Middle Name</label>
                <input type="text" class="form-control input-item" id="user_mname" name="input[user_mname]" autocomplete="off">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="mb-3">
                <label class="form-label">Last Name <strong style="color:red;">*</strong></label>
                <input type="text" class="form-control input-item" id="user_lname" name="input[user_lname]" autocomplete="off" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <label class="form-label">Username <strong style="color:red;">*</strong></label>
                <input type="text" class="form-control input-item" id="username" name="input[username]" autocomplete="off" required>
              </div>
            </div>
            <div class="col-sm-6" id="div_password">
              <div class="mb-3">
                <label class="form-label">Password <strong style="color:red;">*</strong></label>
                <input type="password" class="form-control input-item" id="password" name="input[password]" autocomplete="off" required>
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