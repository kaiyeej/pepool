<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Job Post
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row g-4">
                <!-- <div class="col-md-3">
                    <div class="sticky-top" style="top: 150px;">
                        <div class="form-label">Job Types</div>
                        <div class="mb-4" id="canvas_job_types">
                        </div>
                    </div>
                </div> -->
                <div class="col-md-12">
                    <div id="canvas_job_posting" class="job-listings" style="max-height: 600px; overflow-y: auto;">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // getJobTypes();
        getJobPosting();
    });

    // function getJobTypes() {
    //     $.ajax({
    //         url: "controllers/sql.php?c=JobTypes&q=show",
    //         dataType: "json",
    //         success: function(response) {

    //             if (response.data && response.data.length > 0) {
    //                 $("#canvas_job_types").empty();
    //                 response.data.forEach(function(row) {
    //                     var checkbox = $('<label class="form-check">').html(
    //                         '<input type="checkbox" onclick="getJobPosting(' + row.job_type_id + ')" class="form-check-input" name="form-type[]" value="' + row.job_type_id + '" checked>' +
    //                         '<span class="form-check-label">' + row.job_type + '</span>'
    //                     );
    //                     $("#canvas_job_types").append(checkbox);
    //                 });
    //             } else {
    //                 console.log("No job types found.");
    //             }
    //         }
    //     });
    // }

    function getJobPosting() {
        // var param = job_type_id == -1 ? "" : "job_type_id="+job_type_id;
        $.ajax({
            url: "controllers/sql.php?c=" + route_settings.class_name + "&q=show",
            dataType: "json",
            // type: "POST",
            // data: {
            //     param:param
            // },
            success: function(response) {
                console.log(response);

                if (response.data && response.data.length > 0) {
                    $("#canvas_job_posting").empty();

                    response.data.forEach(function(row) {
                        // Construct the HTML for each job posting card
                        var card = $('<div class="card">').html(
                            '<div class="row g-0">' +
                            '<div class="col-auto">' +
                            '<div class="card-body">' +
                            '<div class="avatar avatar-md"><i class="ti ti-briefcase"></i></div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col">' +
                            '<div class="card-body ps-0">' +
                            '<div class="row">' +
                            '<div class="col">' +
                            '<h3 class="mb-0"><a href="#">' + row.job_title + '</a></h3>' +
                            '</div>' +
                            '<div class="col-auto fs-3 text-green">&#8369;' + row.job_fee + '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col-md">' +
                            '<div class="mt-3 list-inline list-inline-dots mb-0 text-muted d-sm-block d-none">' +
                            row.job_desc +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col-md">' +
                            '<div class="mt-3 list-inline list-inline-dots mb-0 text-muted d-sm-block d-none">' +
                            '<div class="list-inline-item">' +
                            '<i class="ti ti-user"></i>' +
                            row.user_fullname +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>'
                        );

                        // Append the constructed card to the canvas
                        $("#canvas_job_posting").append(card);
                    });
                } else {
                    var noJobsCard = $('<div class="card">').html(
                        '<div class="row g-0">' +
                        '<div class="col">' +
                        '<div class="card-body ps-0">' +
                        '<div class="row">' +
                        '<div class="col" style="padding: 25px;">' +
                        '<h3 class="mb-0">No job postings found.</h3>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );

                    // Append the card to the canvas
                    $("#canvas_job_posting").append(noJobsCard);
                    console.log("No job postings found.");
                }
            }
        });
    }
</script>