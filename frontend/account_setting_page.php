<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

aquire_username_or_default(DEFAULT_USERNAME);

ob_start(); //start buffer to collect generated html lines
?>

<!-- TODO: write your inner html content here -->
<!-- the entire html content will be treated as a string later  -->

<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    // ->set_content($content)
    ->set_header("", STYLES)
    ->set_navibar(NAV_LINKS, $usernane="guest")
    ->set_outline(SCRIPTS)
    ->render();
?>