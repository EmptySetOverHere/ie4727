(function () {
    const sign_in_button = document.querySelector(".sign-in-up-container");
    sign_in_button.onclick = function (_e) {
        window.location.assign("./sign_in_page.php");
    }
})();