<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);

ob_start(); //start buffer to collect generated html lines
?>

<div class="body-inner">
    <div class="feedback-container">
        <h2>We Value Your Feedback!</h2>
            <form id="feedbackForm" action="../backend/feedback/api_new_feedback.php" method="post">
                <div class="feedback-question">
                    <label>1. How satisfied are you with the online ordering service?</label>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Very Satisfied">Very Satisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Satisfied">Satisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Neutral">Neutral</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Dissatisfied">Dissatisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Very Dissatisfied">Very Dissatisfied</div>
                </div>
                <div class="feedback-question">
                    <label>2. How satisfied are you with serving time?</label>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Very Satisfied">Very Satisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Satisfied">Satisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Neutral">Neutral</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Dissatisfied">Dissatisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Very Dissatisfied">Very Dissatisfied</div>
                </div>
                <div class="feedback-question">
                    <label>1. How satisfied are you with our service?</label>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Very Satisfied">Very Satisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Satisfied">Satisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Neutral">Neutral</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Dissatisfied">Dissatisfied</div>
                    <div class="feedback-option"><input type="radio" name="satisfaction" value="Very Dissatisfied">Very Dissatisfied</div>
                </div>

                <div class="feedback-question">
                    <label>4. Would you recommend us to others?</label>
                    <div class="feedback-option"><input type="radio" name="recommend" value="Yes">Yes</div>
                    <div class="feedback-option"><input type="radio" name="recommend" value="No">No</div>
                    <div class="feedback-option"><input type="radio" name="recommend" value="Maybe">Maybe</div>
                </div>
                <div class="feedback-question">
                    <label>5. Additional Comments</label>
                    <textarea name="comments" placeholder="Any additional thoughts..."></textarea>
                </div>
                <button type="submit" class="submit-button">Submit Feedback</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(ORDER_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username)
    ->set_outline(ORDER_PAGE_SCRIPTS)
    ->render();
?>