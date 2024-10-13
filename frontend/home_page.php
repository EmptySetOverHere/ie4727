<?php 
require "./components/templates.php"

?>

<?php
$links = [
    "GPT" => "https://chatgpt.com",
    "GITHUB" => "https://github.com",
    "GOOGLE" => "https://google.com",
    "BING" => ""
];

(new PageTemplate()) 
    ->set_header("Nyan CATering", [])
    ->set_outline()
    ->set_navibar($links, "no one")
    ->set_footer()
    ->render();
?>


