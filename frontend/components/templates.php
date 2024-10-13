<?php
class PageTemplate
{

    private static int $instance = 0; // Singleton
    private ?string $content = null;
    private string $header_title;
    private string $header;
    private string $outline;
    private string $navibar;

    private const NAVIBAR_PLACEHOLDER = "{{navibar}}";
    private const CONTENT_PLACEHOLDER = "{{content}}";

    public function __construct() {
        if (self::$instance > 0) {
            throw new Exception("There page template should only be rendered once");
        }
        self::$instance += 1;
    }


    public function set_content(string $content): self {
        $this->content = $content;
        return $this;
    }

    public function set_header(string $title, array $style_files): self {
        $this->header_title = $title;
        $this->header = <<<HTML
            <!DOCTYPE html> 
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>$title</title>
                <link rel="stylesheet" href="templates.css">
        HTML;
        foreach($style_files as $style_file) {
            $this->header .= <<<STYLE
                <link rel="stylesheet" href="$style_file"> 
            STYLE;
        }
        $this->header .=  <<<HTML
            </head>
        HTML;
        return $this;
    }

    public function set_outline(): self
    {   
        $NAVIBAR_PLACEHOLDER = $this->NAVIBAR_PLACEHOLDER;
        $CONTENT_PLACEHOLDER = $this->CONTENT_PLACEHOLDER;
        $this->outline = <<<HTML
        <body>
        <div class="screen-adjuster">
            <div class="main-outline">
                <div class="nav-bar-outline">
                    $NAVIBAR_PLACEHOLDER
                </div>
                <div class="page-content-container">
                    $CONTENT_PLACEHOLDER
                </div>
            </div>
        </div>
        HTML;
        return $this;
    }

    public function demo(): void
    {
        ?>  
        <?php
    }

    

    public function set_navibar(array $links, ?string $username = null) {
        // appending restaurant icon and name        
        $this->navibar = <<<HTML
            <nav class="navi-bar-container">
                <div class="cater-icon-name-container">
                    <div class="cater-icon">
                        <img src="../assets/cat-space.gif" alt="NyanCat">
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
                foreach($content as $sub_cat => $sub_content) {
                    // TODO: we populate the dropdown list if there is subcontent for display
                }
            } else {
                $this->navibar .= <<<HTML
                    <a href="$content">$category</a>
                HTML;
            }
            
            $this->navibar .= "</div> /n";
        }

        // appending sign in sign up button
        $this->navibar .= <<<HTML
                </div>
                <div class="spacer"></div>
                <button class="sign-in-up-container">
                    <div class="avatar-container"></div>
                    <span class="sign-in-sign-up">
        HTML;

        // appending welcome message if the user has signed in 
        if ($username !== null) {
            $this->navibar .= <<<HTML
                Welcome $username <br>
            HTML;
        } 

        $this->navibar .= <<<HTML
             Sign-in / Sign-up
                    </span>
                </button>
            </nav>
        HTML;

        return $this;
    }

    public function set_footer()
    {
        return $this;
    }

    public function render() {
        $placeholders= [
            $this::NAVIBAR_PLACEHOLDER, 
            $this::CONTENT_PLACEHOLDER
        ];

        
        $this->outline = str_replace($placeholders, [$this->navibar, $this->content], $this->outline);
        $page = $this->header . $this->outline;
        echo $page;
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
    ->set_header("Nyan CATering", [])
    ->set_outline()
    ->set_navibar($links, "no one")
    ->set_footer()
    ->render();
?>