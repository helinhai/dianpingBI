<?php


#set_time_limit(0);
include_once('./vendors/simple_html_dom.php');
include_once('./Snoopy/Snoopy.class.php');
include_once('./utils.class.php');

define('PREFIX', 'http://www.dianping.com');



class WebPageGrabber {
  private $_city;
  private $_url;
  private $_snoopy;
  
  private function _init ( $city ) {
    $this->_city = $city;
    trace("Set city as => ".$this->_city);
    $this->_url = PREFIX.'/'.$this->_city.'/sports';
    trace("URL => ".$this->_url);   
  }
  
  private function _fetch ( $url ) {
    if ( isset($this->_snoopy) )
      unset($this->_snoopy);
    $this->_snoopy = new Snoopy();
    $this->_snoopy->fetch($url);
    //trace("Snoopy captured content from $url");
    return $this->_snoopy->results;
  }
  
  
  public function __construct ( $city ) {
    $this->_init($city);
  }
  
  //获得www.dianping/cityname/sports 下的分类链接
  //@return 关联数组
  public function get_sports_category_urls ( $url ) {
    $html = str_get_html($this->_fetch($url));
    $category_list = array();
    $result = $html->find('li.term-list-item');
    if( empty($result) ){
      trace("NO SPORTS BUSINESSES DETECTED!!!");
      return array();
    }
    foreach ( $result as $li_tag ) {
      if ( $li_tag->find('strong.term')[0]->plaintext == "运动分类:" ) {
        trace("Detected sports categories...");
        foreach ( $li_tag->find('ul.desc li') as  $li ) {
          $sport = $li->find('a', 0);
          $href = $sport->href;
          $title = $sport->title;
          if ( !eregi("shoplist", $href) ) {
            $href = PREFIX.$href;
            $category_list[$title] = $href;
            trace("captured: $title => $href");
          }
        }
      }
      else {
        trace("NO SPORTS BUSINESSES DETECTED!!!");
      }
    }
    return $category_list;
  }


 
  /**
   *获得每一个每类下每一页的商户id
   *@return array
   */
  public function get_business_id ( $url ) {
    $html = str_get_html($this->_fetch($url));
    #$html = file_get_html($url);
    $list = $html->find('a[data-hippo-type=shop]');
    $id_list = array();
    foreach ( $list  as $i ) {
      //$href = PREFIX.$i->href;
      $href = $i->href;
      trace("Fetch business url => $href");    
      //preg_match('/http:\/\/www.dianping.com\/shop\/(.*)/', $href, $id,
      preg_match('/\/shop\/(.*)/', $href, $id,
               PREG_OFFSET_CAPTURE);
      if ( $id != null ) {
        array_push($id_list, $id[1][0]);
        trace("captured business id: ".$id[1][0]);
      }
    }
    return $id_list;
  }
  
  /**
   *检查当前类型是否还有下一页，如果有返回下一页url，反之则返回null
   *@return String or false
   */
  public function has_next_page ( &$url ) {
    $html = str_get_html($this->_fetch($url));
    if ( empty( $html ) ) {
      trace("has_next_page function: failed to create html object, retrying...");
      trace("URL: $url");
      $html = str_get_html($this->_fetch($url));
    }
    $next_page_info = $html->find('a.next', 0);
    if ( $next_page_info == null ) {
      trace("No next page detected.");
      return false;
    }
    else {
      $href = PREFIX.$next_page_info->href;
      trace("Located next page url=>$href");
      $url = $href;
      return true;
    }
  }
  
  /**
   *此类的主函数，根据初始化设定的城市，抓取其所有运动类商户id
   *@return array of business id
   */
  public function grab_all_sport_businesses () {
    trace("Starting to capture ".$this->_city."'s businesses...");
    $business_id_list = array();
    //得到所有运动商户类别
    $category_list = $this->get_sports_category_urls($this->_url);
    //进行循环获取
    while ( list($category, $url) = each ($category_list) ) {
      trace("Tring to capture category $category from $url...");
      do {
        //获得当前页上的说有商户id，并存入$business_id_list
        $business_id_list = array_merge($business_id_list, $this->get_business_id($url));
        //判断是否有下一页，如果有则继续循环，无则结束循环，获取当前类型的所有商户id完毕
      }while ( $this->has_next_page($url));
    }
    trace("Collecting ".$this->_city."'s businesses finished...");
    return $business_id_list;
  }
}


/*$obj = new WebPageGrabber('wenzhou');
$obj->grab_all_sport_businesses();
unset($obj);*/

?>
