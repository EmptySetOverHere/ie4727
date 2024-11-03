<?php
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";
                                            
session_status() === PHP_SESSION_NONE ? session_start() : null;

aquire_username_or_default(DEFAULT_USERNAME);

if(isset($_GET["in-or-up"])) {
    $in_or_up = $_GET["in-or-up"]; // sign in -> true sign up -> false 
} else {
    $in_or_up = "true";
}

if(filter_var($in_or_up, FILTER_VALIDATE_BOOL)) {
    $title_section_text = "Welcome Back";
    $switch_method_text_prefix = "sign in";
    $button_text = "Sign In";
    $no_have_account_text = "Do not have an account? Join Us";
    $backend_handler = "api_signin.php";
} else {
    $title_section_text = "Join Us Now";
    $switch_method_text_prefix = "sign up";
    $button_text = "Sign Up";
    $no_have_account_text = "Already have an account?";
    $backend_handler = "api_signup.php";
}

ob_start(); //start buffer to collect generated html lines
?>

<div class="sign-in-up-page-container">
    <form id="sign-in-up-form" action="..\backend\Auth\<?= $backend_handler ?>" method="post">
        <div class="left-sign-container">
            <div class="sign-in-up-page-container-inner">
                <div class="title-section">
                    <span class="unselectable"><?= $title_section_text ?></span>
                </div>
                <div class="email-phone-number-section">
                    <div class="email-phone-switch-container">
                        <label class="unselectable" id="email-label" for="email">Email</label>
                        <div class="flex-placeholder"></div>
                        <?php if(filter_var($in_or_up, FILTER_VALIDATE_BOOL)) { ?>
                            <span class="unselectable" id="sign-in-up-with" data-method="email" onclick="switch_sign_in_method()"><?= $switch_method_text_prefix ?> with phone number</span>
                        <?php } ?>
                    </div>
                    <input type="email" name="email" id="email" placeholder="example@site.com" required>
                </div>
                <div class="email-phone-number-section">
                    <label class="unselectable" id="phone-label" for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" placeholder="+65 93948788" required>
                </div>
                <div class="password-section">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="******" required>
                </div>
                <div class="submit-button-container">
                    <button class="unselectable" type="submit"><?= $button_text ?></button>
                </div>
                <div class="no-have-account-container">
                    <span class="unselectable" id="no-have-account" onclick="go_to_sign_in_up()" required><?= $no_have_account_text ?></span>
                </div>
            </div>
        </div>
        <div class="right-background-container">
        </div>
    </form>
</div>

<script>
    const email_phone_number_section = document.querySelector(".email-phone-number-section");
    const sign_in_up_with = document.getElementById("sign-in-up-with");
    const no_account_container = document.getElementById("no-account");
    
    function go_to_sign_in_up(e) {
        const in_or_up = <?= $in_or_up ?>;  
        window.location.assign("./sign_in_up_page.php?in-or-up=" + !in_or_up);
    }

    <?php if(filter_var($in_or_up, FILTER_VALIDATE_BOOL)) { ?>

    function switch_sign_in_method(e) {
        const method = sign_in_up_with.getAttribute("data-method");

        if(method === "email") {
            const email_input = document.getElementById("email");
            const email_label = document.getElementById("email-label");
            
            email_label.innerHTML = "Phone Number"; 
            email_label.setAttribute("for", "phone");
            email_label.setAttribute("id", "phone-label");
            email_input.setAttribute("name", "phone");
            email_input.setAttribute("type", "tel");
            email_input.setAttribute("id", "phone");
            email_input.setAttribute("placeholder", "+65 93948788");
            email_input.value = "";
            
            sign_in_up_with.setAttribute("data-method", "phone");
            sign_in_up_with.innerHTML = "<?= $switch_method_text_prefix ?> " + "with email";
            
        } else if (method === "phone") {
            const phone_input = document.getElementById("phone");
            const phone_label = document.getElementById("phone-label");
            
            phone_label.innerHTML = "Email"; 
            phone_label.setAttribute("for", "email");
            phone_label.setAttribute("id", "email-label");
            phone_input.setAttribute("name", "email");
            phone_input.setAttribute("type", "text");
            phone_input.setAttribute("id", "email");
            phone_input.setAttribute("placeholder", "example@site.com");
            phone_input.value = "";

            sign_in_up_with.setAttribute("data-method", "email");
            sign_in_up_with.innerHTML = "<?= $switch_method_text_prefix ?> " + "with phone number";
        }
    }

    <?php } ?>

    // const SignInError = {
    //     INVALID_EMAIL: "Invalid e-mail",
    //     // INVALID_EMAIL_LENGTH: "The maximum length of an e-mail address should not be longer than 255 characters",
    //     INVALID_PHONE_NUMBER_FORMAT: "Invalid phone number",
    //     INVALID_PASSWORD: "Invalid Password",
    //     NO_ERROR: "",
    // };

// Dealing with redirects

<?php if(isset($_SESSION["sign-in-error-msg"])) { 
    $err = $_SESSION["sign-in-error-msg"];
    switch($err) {
        case "Bad Request": 
            break;
            // Email/phone number does not exist yet
        case "User does not exist": 
?>
    (function () {
        const email_input = document.querySelector("input.email");
        email_input.style.borderColor = "red";
        alert("User does not exist");
    })();
<?php
            break;

        // Wrong password
        case "Wrong password": 
?>
(function () {
    const password_input = document.querySelector("input.password");
    email_input.style.borderColor = "red";
    alert("Wrong Password");
})();
<?php
            break;
        // Database connection refused/query error
        case "Database connection error":
            break;
            
        // Database prepare error
        case "Database prepare error":
            break; 
    }
} 
?>    
        

    // function check_email(email_str) {
    //     const email_reg = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    //     const address_max_length = 255;
    //     const address_min_length = 6;

    //     if (
    //         address_max_length < email_str.length ||
    //         email_str.length < address_min_length ||
    //         !email_reg.test(email_str)
    //     ) {
    //         return SignInError.INVALID_EMAIL;
    //     }

    //     return SignInError.NO_ERROR;
    // }

    // function handle_submit(e) {
    //     const formID = "sign-in-up-form";
    //     const form = document.getElementById("sign-in-up-form");
    //     const sign_in_data =  new FormData(form);

    //     const email = sign_in_data.get("email");
    //     const password = sign_in_data.get("password");

    //     let err = check_email(email);

    // }
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