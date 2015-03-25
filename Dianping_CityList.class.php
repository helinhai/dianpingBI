<?php


include_once('./vendors/simple_html_dom.php');
include_once('./Snoopy/Snoopy.class.php');
include_once('./utils.class.php');

class Dianping_CityList {
    private $URL = "http://www.dianping.com/citylist";
    private $SNOOPY;
    
    public function __construct() {
        $this->SNOOPY = new Snoopy();
    }
    
    public function get_city_list_id () {
        $this->SNOOPY->fetch($this->URL);
        $html = str_get_html( $this->SNOOPY->results );
        $city_list = array();
        
        foreach ( $html->find('div.terms a') as $city) {
            if ( $city->href == '#' )
                continue;
            trace("Captured city: ".$city->plaintext." => ". $city->href);
            $city_py = str_replace('/', '', $city->href);
            trace("Captured city pinpyn: $city_py");
            array_push($city_list, $city_py);
        }
        $city_list = array_unique($city_list);
        trace($city_list);
        return $city_list;
    }
}



?>
