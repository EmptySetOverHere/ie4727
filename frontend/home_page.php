<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);   

ob_start(); //start buffer to collect generated html lines
?>

<div class="home-page-container">
    <?php require "./components/slideshow.php" ?>

    <div class="front-layer">
        <div class="home-page-grid-container">
            <div class="slogan-container">
                <h1 class="slogan">Bringing Purrfection to Every Occasion, One Bite at a Time!</h1>
            </div>
            <div class="placeholder"></div>
            <div class="about-us-container">
                <div class="about-us-title">
                    <p>Who are we?</p>
                </div>
                <div class="about-us-body">         
                    <div> 
                        <p>
                            At Nyan CATering, we’re more than just a catering service — we’re a team of passionate food lovers dedicated to delivering exceptional meals with a fun, whimsical touch. Inspired by the playful spirit of Nyan Cat, we bring creativity and joy to every event, offering a wide variety of delicious dishes that cater to all tastes and dietary needs.
                        </p>
                    </div>
                    <div>
                        <p>
                            Whether you’re hosting a large gathering or just need a quick, tasty meal, Nyan CATering has you covered. Our user-friendly platform allows you to easily browse our menu, customize your order, and track it in real-time, ensuring a smooth and delightful experience from start to finish. With a commitment to quality and customer satisfaction, we’re here to make every meal a purrfectly memorable one!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="partners-show-container">
        <div class="partners-section-title">Our Partners</div>
        <div class="partners-scrolling-widget">
            <div class="partners-container-inner">
                <div class="partner">
                    <div class="icon"><img src="./assets/cat-space.gif" alt=""></div>
                    <div class="description"><span>Partner 1</span></div>
                </div>
                <div class="partner">
                    <div class="icon"><img src="./assets/cat-space.gif" alt=""></div>
                    <div class="description"><span>Partner 2</span></div>
                </div>
                <div class="partner">
                    <div class="icon"><img src="./assets/cat-space.gif" alt=""></div>
                    <div class="description"><span>Partner 3</span></div>
                </div>
                <div class="partner">
                    <div class="icon"><img src="./assets/cat-space.gif" alt=""></div>
                    <div class="description"><span>Partner 4</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(HOME_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username)
    ->set_outline(HOME_PAGE_SCRIPTS)
    ->render();
?>