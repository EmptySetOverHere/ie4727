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
            <input type="number" name="order_id" value="123456" required hidden>
            <div class="feedback-question">
                <label>1. How satisfied are you with our web experience?</label>
                <div class="feedback-option"><input type="radio" name="app_experience_rating" value="5" required>Very Satisfied</div>
                <div class="feedback-option"><input type="radio" name="app_experience_rating" value="4">Satisfied</div>
                <div class="feedback-option"><input type="radio" name="app_experience_rating" value="3">Neutral</div>
                <div class="feedback-option"><input type="radio" name="app_experience_rating" value="2">Dissatisfied</div>
                <div class="feedback-option"><input type="radio" name="app_experience_rating" value="1">Very Dissatisfied</div>
            </div>
            <div class="feedback-question">
                <label>2. How satisfied are you with serving time?</label>
                <div class="feedback-option"><input type="radio" name="wait_time_rating" value="5" required>Very Satisfied</div>
                <div class="feedback-option"><input type="radio" name="wait_time_rating" value="4">Satisfied</div>
                <div class="feedback-option"><input type="radio" name="wait_time_rating" value="3">Neutral</div>
                <div class="feedback-option"><input type="radio" name="wait_time_rating" value="2">Dissatisfied</div>
                <div class="feedback-option"><input type="radio" name="wait_time_rating" value="1">Very Dissatisfied</div>
            </div>
            <div class="feedback-question">
                <label>3. How satisfied are you with our food quality?</label>
                <div class="feedback-option"><input type="radio" name="food_quality_rating" value="5" required>Very Satisfied</div>
                <div class="feedback-option"><input type="radio" name="food_quality_rating" value="4">Satisfied</div>
                <div class="feedback-option"><input type="radio" name="food_quality_rating" value="3">Neutral</div>
                <div class="feedback-option"><input type="radio" name="food_quality_rating" value="2">Dissatisfied</div>
                <div class="feedback-option"><input type="radio" name="food_quality_rating" value="1">Very Dissatisfied</div>
            </div>
            <div class="feedback-question">
                <label>4. Additional Comments</label>
                <textarea name="comments" placeholder="Any additional thoughts..."></textarea>
            </div>
            <button type="submit" class="submit-button">Submit Feedback</button>
        </form>
    </div>
</div>


<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(FEEDBACK_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username)
    ->set_outline(FEEDBACK_PAGE_SCRIPTS)
    ->render();
?>