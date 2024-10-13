<?php

require '../core/Image.php';
require '../core/NyanDB.php';
var_dump($_FILES['space']);

$sql = "
INSERT INTO imagetest (image_name, image_type, image_data)
VALUES (?, ?, ?);
";
NyanDB::single_query($sql, Image::explode($_FILES['space']));

echo "done. nyan.";

?>
