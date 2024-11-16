<?php

require_once 'NyanDB.php';
require_once 'Image.php';
require_once 'menu_items.php';

class MenuPackages{
    public static function get_all_packages(){
        $sql = "
        Select *
        From packages
        ";
        $results = NyanDB::single_query($sql, []);
        $result = mysqli_fetch_all($results, MYSQLI_ASSOC);
        $results->free();
        return $result;
    }

    public static function get_latest_packages(){
        $sql = "
        SELECT *
        FROM packages
        WHERE package_id IN (
            SELECT MAX(package_id)
            FROM packages
            GROUP BY item_name
        )
        AND is_available = true;
        ";
        $results = NyanDB::single_query($sql, []);
        $result = mysqli_fetch_all($results, MYSQLI_ASSOC);
        $results->free();
        return MenuPackages::format_result($result,'package_id');
    }

    public static function get_latest_instock_package_ids(){
        $sql = "
        SELECT p.package_id
        FROM packages p
        JOIN menu_items m1 ON p.main    = m1.menu_item_id AND m1.is_in_stock = 1
        JOIN menu_items m2 ON p.side    = m2.menu_item_id AND m2.is_in_stock = 1
        JOIN menu_items m3 ON p.dessert = m3.menu_item_id AND m3.is_in_stock = 1
        JOIN menu_items m4 ON p.drink   = m4.menu_item_id AND m4.is_in_stock = 1
        WHERE p.package_id IN (
            SELECT MAX(package_id)
            FROM packages
            GROUP BY item_name
        );
        ";
        $results = NyanDB::single_query($sql, []);
        $result = mysqli_fetch_all($results, MYSQLI_ASSOC);
        $results->free();
        return MenuPackages::format_result($result,'package_id');
    }

    public static function get_latest_instock_package_information(){
        $output = [];

        $instock_package_info = MenuPackages::get_latest_packages();
        foreach (MenuPackages::get_latest_instock_package_ids() as $package_array){
            $available_package_id = $package_array['package_id'];
            if(isset($instock_package_info[$available_package_id])){
                $package_info = $instock_package_info[$available_package_id];
                $package_info['main_name']    = MenuItems::get_menu_item_by_id($package_info['main'])['item_name'];
                $package_info['side_name']    = MenuItems::get_menu_item_by_id($package_info['side'])['item_name'];
                $package_info['dessert_name'] = MenuItems::get_menu_item_by_id($package_info['dessert'])['item_name'];
                $package_info['drink_name']   = MenuItems::get_menu_item_by_id($package_info['drink'])['item_name'];

                $package_info['img_src']      = MenuItems::get_associated_image_src($available_package_id)??null;
                $output[] = $package_info;
            }
        }

        return MenuPackages::format_result($output,'package_id');
    }

    public static function get_package_information_by_id($package_id){
        $sql = "
        SELECT * 
        FROM packages 
        WHERE package_id = ?
        ";
        $results = NyanDB::single_query($sql, [$package_id]);
        $result = $results->fetch_assoc();
        $results->free();
        $package_info = $result;
        $package_info['main_name']    = MenuItems::get_menu_item_by_id($package_info['main'])['item_name'];
        $package_info['side_name']    = MenuItems::get_menu_item_by_id($package_info['side'])['item_name'];
        $package_info['dessert_name'] = MenuItems::get_menu_item_by_id($package_info['dessert'])['item_name'];
        $package_info['drink_name']   = MenuItems::get_menu_item_by_id($package_info['drink'])['item_name'];
        return $package_info;
    }

    public static function get_associated_image_src($package_id){
        $sql = "
        SELECT * 
        FROM package_images 
        WHERE package_id = ?
        "
        ;
        $results = NyanDB::single_query($sql, [$package_id]);
        $result = $results->fetch_assoc();
        $results->free();
        if (!$result){return null;}
        return new Image($result['image_name'], $result['image_data'], $result['image_type']);
    }

    public static function set_availablility(int $package_id, bool $is_available){
        $sql = "
            UPDATE packages
            SET is_available = ?
            WHERE package_id = ?
        ";
        NyanDB::single_query($sql, [$is_available, $package_id]);
    }

    private static function format_result($array,$attribute){
        $output = [];
        foreach($array as $item){
            $key = $item[$attribute];
            $output[$key] = $item;
        }
        return $output;
    }
}

?>