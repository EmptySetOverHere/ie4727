<?php

require_once '../core/NyanDB.php';
require_once '../core/Image.php';

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
        );
        ";
        $results = NyanDB::single_query($sql, []);
        $result = mysqli_fetch_all($results, MYSQLI_ASSOC);
        $results->free();
        return $result;
    }

    public static function display_latest_available_package_id(){
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
        return $result;
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
        return new Image($result[image_name], $result[image_data], $result[image_type]);
    }

    public static function set_availablility(int $package_id, bool $is_available){
        $sql = "
            UPDATE packages
            SET is_available = ?
            WHERE item_id = ?
        ";
        NyanDB::single_query($sql, [$is_available, $item_id]);
    }
}

?>