<?php

require_once '../core/Image.php';
require_once '../core/NyanDB.php';
require_once '../core/menu_items.php';

// var_dump(MenuItems::get_latest_menu_items());
// var_dump(MenuItems::get_menu_item_price(9));

/////i named my input field 'space' in this example
// var_dump($_FILES['space']);

// $sql = "
// INSERT INTO imagetest (image_name, image_data, image_type)
// VALUES (?, ?, ?);
// ";
// echo $_FILES['space']["type"];
// NyanDB::single_query($sql, Image::explode($_FILES['space']));


// $sql = "
// SELECT image_name, image_data, image_type
// FROM imagetest 
// WHERE user_id = 2
// ";
// $results = NyanDB::single_query($sql,[]);
// $result = $results->fetch_assoc();


// echo '<img src="' . Image::src($result['image_data'], $result['image_type']) . '" style="max-width: 100%; height: auto;">';

// $results->free();

// echo date('H:i:s, jS F Y');
// $sql = "
// INSERT into timetest (time_stuff)
// VALUES ( ? )
// ";
// $results = NyanDB::single_query($sql,[date('Y-m-d H:i:s')]);
// // $result = $results->fetch_assoc();

$time_ting = new DateTime('2024-10-20 10:07', new DateTimeZone('Asia/Singapore'));
// echo new Date('Y-m-d H:i:s');
echo $time_ting->format('Y-m-d H:i:s');

echo "done. nyan.";

?>
