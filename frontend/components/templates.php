<?php
require_once "./utilities/config.php";

class PageTemplate
{
    // private static int $instance = 0;
    private ?string $content = null;
    private string $header_title;
    private string $header;
    private string $outline;
    private string $navibar;
    private string $footer;

    private const NAVIBAR_PLACEHOLDER       = "{{navibar}}";
    private const CONTENT_PLACEHOLDER       = "{{content}}";
    private const FOOTER_PLACEHOLDER        = "{{footer}}";

    public function __construct()
    {
        $this->content = <<<HTML
            <div id="dummy-content"></div>
        HTML;
    }

    public function set_content(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function set_header(array $style_files, string $title = PROJECT_TITLE, ): self
    {
        $this->header_title = $title;
        $this->header = <<<HTML
            <!DOCTYPE html> 
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>$title</title>
        HTML;
        foreach ($style_files as $style_file) {
            $this->header .= <<<STYLE
                <link rel="stylesheet" href="$style_file"> 
            STYLE;
        }
        $this->header .= <<<HTML
            </head>
        HTML;

        // echo $this->header;
        return $this;
    }

    public function set_outline(array $scripts): self
    {
        $NAVIBAR_PLACEHOLDER = self::NAVIBAR_PLACEHOLDER;
        $CONTENT_PLACEHOLDER = self::CONTENT_PLACEHOLDER;
        $FOOTER_PLACEHOLDER  = self::FOOTER_PLACEHOLDER;

        $this->outline = <<<HTML
            <body>
            <div class="screen-adjuster">
                <div class="main-outline">
                    <div class="nav-bar-outline">
                        $NAVIBAR_PLACEHOLDER
                    </div>
                    <div class="secondary-outline">
                        $CONTENT_PLACEHOLDER
                    </div>
                    <div>
                        $FOOTER_PLACEHOLDER    
                    </div>
                </div>
            </div>
            
        HTML;

        foreach ($scripts as $script_file) {
            $this->outline .= <<<HTML
                <script src="$script_file" defer></script>
            HTML;
        }

        $this->outline .= <<<HTML
            </body>
        HTML;

        return $this;
    }


    public function set_navibar(array $links, ?string $username = null)
    {
        // appending restaurant icon and name       
        
        $this->navibar = <<<HTML
            <nav class="navi-bar-container unselectable">
                <div class="cater-icon-name-container">
                    <div class="cater-icon">
                        <img src="./assets/cat-space.gif" alt="NyanCat">
                    </div>
                    <div class="cater-name">
                        {$this->header_title}
                    </div>
                </div>
                <div class="navi-links-container">
        HTML;

        // appending navigation links in the navibar
        foreach ($links as $category => $content) {
            $this->navibar .= <<<HTML
                <div class="navi-link-container">
            HTML;

            if (is_array($content)) {
                foreach ($content as $sub_cat => $sub_content) {
                    // TODO: we populate the dropdown list if there is subcontent for display
                }
            } else {
                $this->navibar .= <<<HTML
                    <a href="$content">$category</a>
                HTML;
            }
            $this->navibar .= "</div>";
        }

        // appending sign in sign up button
        $this->navibar .= <<<HTML
                </div>
                <div class="spacer"></div>
        HTML;

        // appending welcome message if the user has signed in 
        if ($username !== null) {
            $this->navibar .= <<<HTML
                <div class="welcome-message-container">
                    <span>
                        Welcome $username <br>
                    </span>
                </div>
            HTML;
        }

        if ($username === null) {
            $this->navibar .= <<<HTML
                <button class="sign-in-up-container">
                    <a class="sign-in-sign-up" href="./sign_in_up_page.php">
                        Sign In
                    </a>
                </button>
                </nav>
            HTML;
        } else {
            $this->navibar .= <<<HTML
                    <button class="account-avatar-container" onclick="avatar_onclick()">
                        <img src="./assets/cat-avatar.png" alt="">
                    </button>
                </nav>
            HTML;
        }

        return $this;
    }

    public function set_footer()
    {
        $this->footer = <<<HTML
            <footer>
                <Address>
                    <div class="footer-text-title">Address</div>
                    <div class="footer-text-body">
                        Nyan Cat Residence              <br>
                        42 Rainbow Lane                 <br>
                        Pixelville, Internet Universe   <br>  
                        Purrfect Galaxy, 001010         <br>
                        Cyberspace                      <br>
                    </div>
                </Address>
                <div>
                    <div class="footer-text-title">Email</div>
                    <div class="footer-text-body">nyancat@localhost.org</div>
                </div>
                <div class="footer-text-title">Participate</div>
                <div class="footer-text-title">Term of Service</div>
                <div class="footer-text-title">Policy</div>
                <div class="footer-text-title">Cookie Policy</div>
            </footer>
        HTML;
        return $this;
    }

    public function render()
    {
        $placeholders = [
            self::NAVIBAR_PLACEHOLDER,
            self::CONTENT_PLACEHOLDER,
            self::FOOTER_PLACEHOLDER,
        ];

        $body = str_replace(
            $placeholders, 
            [$this->navibar, $this->content, $this->footer], 
            $this->outline
        );
        
        $page = $this->header . $body;
        echo $page;
    }

    public static function getJavascriptAlertMessage(): string {
        if (isset($_GET['alert_msg'])) {
            // Use json_encode to safely format the message for JavaScript
            $message = json_encode($_GET['alert_msg']);
            return "<script>alert($message);</script>";
        }
        return ''; // Return an empty string if no alert message is set
    }
}
?>
