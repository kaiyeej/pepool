<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Job Types
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-2 align-items-center">
                                <div class="col-6 col-sm-4 col-md-2 col-xl py-3">
                                    <a href="#" onclick="addModal()" class="btn btn-primary w-100">
                                        Add Entry
                                    </a>
                                </div>
                                <div class="col-6 col-sm-4 col-md-2 col-xl py-3">
                                    <a href="#" onclick="deleteEntry()" id="btn_delete" class="btn btn-danger w-100">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush table-hover" id="dt_entries">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>
                                                <div class='form-check form-check-success'><label class='form-check-label'><input type='checkbox' class='dt_id form-check-input' onchange="checkAll(this,'dt_id')"><i class='input-helper'></i></label></div>
                                            </th>
                                            <th></th>
                                            <th>#</th>
                                            <th>Job Type</th>
                                            <th>Date Added</th>
                                            <th>Date Modified</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'modal_job_types.php'; ?>
<script type="text/javascript">
   
    function getEntries() {
        $("#dt_entries").DataTable().destroy();
        $("#dt_entries").DataTable({
            "processing": true,
            "ajax": {
                "url": "controllers/sql.php?c=" + route_settings.class_name + "&q=show",
                "dataSrc": "data"
            },
            "columns": [{
                    "mRender": function(data, type, row) {
                        return "<input type='checkbox' value=" + row.job_type_id + " class='dt_id' style='position: initial; opacity:1;'>";
                    }
                },
                {
                    "mRender": function(data, type, row) {
                        return "<center><button class='btn btn-primary' onclick='getEntryDetails(" + row.job_type_id + ")'><span class='mdi mdi-grease-pencil'></span></button></center>";
                     }
                },
                {
                    "data": "count"
                },
                {
                    "data": "job_type"
                },
                {
                    "data": "date_added"
                },
                {
                    "data": "date_modified"
                }
            ]
        });
    }
    $(document).ready(function() {
        getEntries();
    });
</script>