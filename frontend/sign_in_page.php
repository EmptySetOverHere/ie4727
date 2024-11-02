<?php
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";
                                            
session_status() === PHP_SESSION_NONE ? session_start() : null;

aquire_username_or_default(DEFAULT_USERNAME);

ob_start(); //start buffer to collect generated html lines
?>

<div class="sign-in-page-container">
    <form action="" method="post" onsubmit="">
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

<script>
    const email_phone_number_section = document.querySelector(".email-phone-number-section");
    const sign_in_with = email_phone_number_section.closest(".sign-in-with");

    const SignInError = {
        INVALID_EMAIL: "Invalid e-mail",
        // INVALID_EMAIL_LENGTH: "The maximum length of an e-mail address should not be longer than 255 characters",
        INVALID_PHONE_NUMBER_FORMAT: "Invalid phone number",
        INVALID_PASSWORD: "Invalid Password",
        NO_ERROR: "",
    };

    function check_email(email_str) {
        const email_reg = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        const address_max_length = 255;
        const address_min_length = 6;

        if (
            address_max_length < email_str.length ||
            email_str.length < address_min_length ||
            !email_reg.test(email_str)
        ) {
            return SignInError.INVALID_EMAIL;
        }

        return SignInError.NO_ERROR;
    }

    function handle_submit(e) {
        console.log(e.target);
    }
</script>

<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(SIGN_IN_PAGE_STYLE)
    ->set_navibar(NAV_LINKS, $usernane = "guest")
    ->set_outline(SIGN_IN_PAGE_SCRIPTS)
    ->render();
?>