<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);

// $_POST['is_']

ob_start(); //start buffer to collect generated html lines
?>

<div class='centered-items account-setting-content-container' style="height: 200px; align-items: center;">
    <div class="centered-items">Welcome, <?=$username?></div>
    <br>

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