<?php

require_once 'NyanDB.php';
require_once 'Image.php';

class MenuItems{
    public static function get_all_menu_items(){
        $sql = "
        Select *
        From menu_items
        ";
        $results = NyanDB::single_query($sql, []);
        $result = mysqli_fetch_all($results, MYSQLI_ASSOC);
        $results->free();
        return $result;
    }

    public static function get_latest_valid_menu_items($offset=0, $limit=5, $name_filter=null){
        $sql = "
        SELECT *
        FROM menu_items
        WHERE 
            is_in_stock = 1
            AND 
            menu_item_id IN (
                SELECT MAX(menu_item_id)
                FROM menu_items
                GROUP BY item_name
            ) ";
        
        if ($name_filter) {
            $sql .= " AND item_name LIKE ? ";
            $name_filter = "%" . $name_filter . "%";
        }

        $sql .= " LIMIT ? OFFSET ?;";

        $params = ($name_filter) ? [$name_filter, $limit, $offset] : [$limit, $offset]; //TADA
        $results = NyanDB::single_query($sql, $params);
        $result = mysqli_fetch_all($results, MYSQLI_ASSOC);
        $results->free();
        return $result;
    }

    public static function get_latest_valid_menu_items_count($name_filter=null){
        $sql = "
        SELECT COUNT(*) AS total_count
        FROM menu_items
        WHERE 
            is_in_stock = 1
            AND 
            menu_item_id IN (
                SELECT MAX(menu_item_id)
                FROM menu_items
                GROUP BY item_name
            ) ";
        
        if ($name_filter) {
            $sql .= " AND item_name LIKE ? ";
            $name_filter = "%" . $name_filter . "%";
        }

        $params = ($name_filter) ? [$name_filter] : [];
        $results = NyanDB::single_query($sql, $params);
        $countRow = mysqli_fetch_assoc($results); 
        $results->free();
        return (int)$countRow['total_count'];
    }

    public static function get_menu_item_by_id($menu_item_id){
        $sql = "
        SELECT *
        FROM menu_items
        WHERE menu_item_id = ?
        ";
        $results = NyanDB::single_query($sql, [$menu_item_id]);
        if(!empty($result)){
            return null;
        }
        $result = $results->fetch_assoc();
        $results->free();
        return $result ?? null;
    }

    public static function get_associated_image_src($menu_item_id){
        $sql = "
        SELECT *
        FROM menu_item_images
        WHERE menu_item_id = ?
        ";
        $results = NyanDB::single_query($sql, [$menu_item_id]);
        $result = $results->fetch_assoc();
        $results->free();
        if(empty($result)){
            return null;}
        $image = new Image($result['image_name'], $result['image_data'], $result['image_type']);
        return $image.getsrc();
    }

    public static function set_availablility(int $menu_item_id, bool $is_in_stock){
        $sql = "
            UPDATE menu_items
            SET is_in_stock = ?
            WHERE item_id = ?
        ";
        NyanDB::single_query($sql, ['is_available', 'item_id']);
    }
}

?>