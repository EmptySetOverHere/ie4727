<?php

class Image 
{
    //usage:
    // $result=[$imageName, $imageData, $imageType]
    // $src = Image::from_array(...$result)->src()
    // this $src is used in html <img>
    private $imageName;
    private $imageData;
    private $imageType;

    public function __construct($imageName, $imageData, $imageType) {
        // Initialize properties
        $this->imageName = $imageName;
        $this->imageData = $imageData;
        $this->imageType = $imageType;
    }

    public static function from_array($raw) {
        return new Image($raw['name'], file_get_contents($raw['tmp_name']), $raw['type']);
    }

    public static function src($imageData, $imageType){
        return (new Image("", $imageData, $imageType))->getsrc();
    }

    public function getsrc(){
        return 'data:' . $this->imageType . ';base64,' . base64_encode($this->imageData);
    }

    public function getname(){
        return $this->imageName;
    }

    public static function explode($raw){
        return [$raw['name'], file_get_contents($raw['tmp_name']), $raw['type']];
    }
}


////random junk code sitting down here, dont mind us we are the code slum :)
// var_dump($_FILES['space']);
// $imageData = file_get_contents($_FILES['space']['tmp_name']); // Binary data
// $imageName = $_FILES['space']['name']; // Original file name
// $imageType = $_FILES['space']['type']; // MIME type
// $base64 = base64_encode($imageData);
// $imageSrc = 'data:'. $imageType . ';base64,' . $base64;
// echo '<img src="' . htmlspecialchars($imageSrc) . '" alt="' . htmlspecialchars($imageName) . '" style="max-width: 100%; height: auto;">';

// require '../core/Image.php';
// var_dump($_FILES['space']);
// echo '<img src="' . Image::from_array($_FILES['space'])->src() . '" style="max-width: 100%; height: auto;">';

// require '../core/Image.php';
// var_dump($_FILES['space']);
// $imageData = file_get_contents($_FILES['space']['tmp_name']); // Binary data
// $imageName = $_FILES['space']['name']; // Original file name
// $imageType = $_FILES['space']['type']; // MIME type
// echo '<img src="' . Image::src($imageData, $imageType) . '" style="max-width: 100%; height: auto;">';

?>
