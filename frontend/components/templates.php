<?php
class PageTemplate
{

    private static int $instance = 0; // Singleton
    private mixed $content;
    private string $header_title;

    public function __construct() {
        if (self::$instance > 0) {
            throw new Exception("There page template should only be rendered once");
        }
        self::$instance += 1;
    }

    public function set_content(mixed $content): self {
        $this->content = $content;
        return $this;
    }

    public function render_content(...$args) {
        if (isset($content)) {
            ($this->content)(...$args);
        }
        return $this;
    }


    public function render_header(string $title, array $style_files) {
        $this->header_title = $title;
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?></title>
            <link rel="stylesheet" href="templates.css">
            <?php foreach ($style_files as $style_file): ?>
                <link rel="stylesheet" href=" <?= $style_file ?>">
            <?php endforeach ?>
        </head>

    <?php
        return $this;
    }

    public function render_outline() {
    ?>
        <div class="screen-adjuster">
            <div class="main-outline">
                <div class="nav-bar-outline">
        <?php
        return $this;
    }


    public function render_navibar(array $links, string $username) {
        ?>
            <nav class="navi-bar-container">
                <div class="cater-icon-name-container">
                    <div class="cater-icon">
                        <img src="../assets/cat-space.gif" alt="NyanCat">
                    </div>
                    <div class="cater-name">
                        <?= $this->header_title ?>
                    </div>
                </div>
                <div class="navi-links-container">
                    <?php foreach ($links as $category => $content): ?>
                        <div class="navi-link-container">
                            <!-- TODO: Implementing drop-down if we have a nested navigation contents -->
                            <?php if (is_array($content)): ?>
                                <?php foreach ($content as $sub_cat => $sub_content): ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <a href="<?= $content ?>"><?= $category ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="spacer"></div>
                <button class="sign-in-up-container">
                    <div class="avatar-container"></div>
                    <span class="sign-in-sign-up">
                        <?php if (isset($this->username)): ?>
                            Welcome
                            <?= $username ?>
                            <br>
                        <?php endif; ?>
                        Sign-in / Sign-up
                    </span>
                </button>
            </nav>
        <?php
        return $this;
    }

    public function render_footer()
    {
        ?>
        </div>
        </div>
        </div>
        </div>
        <?php
        return $this;
    }
    }
?>


<?php
$links = [
    "GPT" => "https://chatgpt.com",
    "GITHUB" => "https://github.com",
    "GOOGLE" => "https://google.com",
    "BING" => ""
];

(new PageTemplate())
    ->render_header("Nyon CATering", [])
    ->render_outline()
    ->render_navibar($links,"no one")
    ->render_content()
    ->render_footer();
?>