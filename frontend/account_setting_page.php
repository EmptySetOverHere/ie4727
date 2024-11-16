<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
// require_once '../backend/update_profile/get_user_preferences.php';
// $user_preferences = get_user_preferences();


ob_start(); //start buffer to collect generated html lines
?>
<div class="section-header">Account Settings</div>

<div class='centered-items account-setting-content-container'>
    <div class="grouped-settings-container">
        <div class="section-header">Update Login Information</div>
        <div class="section-divider"></div>
        <form id="update-auth-form" action="../backend/auth/api_update_auth.php">
            <div class="text-input-container">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>
            <div class="text-input-container">
                <label for="phone_number">Phone Number</label>
                <input type="number" name="phone_number" id="phone_number">
            </div>
            <div class="text-input-container">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="text-input-container">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password">
            </div>
        </form>
        <div class="submit-button-container">
            <button type="submit" onclick="submit('update-auth-form', verify_auth)">Update</button>
        </div>
    </div>
    <br>
    <div class="grouped-settings-container">
        <div class="section-header">Update Account Preferences</div>
        <div class="section-divider"></div>
        <form id="update-preferences-form" action="../backend/update_profile/api_update_profile.php">
            <div class="text-input-container">
                <label for="prefered_name">Prefered Name</label>
                <input type="text" name="prefered_name" id="prefered_name">
            </div>
            <div class="text-input-container">
                <label for="address">Address</label>
                <input type="text" name="address" id="address">
            </div>
            <div class="text-input-container">
                <label for="preferred_payment_method">Payment Method</label>
                <input type="text" name="preferred_payment_method" id="preferred_payment_method">
            </div>
            <div class="notification-preference-title">
                Notification Preferences
            </div>
            <div class="checkbox-input-container">
                <table>
                    <tr>
                        <td><label for="is_notify_by_sms">SMS</label></td>
                        <td><input type="checkbox" name="is_notify_by_sms" id="is_notify_by_sms"></td>
                    </tr>
                    <tr>
                        <td><label for="is_notify_by_email">Email</label></td>
                        <td><input type="checkbox" name="is_notify_by_email" id="is_notify_by_email"></td>
                    </tr>
                    <tr>
                        <td><label for="is_notify_by_whatsapp">Whatsapp</label></td>
                        <td><input type="checkbox" name="is_notify_by_whatsapp" id="is_notify_by_whatsapp"></td>
                    </tr>
                    <tr>
                        <td><label for="is_notify_by_telegram">Telegram</label></td>
                        <td><input type="checkbox" name="is_notify_by_telegram" id="preferred_payment_method"></td>
                    </tr>
                </table>
            </div>
        </form>
        <div class="submit-button-container">
            <button type="submit" onclick="submit('update-preferences-form')">Update</button>
        </div>
        <br>
    </div>
    <div class="sign-out-button-container">
        <form action="../backend/Auth/api_logout.php" method="GET" id="sign-out-form">
            <div class="submit-button-container">
                <button type="submit" id="sign-out-button">Sign Out</button>
            </div>
        </form>
    </div>
</div>
<script>
    function submit(form_id, verification_function = null){
        if (verification_function === null || verification_function()) {
            document.getElementById(form_id).submit();
        }
    }
    function verify_auth(){
        alert('nyan');
        return true //TODO
    }

</script>

<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(ACCOUNT_SETTING_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username)
    ->set_outline(ACCOUNT_SETTING_PAGE_SCRIPTS)
    ->render();
?>