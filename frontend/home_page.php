<?php 
require_once "./components/templates.php"
?>

<?php
$nav_links = [
    "GPT" => "https://chatgpt.com",
    "GITHUB" => "https://github.com",
    "GOOGLE" => "https://google.com",
    "BING" => ""
];

$styles = [
    "./styles/templates.css", //This must be specified by default
];

$scripts = [
    "./scipts/templates.js", //This must be specified by default
];

(new PageTemplate())
    ->set_footer()
    ->set_header("Nyan CATering", $styles)
    ->set_navibar($nav_links, "no one")
    ->set_outline($scripts)
    ->render();
?>