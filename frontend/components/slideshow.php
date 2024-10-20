<?php 
    require_once "./utilities/config.php";
    $image_files = scandir(SLIDE_SHOW_IMAGE_DIRECTORY);
    array_shift($image_files);
    array_shift($image_files);
?>

<div class="catering-slide-show-container">
    <?php foreach($image_files as $i => $file) { 
        $opacity = $i === 0 ? 1 : 0; 
    ?>
        <img class="slider-image" src="<?= SLIDE_SHOW_IMAGE_DIRECTORY . '/' . $file ?>" alt="" style="opacity: <?= $opacity ?>;">
    <?php } ?>
</div>