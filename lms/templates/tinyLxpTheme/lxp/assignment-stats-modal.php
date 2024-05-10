<style type="text/css">
.third-tab-date-time {
    border: 0px !important;
}
.students-modal .modal-content .modal-body .students-breadcrumb {
    margin-bottom: 0px !important;
}
</style>

<?php global $treks_src; ?>
<div class="modal fade students-modal" id="assignmentStatsModal" tabindex="-1" aria-labelledby="assignmentStatsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header-title">
            <img src="<?php echo $treks_src; ?>/assets/img/black-group.svg" alt="rocket" />
            <h2 class="modal-title" id="assignmentStatsModalLabel">Student Progress</h2>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="students-breadcrumb">
                <div class="interdependence-user">
                    <img src="<?php echo $treks_src; ?>/assets/img/tr_main.jpg" alt="user" class="inter-user-img" />
                    <h3 class="inter-user-name" id="student-progress-trek-title"></h3>
                </div>
                <img src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" alt="user" class="students-breadcrumb-arrow" />
                <div class="interdependence-tab">
                    <div class="inter-tab-polygon">
                        <h4 id="student-progress-trek-segment-char"></h4>
                    </div>
                    <h3 class="inter-tab-polygon-name" id="student-progress-trek-segment"></h3>
                </div>
            </div>

            <div class="students-breadcrumb">
                <div class="calendar-time-date third-tab-date-time">
                    <img src="<?php echo $treks_src; ?>/assets/img/clock-outline.svg" alt="logo">                    
                    <div class="time-date-box days-box">
                        <div class="time-date-box">
                            <p class="date-time"><span id="student-progress-trek-start-time"></span></p>
                            <p class="date-time to-text">To</p>
                            <p class="date-time"><span id="student-progress-trek-end-time"></span></p>
                        </div>
                        <!-- <label class="to-text all-day-label">
                            <input class="form-check-input" type="checkbox" />
                            All day
                        </label> -->
                    </div>                    
                </div>
            </div>

            <div class="text-center" id="student-modal-loader" style="height: 200px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="students-table" id="student-modal-table">
            <table class="table">
                <thead>
                <tr>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Score (%)</th>
                    <th>Grade</th>
                </tr>
                </thead>
                <tbody>
<!--                     
                <tr>
                    <td>
                    <div class="table-user">
                        <img src="<?php echo $treks_src; ?>/assets/img/header_avatar.svg" alt="user" />
                        <div class="user-about">
                        <h5>Jane Cooper</h5>
                        <p>5th grade</p>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="table-status">Completed</div>
                    </td>
                    <td>10 /10</td>
                    <td>80</td>
                </tr>
                <tr>
                    <td>
                    <div class="table-user">
                        <img src="<?php echo $treks_src; ?>/assets/img/header_avatar.svg" alt="user" />
                        <div class="user-about">
                        <h5>Jane Cooper</h5>
                        <p>5th grade</p>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="table-status">Completed</div>
                    </td>
                    <td>10 /10</td>
                    <td>80</td>
                </tr>
                <tr>
                    <td>
                    <div class="table-user">
                        <img src="<?php echo $treks_src; ?>/assets/img/header_avatar.svg" alt="user" />
                        <div class="user-about">
                        <h5>Jane Cooper</h5>
                        <p>5th grade</p>
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="table-status">Completed</div>
                    </td>
                    <td>10 /10</td>
                    <td>80</td>
                </tr>
 -->
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        var assignmentStatsModal = document.getElementById('assignmentStatsModal');
        assignmentStatsModalObj = new bootstrap.Modal(assignmentStatsModal);
        window.assignmentStatsModalObj = assignmentStatsModalObj;
    });
</script>
