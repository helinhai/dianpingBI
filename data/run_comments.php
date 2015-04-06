<?php

include_once('WebPageGrabber.class.php');
include_once('./pinyin/Pinyin.class.php');
include_once('DianpingAPI.class.php');
include_once('DBhandler.class.php');
include_once('./utils.class.php');
include_once('./Dianping_CityList.class.php');
include_once('./grab_comments.php');





function push_businesses_comments_details ( $id_list ) {
    foreach ( $id_list as $id ) {
        $dianping = new DianpingAPI_Comments();
        trace($id);
        $dianping->set_BusinessID($id);
        $data = $dianping->request();
        if ( $data['status'] == 'OK' && $data['count'] >= 1 ) {
            $comments_details = $data['reviews'];
            for($i=0;$i<$data['count'];$i++)
            {
                $business_id=$id;
                $review_id=$comments_details[$i]['review_id'];
                $user_nickname=$comments_details[$i]['user_nickname'];
                $text_excerpt=$comments_details[$i]['text_excerpt'];
                $product_rating=$comments_details[$i]['product_rating'];
                $decoration_rating=$comments_details[$i]['decoration_rating'];
                $service_rating=$comments_details[$i]['service_rating'];
                $created_time=$comments_details[$i]['created_time'];
                $comments_sql="INSERT INTO `helinhai`.`comments` (`business_id`, `review_id`, `user_nickname`, `text_excerpt`, `product_rating`, `decoration_rating`, `service_rating`, `created_time`) VALUES ('$business_id', '$review_id', '$user_nickname', '$text_excerpt', '$product_rating', '$decoration_rating', '$service_rating', '$created_time');";
                $db_handler = new DBhandler();
                $db_handler->execQuery($comments_sql);
            }
        }
        else {
            trace("Meet some errors while collecting comments : ".$id);
            trace("THIS COMMENTS WON'T BE ADDED INTO DB!!!");
            if ( $data['status'] == 'ERROR' && $data['error']['errorCode'] == 10007 ) {
                trace("You have reached the daily limit. 当日API访问量已达到上限");
                trace("Program stoped!");
                exit;
            }
            continue;
        }
    }
}


function get_id_file ( $city , $last_id = null) {
    $file = "./cities/$city".".city.passed";
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
function push_comments_details ( $id_list ) {
    foreach ( $id_list as $id ) {
        $dianping = new grab_comments($id);
        $data = $dianping->get_business_comments();
        $business_id=$id;
        $comment_1=$data[0];
        $comment_2=$data[1];
        $comment_3=$data[2];
        $comment_4=$data[3];
        $comments_sql="INSERT INTO `helinhai`.`comments` (`business_id`, `comment_1`, `comment_2`, `comment_3`, `comment_4`) VALUES ('$business_id', '$comment_1', '$comment_2', '$comment_3', '$comment_4');";
        $db_handler = new DBhandler();
        $db_handler->execQuery($comments_sql);
        }
    }
function start ( $city ) {
    $id_list = get_id_file($city);
    push_comments_details($id_list);
    trace("Captured finished...");
}
#fetch_businesses_id();

start('beijing');
?>
