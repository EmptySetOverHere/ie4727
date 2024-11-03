<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);

// $_POST['is_']

ob_start(); //start buffer to collect generated html lines
?>

<div class='centered-items account-setting-content-container' style="padding: 20px;">
    <div class="grouped-settings-container">
        <div class="section-header">Login Information</div>
        <div class="section-divider"></div>
        <form id="update-auth-form" action="../backend/auth/api_update_auth.php">
            <div class="text-input-container">
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
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
            <button type="submit">Update</button>
        </div>
    </div>
    <br>
    <div class="grouped-settings-container">
        <div class="section-header">Account Preferences</div>
        <div class="section-divider"></div>
        <form id="update-preferences-form">
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
            <div class="checkbox-input-container">
                <table>
                    <tr>
                        <td><label for="is_notify_by_sms">SMS</label></td>
                        <td><input type="checkbox" name="is_notify_by_sms" id="preferred_payment_method"></td>
                    </tr>
                    <tr>
                        <td><label for="is_notify_by_sms">Email</label></td>
                        <td><input type="checkbox" name="is_notify_by_sms" id="preferred_payment_method"></td>
                    </tr>
                    <tr>
                        <td><label for="is_notify_by_sms">Whatsapp</label></td>
                        <td><input type="checkbox" name="is_notify_by_sms" id="preferred_payment_method"></td>
                    </tr>
                    <tr>
                        <td><label for="is_notify_by_sms">Telegram</label></td>
                        <td><input type="checkbox" name="is_notify_by_sms" id="preferred_payment_method"></td>
                    </tr>
                </table>
            </div>
        </form>
        <div class="submit-button-container">
            <button type="submit">Update</button>
        </div>
        <br>
    </div>
</div>

<!-- TODO: write your inner html content here -->
<!-- the entire html content will be treated as a string later  -->

<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(ACCOUNT_SETTING_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $usernane="guest")
    ->set_outline(ACCOUNT_SETTING_PAGE_SCRIPTS)
    ->render();
?>