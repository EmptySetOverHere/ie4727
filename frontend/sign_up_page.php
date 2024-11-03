<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

aquire_username_or_default(DEFAULT_USERNAME);

ob_start(); //start buffer to collect generated html lines
?>

<div class="sign-in-page-container">
    <form id="sign-in-form" action="..\backend\Auth\api_signin.php" method="post">
        <div class="left-sign-container">
            <div class="sign-in-page-container-inner">
                <div class="title-section">
                    <span>Welcome Back</span>
                </div>
                <div class="email-phone-number-section">
                    <div class="email-phone-switch-container">
                        <label for="email">Email</label>
                        <div class="flex-placeholder"></div>
                        <span id="sign-in-with" onclick="">sign in with phone number</span>
                    </div>
                    <input type="text" name="email" id="email">
                </div>
                <div class="password-section">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="submit-button-container">
                    <button type="submit">Sign In</button>
                </div>
            </div>
        </div>
        <div class="right-background-container">
        </div>
    </form>
</div>


<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    // ->set_content($content)
    ->set_header(SIGN_UP_PAGE_STYLE)
    ->set_navibar(NAV_LINKS, $usernane="guest")
    ->set_outline(SIGN_UP_PAGE_SCRIPTS)
    ->render();
?>