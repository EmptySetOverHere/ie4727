(function () {
    const sign_in_button = document.querySelector(".sign-in-up-container");

    if(sign_in_button === null) { return; }

    sign_in_button.onclick = function (_e) {
        window.location.assign("./sign_in_up_page.php");
    }
})();


function avatar_onclick(e) {
    window.location.assign("./account_setting_page.php");
}
