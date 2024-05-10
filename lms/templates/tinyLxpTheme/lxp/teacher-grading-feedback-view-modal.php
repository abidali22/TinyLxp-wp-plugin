<style style="text/css">
    .feedback_input_box {
        margin-top: 10px;
    }
    .feedback-btn {
        display: inline-block;
    }
</style>

<?php
$assignment_submission_id = $args['assignment_submission_id'];
// $feedback = get_post_meta($assignment_submission_id, "slide_{$slide}_feedback", true);
?>

<!-- Modal -->
<div class="modal fade modal-lg" id="feedbackViewModal" tabindex="-1" aria-labelledby="feedbackViewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <div class="modal-header-title">
                    <h2 class="modal-title" id="exampleModalLabel">Grade Feedback</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" id="feedbackViewForm">
                    <input type="hidden" name="slide" id="slide" value="0" />
                    <input type="hidden" name="assignment_submission_id" value="<?php echo $assignment_submission_id; ?>" />

                    <div class="input_section">
                        <div class="input_box brief_input_box">
                            <div class="label_box brief_label_box">
                                <div id="feedback-container"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input_section">
                        <div class="btn_box">
                            <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function viewFeedback(slide) {
        jQuery("#feedbackViewForm #slide").val(slide);
        jQuery("#feedbackViewForm").submit();
    }

    jQuery(document).ready(function() {
        let host = window.location.hostname === 'localhost' ? window.location.origin + '/wordpress' : window.location.origin;
        let apiUrl = host + '/wp-json/lms/v1/';

        var feedbackViewModal = document.getElementById('feedbackViewModal');
        feedbackViewModalObj = new bootstrap.Modal(feedbackViewModal);
        window.feedbackViewModalObj = feedbackViewModalObj;
        
        let feedbackViewForm = jQuery("#feedbackViewForm");
        jQuery(feedbackViewForm).on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            
            $.ajax({
                method: "POST",
                enctype: 'multipart/form-data',
                url: apiUrl + "assignment/submission/feedback/view",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
            }).done(function( response ) {
                response.data.length > 0 ? jQuery('#feedback-container').html(response.data) : jQuery('#feedback-container').html('<p><i>No feedback given.</i></p>');
                feedbackViewModalObj.show();
            }).fail(function (response) {
                console.log('fail');
            });
        
        });

        feedbackViewModal.addEventListener('hide.bs.modal', function (event) {
            //jQuery('#inputFeedback').val("");
            //jQuery('.feedback-action').text("Add");
            //window.location.reload();
        });
    });
    
</script>