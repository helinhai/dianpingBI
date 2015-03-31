<?php

include_once('WebPageGrabber.class.php');
include_once('./pinyin/Pinyin.class.php');
include_once('DianpingAPI.class.php');
include_once('Business.class.php');
include_once('DBhandler.class.php');
include_once('./utils.class.php');
include_once('./Dianping_CityList.class.php');





function get_city_list () {
    $obj = new Dianping_CityList();
    $city_list = $obj->get_city_list_id();
    trace($city_list);
    return $city_list;
}

function get_sports_businesses_id ( $city ) {
    $grabber = new WebPageGrabber($city);
    $id_list = $grabber->grab_all_sport_businesses();
    trace("All businesses ID in $city:");
    trace($id_list);
    unset($grabber);
    return $id_list;
}

function get_businesses_details ( $id_list ) {
    $business_list = array();
    
    foreach ( $id_list as $id ) {
        $dianping = new DianpingAPI_Business();
        trace($id);
        $dianping->set_BusinessID($id);
        $data = $dianping->request();
        if ( $data['status'] == 'OK' && $data['count'] == 1 ) {
            $business_details = $data['businesses'];
            array_push($business_list, new Business($business_details[0]));
        }
        else {
            trace("Meet some errors while collecting business : ".$id);
            trace("THIS BUSINEES WON'T BE ADDED INTO DB!!!");
            if ( $data['status'] == 'ERROR' && $data['error']['errorCode'] == 10007 ) {
                trace("You have reached the daily limit. 当日API访问量已达到上限");
                trace("Program stoped!");
                exit;
            }
            continue;
        }
    }
    return $business_list;
}

function push_businesses_details ( $id_list ) {
    foreach ( $id_list as $id ) {
        $dianping = new DianpingAPI_Business();
        trace($id);
        $dianping->set_BusinessID($id);
        $data = $dianping->request();
        if ( $data['status'] == 'OK' && $data['count'] == 1 ) {
            $business_details = $data['businesses'];
            add_businesses_to_db(array(new Business($business_details[0])));
        }
        else {
            trace("Meet some errors while collecting business : ".$id);
            trace("THIS BUSINEES WON'T BE ADDED INTO DB!!!");
            if ( $data['status'] == 'ERROR' && $data['error']['errorCode'] == 10007 ) {
                trace("You have reached the daily limit. 当日API访问量已达到上限");
                trace("Program stoped!");
                exit;
            }
            continue;
        }
    }
}



function add_businesses_to_db ( $business_list ) {
    $db_handler = new DBhandler();
    foreach ( $business_list as $obj ) {
        $db_handler->execQuery($obj->businessQuery());
        $db_handler->execQuery($obj->regionQuery());
        $not_insert_categories = $obj->get_not_inserted_categories();       
        if ( !(empty($not_insert_categories)) ) {
            $sql = $obj->addCategoriesQuery($not_insert_categories);
            foreach ( $sql as $i ) {
                $db_handler->execQuery($i);
            }
            Business::UPDATE_CATEGORIES_LIST();//更新类型列表
        }
        $id_list = $obj->get_category_ids();
        foreach( $id_list as $category_id ) {
            $db_handler->execQuery($obj->setRelationBusinessCategoriesQuery($category_id));
        }
    }
}

function get_id_file ( $city , $last_id = null) {
    $file = "./cities/$city".".city";
    trace("Starting to fetch $city'"."s businesses information...");
    trace("Set file path as: $file");
    
    $file_handle = fopen($file, 'r');
    if ( !$file_handle )
        die("failed to located file: ".$file);
    $id_list = array();
    while ( !feof($file_handle) ) {
        $id = str_replace(PHP_EOL, '', fgets($file_handle));
 	$id = trim($id);
        if ( empty($id) )
            continue;
        if ( !empty($last_id) ) {
          if ( $id != $last_id )
  	    continue;
          else {
            trace("Located last id index: $id");
            trace("Now starting to fetch business id...");
            trace("Fetched business id: ".$id);
            array_push($id_list, $id);
	    $last_id = null;
  	    continue;
          }
        }
        trace("Fetched business id: ".$id);
        array_push($id_list, $id);
    }
    fclose($file_handle);
    return $id_list;
}

function start ( $city ) {
    #Business::UPDATE_CATEGORIES_LIST();
    $id_list = get_id_file($city);
    push_businesses_details($id_list);
    trace("Captured finished...");
    rename("./cities/$city".".city", "./cities/$city".".city.passed");
}


function fetch_businesses_id () {
    $city_list = get_city_list();
    #$city_list = array('shenzhen');
    foreach ( $city_list as $city ) {
        $id_list = array_unique( get_sports_businesses_id($city) );
        foreach ( $id_list as $id ) {
            file_put_contents("./cities/$city".'.city', $id.PHP_EOL,  FILE_APPEND);
        }
    }
}

#fetch_businesses_id();
start('beijing');

?>
