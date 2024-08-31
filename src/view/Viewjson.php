<?php


class Viewjson {

     
    /*JSON*/
    public static function render($key,$data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
