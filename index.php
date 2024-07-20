<?php
include 'core/config.php';

if (!isset($_SESSION["pepool_user_id"])) {
  header("location:./login.php");
}

$Users = new Users;
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>PePool</title>
  <!-- CSS files -->
  <link href="./dist/css/tabler.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/demo.min.css?1684106062" rel="stylesheet" />
  <link rel="stylesheet" href="dist/mdi/css/materialdesignicons.min.css" />
  <link href="dist/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link rel="stylesheet" href="dist/sweetalert/sweetalert.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
  
  <link rel="shortcut icon" href="./static/logo.png" />
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
  </style>
</head>

<body>
  <script src="./dist/js/demo-theme.min.js?1684106062"></script>

  <script src="dist/jquery-3.7.1.min.js" type="text/javascript"></script>
  <script src="dist/sweetalert/sweetalert2.js"></script>
  <script src="dist/sweetalert/sweetalert.js"></script>
  <script src="dist/datatables/jquery.dataTables.min.js"></script>
  <script src="dist/datatables/dataTables.bootstrap4.min.js"></script>
  <div class="page">
    <!-- Navbar -->
    <div class="sticky-top">
      <header class="navbar navbar-expand-md sticky-top d-print-none">
        <div class="container-xl">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href=".">
              <img src="./static/logo.png" width="110" height="32" alt="Tabler" class="navbar-brand-image">
            </a>
          </h1>
          <div class="navbar-nav flex-row order-md-last">

            <div class="d-none d-md-flex">
              <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                </svg>
              </a>
              <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                  <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                </svg>
              </a>
              <div class="nav-item dropdown d-none d-md-flex me-3">
              </div>
            </div>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                  </svg></span>
                <div class="d-none d-xl-block ps-2">
                  <div><?= $Users->getUser($_SESSION["pepool_user_id"]) ?></div>
                  <div class="mt-1 small text-muted">Admin</div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="./profile" class="dropdown-item">Profile</a>
                <!-- <a href="#" class="dropdown-item">Feedback</a>
                <div class="dropdown-divider"></div>
                <a href="./settings.html" class="dropdown-item">Settings</a> -->
                <a href="#" onclick="logout()" class="dropdown-item">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </header>
      <header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
          <div class="navbar">
            <div class="container-xl">

              <?php include "components/navbar.php"; ?>

              <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">

              </div>
            </div>
          </div>
        </div>
      </header>
    </div>
    <div class="page-wrapper">
      <!-- Page start -->
      <?php require_once 'routes/routes.php'; ?>
      <!-- Page end -->
      <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
          <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
              <ul class="list-inline list-inline-dots mb-0">
              </ul>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
              <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item">
                  Copyright &copy; 2024
                  <a href="." class="link-secondary">Jeffred Lim</a>.
                  All rights reserved.
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">New report</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="example-text-input" placeholder="Your report name">
          </div>
          <label class="form-label">Report type</label>
          <div class="form-selectgroup-boxes row mb-3">
            <div class="col-lg-6">
              <label class="form-selectgroup-item">
                <input type="radio" name="report-type" value="1" class="form-selectgroup-input" checked>
                <span class="form-selectgroup-label d-flex align-items-center p-3">
                  <span class="me-3">
                    <span class="form-selectgroup-check"></span>
                  </span>
                  <span class="form-selectgroup-label-content">
                    <span class="form-selectgroup-title strong mb-1">Simple</span>
                    <span class="d-block text-muted">Provide only basic data needed for the report</span>
                  </span>
                </span>
              </label>
            </div>
            <div class="col-lg-6">
              <label class="form-selectgroup-item">
                <input type="radio" name="report-type" value="1" class="form-selectgroup-input">
                <span class="form-selectgroup-label d-flex align-items-center p-3">
                  <span class="me-3">
                    <span class="form-selectgroup-check"></span>
                  </span>
                  <span class="form-selectgroup-label-content">
                    <span class="form-selectgroup-title strong mb-1">Advanced</span>
                    <span class="d-block text-muted">Insert charts and additional advanced analyses to be inserted in the report</span>
                  </span>
                </span>
              </label>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-8">
              <div class="mb-3">
                <label class="form-label">Report url</label>
                <div class="input-group input-group-flat">
                  <span class="input-group-text">
                    https://tabler.io/reports/
                  </span>
                  <input type="text" class="form-control ps-0" value="report-01" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="mb-3">
                <label class="form-label">Visibility</label>
                <select class="form-select">
                  <option value="1" selected>Private</option>
                  <option value="2">Public</option>
                  <option value="3">Hidden</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="mb-3">
                <label class="form-label">Client name</label>
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="mb-3">
                <label class="form-label">Reporting period</label>
                <input type="date" class="form-control">
              </div>
            </div>
            <div class="col-lg-12">
              <div>
                <label class="form-label">Additional information</label>
                <textarea class="form-control" rows="3"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancel
          </a>
          <a href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            Create new report
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Libs JS -->
  <!-- Tabler Core -->
