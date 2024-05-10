<?php
global $treks_src;
?>
<div
    class="modal fade assignment-modal"
    id="calendarModal"
    tabindex="-1"
    aria-labelledby="calendarModalLabel"
    aria-hidden="true"
    >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-body">
            <!-- left side -->
            <div class="assignment-modal-left">
            <div class="assignment-left-header">
                <img src="<?php echo $treks_src; ?>/assets/img/calendar.svg" alt="calendar" />
                <p>Calendar</p>
            </div>
            <div class="assignment-left-detail">
                <h1>New assignment</h1>
                <p>You are creating a new assignment for the following content:</p>
            </div>
            <div class="assignment-left-label">
                <h6 class="taek-label">TREK</h6>
                <div class="interdependence-user">
                <img src="<?php echo $treks_src; ?>/assets/img/tr_main.png" alt="user" class="inter-user-img" />
                <h3 class="inter-user-name trek-title-label">5.12A Interdependence</h3>
                </div>
                <h6 class="taek-label">RPA Segment</h6>
                <div class="recall-user">
                <div class="inter-tab-polygon trek-segment-label-character">
                    <h4>R</h4>
                </div>
                <h3 class="inter-user-name trek-segment-label">Recall</h3>
                </div>
            </div>
            <div class="assignment-search overflow-auto">
                <h6 class="taek-label">Select Student(s)</h6>
                <!-- <input type="text" class="ass-search-input" placeholder="Type student name" /> -->

                <!-- <div class="assignment-search-result">
                    <div class="search-user">
                        <img src="<?php // echo $treks_src; ?>/assets/img/rec_tre_img2.svg" alt="user" />
                        <div class="user-name-detail">
                            <h5>Jane Cooper</h5>
                            <p>5th grade</p>
                        </div>
                    </div>
                </div> -->

            </div>
            </div>
            <!-- right side -->
            <div class="assignment-modal-right">
            <div class="assignment-right-header">
                <div class="modal-header-title">
                <h2 class="modal-title" id="calendarModalLabel">
                    Please select a schedule to create this assignment
                </h2>
                </div>
                <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
                ></button>
            </div>
            <div class="assignment-calendar" id="assignmentCalendar"></div>
            <div class="assignment-right-btn">
                <button class="assignment-right-cancel" onclick="assign_modal_close()">Cancel</button>
                <button class="primary-btn bg-gray-3-color" onclick="trek_section_create_assignment()">Create assignment</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>