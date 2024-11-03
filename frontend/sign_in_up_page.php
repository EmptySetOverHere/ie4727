<?php
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";
                                            
session_status() === PHP_SESSION_NONE ? session_start() : null;

$username = aquire_username_or_default(DEFAULT_USERNAME);

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
        <input type="text" id="last-sign-in-method" name="last-sign-in-method" value="email" hidden>
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
                    <input type="email" name="email" id="email" placeholder="example@domain.com" required>
                </div>
                <?php if(!filter_var($in_or_up, FILTER_VALIDATE_BOOL)) { ?>
                    <div class="email-phone-number-section">
                        <label class="unselectable" id="phone-label" for="phone">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone" placeholder="+65 93948788" required>
                    </div>
                <?php } ?>
                <div class="password-section">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="******" required>
                </div>
                <div class="submit-button-container">
                    <button class="unselectable" type="submit" id="submit-button"><?= $button_text ?></button>
                </div>
                <div class="no-have-account-container">
                    <span class="unselectable" id="no-have-account" onclick="go_to_sign_in_up()" required><?= $no_have_account_text ?></span>
                </div>
            </div>
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
            const sign_in_method = document.getElementById("last-sign-in-method");
            
            email_label.innerHTML = "Phone Number"; 
            email_label.setAttribute("for", "phone");
            email_label.setAttribute("id", "phone-label");
            email_input.setAttribute("name", "phone_number");
            email_input.setAttribute("type", "tel");
            email_input.setAttribute("id", "phone");
            email_input.setAttribute("placeholder", "+65 93948788");
            email_input.value = "";
            sign_in_method.value = "phone";
            
            sign_in_up_with.setAttribute("data-method", "phone");
            sign_in_up_with.innerHTML = "<?= $switch_method_text_prefix ?> " + "with email";
            
        } else if (method === "phone") {
            const phone_input = document.getElementById("phone");
            const phone_label = document.getElementById("phone-label");
            const sign_in_method = document.getElementById("last-sign-in-method");
            
            phone_label.innerHTML = "Email"; 
            phone_label.setAttribute("for", "email");
            phone_label.setAttribute("id", "email-label");
            phone_input.setAttribute("name", "email");
            phone_input.setAttribute("type", "text");
            phone_input.setAttribute("id", "email");
            phone_input.setAttribute("placeholder", "example@site.com");
            phone_input.value = "";
            sign_in_method.value = "email";

            sign_in_up_with.setAttribute("data-method", "email");
            sign_in_up_with.innerHTML = "<?= $switch_method_text_prefix ?> " + "with phone number";
        }
        
        return method;
    }
    
    <?php } ?>

    <?php if(
            filter_var($in_or_up, FILTER_VALIDATE_BOOL) && 
            isset($_GET["sign-up-error-msg"]) && 
            isset($_GET["sign-up-error-code"]) 
        ) 
    { ?>
        window.onload = function () {
            const req_params = new URLSearchParams(window.location.search);
            alert(req_params.get("sign-up-error-msg"));
        } 
    <?php } ?> 

    
    <?php if(!filter_var($in_or_up, FILTER_VALIDATE_BOOL)) { ?>
        (function () {
            const email_input = document.getElementById("email");
            const phone_input = document.getElementById("phone");
            const form = document.getElementById("sign-in-up-form");
            const password_input = document.getElementById("password");
            const sign_up_button = document.getElementById("submit-button");
        
            function check_email(email_str) {
                const email_reg = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
                const address_max_length = 255;
                const address_min_length = 6;
                
                if (address_max_length < email_str.length) {
                    alert("Email should not be longer than 255 characters");
                    email_input.classList.add("error");
                    return false;
                } 

                if (email_str.length < address_min_length) {
                    alert("Email should not be shorter than 6 characters");
                    email_input.classList.add("error");
                    return false;
                }

                if (!email_reg.test(email_str)) {
                    alert("Wrong email format");
                    email_input.classList.add("error");
                    return false;
                }

                return true;
            }

            function check_phone_number(phone_number) {
                const phone_reg = /^[\d]{8,14}$/;
                
                if (!phone_reg.test(phone_number)) {
                    alert("Wrong phone format");
                    phone_input.classList.add("error");
                    return false;    
                }

                return true;
            }

            function check_password(password) {
                const password_reg = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                if (!password_reg.test(password)) {
                    alert("Password must meet the following criteria:\n" +
                        "- At least 8 characters\n" +
                        "- At least one uppercase letter (A-Z)\n" +
                        "- At least one lowercase letter (a-z)\n" +
                        "- At least one number (0-9)\n" +
                        "- At least one special character (e.g., @, $, !, %, *, ?, &)");
                    password_input.classList.add("error");
                    password_input.addEventListener("focus", function() {
                        password_input.classList.remove("error");
                    });
                        
                    return false;
                }
            return true;
            }

            function handle_submit(e) {
                e.preventDefault();
                if (
                    check_email(email_input.value) && 
                    check_password(password_input.value) && 
                    check_phone_number(phone_input.value)
                ) {
                    form.submit();
                } 
            }

            sign_up_button.onclick = handle_submit;

        })();
        
    <?php } ?>
        
    <?php if(isset($_GET["sign-in-error-msg"]) && isset($_GET["sign-in-error-code"]) && isset($_GET["last-sign-in-method"]) && isset($_GET["in-or-up"])) { ?>
        (function () {
            const req_params = new URLSearchParams(window.location.search);
            let method = document.getElementById("sign-in-up-with").getAttribute("data-method");

            const SignInError = {
                INVALID_EMAIL: "69003",
                INVALID_PHONE: "69004",
                INVALID_PASSWORD: "69005",
            };

            if (req_params.get("last-sign-in-method") !== method) {
                method = switch_sign_in_method(null);
            }

            const email_input = document.getElementById("email");
            const phone_input = document.getElementById("phone");
            const password_input = document.getElementById("password");

            const error_code = req_params.get("sign-in-error-code");
            const error_msg = req_params.get("sign-in-error-msg");

            if (error_code === SignInError.INVALID_EMAIL && email_input) {
                email_input.classList.add("error");
                email_input.addEventListener("focus", function() {
                    email_input.classList.remove("error");
                });
            }

            if (error_code === SignInError.INVALID_PHONE && phone_input) {
                phone_input.classList.add("error");
                phone_input.addEventListener("focus", function() {
                    phone_input.classList.remove("error");
                });
            }

            if (error_code === SignInError.INVALID_PASSWORD && password_input) {
                password_input.classList.add("error");
                password_input.addEventListener("focus", function() {
                    password_input.classList.remove("error");
                });
            }

            window.onload = function () { alert(error_msg); }
        })();

    <?php } ?>
</script>




<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(SIGN_IN_PAGE_STYLE)
    ->set_navibar(NAV_LINKS, $username)
    ->set_outline(SIGN_IN_PAGE_SCRIPTS)
    ->render();
?>