<script type='text/javascript'>
  <?php
  echo "var route_settings = " . $route_settings . ";\n";
  ?>
</script>
  <script src="./dist/js/tabler.min.js?1684106062" defer></script>
  <script src="./dist/js/demo.min.js?1684106062" defer></script>
  <script type="text/javascript">
    var modal_detail_status = 0;
    $(document).ready(function() {
      $(".select2").select2();

      $(".select2").css({
        "width": "100%"
      });

      $(".input-item").css({"color": "#fff;"});

      $('ul li a').click(function(){
        $('li a').removeClass("active");
        $(this).addClass("active");
      });
    });

    function logout() {
      var confirm_export = confirm("You are about to logout.");
      if (confirm_export == true) {
        var url = "controllers/sql.php?c=Users&q=logout";
        $.ajax({
          url: url,
          success: function(data) {

            location.reload();

          }
        });
      }
      
    }

    function schema() {
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=schema",
        data: [],
        success: function(data) {
          var json = JSON.parse(data);
          console.log(json.data);
        }
      });
    }

    function success_add() {
      swal("Success!", "Successfully added entry!", "success");
    }

    function success_update() {
      swal("Success!", "Successfully updated entry!", "success");
    }

    function success_delete() {
      swal("Success!", "Successfully deleted entry!", "success");
    }

    function entry_already_exists() {
      swal("Cannot proceed!", "Entry already exists!", "warning");
    }

    function amount_is_greater() {
      swal("Cannot proceed!", "Amount is greater than balance!", "warning");
    }

    function failed_query(data) {
      swal("Failed to execute query!", data, "warning");
    }

    function checkAll(ele, ref) {
      var checkboxes = document.getElementsByClassName(ref);
      if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = true;
          }
        }
      } else {
        for (var i = 0; i < checkboxes.length; i++) {
          //console.log(i)
          if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = false;
          }
        }
      }
    }


    function addModal() {
      modal_detail_status = 0;
      $("#hidden_id").val(0);
      document.getElementById("frm_submit").reset();

      var element = document.getElementById('reference_code');
      if (typeof(element) != 'undefined' && element != null) {
        generateReference(route_settings.class_name);
      }

      $("#modalLabel").html("<i class='flaticon2-add'></i> Add Entry");
      $("#modalEntry").modal('show');
    }

    $("#frm_submit").submit(function(e) {
      e.preventDefault();

      $("#btn_submit").prop('disabled', true);
      $("#btn_submit").html("<span class='fa fa-spinner fa-spin'></span> Submitting ...");

      var hidden_id = $("#hidden_id").val();
      var q = hidden_id > 0 ? "edit" : "add";
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=" + q,
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {

          var json = JSON.parse(data);
          if (route_settings.has_detail == 1) {
            if (json.data > 0) {
              $("#modalEntry").modal('hide')
              hidden_id > 0 ? success_update() : success_add();
              hidden_id > 0 ? $("#modalEntry2").modal('hide') : '';
              hidden_id > 0 ? getEntryDetails2(hidden_id) : getEntryDetails2(json.data);
            } else if (json.data == -2) {
              entry_already_exists();
            } else {
              failed_query(json);
            }
          } else {
            if (json.data == 1) {
              hidden_id > 0 ? success_update() : success_add();
              $("#modalEntry").modal('hide');
            } else if (json.data == 2) {
              entry_already_exists();
            } else {
              failed_query(json);
            }
          }
          getEntries();

          $("#btn_submit").prop('disabled', false);
          $("#btn_submit").html("<span class='fa fa-check-circle'></span> Submit");
        }
      });
    });

    function getEntryDetails(id, is_det = 0) {
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=view",
        data: {
          input: {
            id: id
          }
        },
        success: function(data) {
          var jsonParse = JSON.parse(data);
          const json = jsonParse.data;

          $("#hidden_id").val(id);

          $('.input-item').map(function() {
            //console.log(this.id);
            const id_name = this.id;
            this.value = json[id_name];
          });

          //$(".select2").select2().trigger('change');

          $("#modalLabel").html("<i class='flaticon-edit'></i> Update Entry");
          $("#modalEntry").modal('show');
        }
      });

      if (is_det == 1) {
        modal_detail_status == 1 ? setTimeout(() => {
          $("#modalEntry2").modal('hide')
        }, 500) : '';
      } else {
        modal_detail_status = 0;
      }
    }

    function deleteEntry() {

      var count_checked = $("input[class='dt_id']:checked").length;

      if (count_checked > 0) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover these entries!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-primary",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              var checkedValues = $("input[class='dt_id']:checked").map(function() {
                return this.value;
              }).get();

              $.ajax({
                type: "POST",
                url: "controllers/sql.php?c=" + route_settings.class_name + "&q=remove",
                data: {
                  input: {
                    ids: checkedValues
                  }
                },
                success: function(data) {
                  getEntries();
                  var json = JSON.parse(data);
                  console.log(json);
                  if (json.data == 1) {
                    success_delete();
                  } else {
                    failed_query(json);
                  }
                }
              });

              $("#btn_delete").prop('disabled', true);

            } else {
              swal("Cancelled", "Entries are safe :)", "error");
            }
          });
      } else {
        swal("Cannot proceed!", "Please select entries to delete!", "warning");
      }
    }

    // MODULE WITH DETAILS LIKE SALES

    function getEntryDetails2(id) {
      $("#hidden_id_2").val(id);
      modal_detail_status = 1;
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=view",
        data: {
          input: {
            id: id
          }
        },
        success: function(data) {
          var jsonParse = JSON.parse(data);
          const json = jsonParse.data;

          $('.label-item').map(function() {
            const id_name = this.id;
            const new_id = id_name.replace('_label', '');
            this.innerHTML = json[new_id];
          });

          var transaction_edit = document.getElementById("menu-edit-transaction");
          var transaction_delete_items = document.getElementById("menu-delete-selected-items");
          var transaction_finish = document.getElementById("menu-finish-transaction");
          var col_list = document.getElementById("col-list");
          var col_item = document.getElementById("col-item");

          if (json.status == 'F') {
            transaction_edit.classList.add('disabled');
            (typeof(transaction_delete_items) != 'undefined' && transaction_delete_items != null) ? transaction_delete_items.classList.add('disabled'): '';
            transaction_finish.classList.add('disabled');

            transaction_edit.setAttribute("onclick", "");
            (typeof(transaction_delete_items) != 'undefined' && transaction_delete_items != null) ? transaction_delete_items.setAttribute("onclick", ""): '';
            transaction_finish.setAttribute("onclick", "");

            (typeof(col_item) != 'undefined' && col_item != null) ? col_item.style.display = "none": '';
            (typeof(col_list) != 'undefined' && col_list != null) ? col_list.classList.remove('col-8'): '';
            (typeof(col_list) != 'undefined' && col_list != null) ? col_list.classList.add('col-12'): '';
          } else {
            transaction_edit.classList.remove('disabled');
            (typeof(transaction_delete_items) != 'undefined' && transaction_delete_items != null) ? transaction_delete_items.classList.remove('disabled'): '';
            transaction_finish.classList.remove('disabled');

            transaction_edit.setAttribute("onclick", "getEntryDetails(" + id + ",1)");
            (typeof(transaction_delete_items) != 'undefined' && transaction_delete_items != null) ? transaction_delete_items.setAttribute("onclick", "deleteEntry2()"): '';
            transaction_finish.setAttribute("onclick", "finishTransaction()");

            (typeof(col_item) != 'undefined' && col_item != null) ? col_item.style.display = "block": '';
            (typeof(col_list) != 'undefined' && col_list != null) ? col_list.classList.remove('col-12'): '';
            (typeof(col_list) != 'undefined' && col_list != null) ? col_list.classList.add('col-8'): '';
          }
          getEntries2();
          $("#modalEntry2").modal('show');
        }
      });
    }

    $("#frm_submit_2").submit(function(e) {
      e.preventDefault();

      $("#btn_submit_2").prop('disabled', true);
      $("#btn_submit_2").html("<span class='fa fa-spinner fa-spin'></span> Submitting ...");

      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=add_detail",
        data: $("#frm_submit_2").serialize(),
        success: function(data) {
          getEntries2();
          var json = JSON.parse(data);
          if (json.data == 1) {
            success_add();
            document.getElementById("frm_submit_2").reset();
            $('.select2').select2().trigger('change');
          } else if (json.data == 2) {
            entry_already_exists();
          } else if (json.data == 3) {
            amount_is_greater();
          } else {
            failed_query(json);
            $("#modalEntry2").modal('hide');
          }
          $("#btn_submit_2").prop('disabled', false);
          $("#btn_submit_2").html("<span class='fa fa-check-circle'></span> Submit");
        }
      });
    });

    function deleteEntry2() {

      var count_checked = $("input[class='dt_id_2']:checked").length;

      if (count_checked > 0) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover these entries!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-primary",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              var checkedValues = $("input[class='dt_id_2']:checked").map(function() {
                return this.value;
              }).get();

              $.ajax({
                type: "POST",
                url: "controllers/sql.php?c=" + route_settings.class_name + "&q=remove_detail",
                data: {
                  input: {
                    ids: checkedValues
                  }
                },
                success: function(data) {
                  getEntries2();
                  var json = JSON.parse(data);
                  console.log(json);
                  if (json.data == 1) {
                    success_delete();
                  } else {
                    failed_query(json);
                  }
                }
              });

              $("#btn_delete").prop('disabled', true);

            } else {
              swal("Cancelled", "Entries are safe :)", "error");
            }
          });
      } else {
        swal("Cannot proceed!", "Please select entries to delete!", "warning");
      }
    }

    function finishTransaction() {
      var id = $("#hidden_id_2").val();

      var count_checked = $("input[class='dt_id_2']").length;
      if (count_checked > 0) {
        swal({
            title: "Are you sure?",
            text: "This entries will be finished!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-info",
            cancelButtonClass: "btn-primary",
            confirmButtonText: "Yes, finish it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              $.ajax({
                type: "POST",
                url: "controllers/sql.php?c=" + route_settings.class_name + "&q=finish",
                data: {
                  input: {
                    id: id
                  }
                },
                success: function(data) {
                  getEntries();
                  var json = JSON.parse(data);
                  if (json.data == 1) {
                    success_add();
                    $("#modalEntry2").modal('hide');
                  } else {
                    failed_query(json);
                  }
                }
              });
            } else {
              swal("Cancelled", "Entries are safe :)", "error");
            }
          });
      } else {
        swal("Cannot proceed!", "No entries found!", "warning");
      }
    }

    function getSelectOption(class_name, primary_id, label, param = '', attributes = [], pre_value='', pre_label = 'Please Select') {
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + class_name + "&q=show",
        data: {
          input: {
            param: param
          }
        },
        success: function(data) {
          var json = JSON.parse(data);
          if(pre_value != "remove"){
            $("#" + primary_id).html("<option value='" + pre_value + "'> &mdash; " + pre_label + " &mdash; </option>");
          }

          for (list_index = 0; list_index < json.data.length; list_index++) {
            const list = json.data[list_index];
            var data_attributes = {};
            data_attributes['value'] = list[primary_id];
            for (var attr_index in attributes) {
              const attr = attributes[attr_index];
              data_attributes[attr] = list[attr];
            }
            $('#' + primary_id).append($("<option></option>").attr(data_attributes).text(list[label]));
          }
        }
      });
    }

    function generateReference(class_name) {
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + class_name + "&q=generate",
        data: [],
        success: function(data) {
          var json = JSON.parse(data);
          $("#reference_code").val(json.data);
        }
      });
    }

    function printCanvas() {
      var printContents = document.getElementById('print_canvas').innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
      window.close();
      location.reload();

    }
  </script>
</body>

</html>