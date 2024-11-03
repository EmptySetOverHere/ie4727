(function () {
    const sign_in_button = document.querySelector(".sign-in-up-container");

    if(sign_in_button === null) { return; }

    sign_in_button.onclick = function (_e) {
        window.location.assign("./sign_in_up_page.php");
    }
})();

(function () {
    const navi_bar_container = document.querySelector(".navi-bar-container");
    const navi_link_container = document.querySelector(".navi-link-container");
    const sign_in_button = document.querySelector(".sign-in-up-container");
    const welcome_message_container = document.querySelector("welcome-message-container");

    
})();

function avatar_onclick(e) {
    window.location.assign("./account_setting_page.php");
}
