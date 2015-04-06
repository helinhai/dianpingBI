<?php


#set_time_limit(0);
include_once('./vendors/simple_html_dom.php');
include_once('./Snoopy/Snoopy.class.php');
include_once('./utils.class.php');

define('PREFIX', 'http://www.dianping.com');



class grab_comments {
  private $_id;
  private $_url;
  private $_snoopy;
  
  private function _init ( $id ) {
    $this->_id = $id;
    trace("Set business as => ".$this->_id);
    $this->_url = PREFIX.'/shop/'.$this->_id.'/review_all';
    trace("URL => ".$this->_url);
    return $this->_url;   
  }
  
  private function _fetch ( $url ) {
    if ( isset($this->_snoopy) )
      unset($this->_snoopy);
    $this->_snoopy = new Snoopy();
    $this->_snoopy->fetch($url);
    return $this->_snoopy->results;
  }
  
  
  public function __construct ( $id ) {
    $this->_init($id);
  }
  
  public function get_business_comments () 
  {
    $html = str_get_html($this->_fetch($this->_url));
    $comments_list = array();
    $comments_list[0] = $html->find('div.J_brief-cont')[0]->plaintext;
    $comments_list[1] = $html->find('div.J_brief-cont')[1]->plaintext;
    $comments_list[2] = $html->find('div.J_brief-cont')[2]->plaintext;
    $comments_list[3] = $html->find('div.J_brief-cont')[3]->plaintext;
    if( empty($comments_list) ){
      trace("NO comments DETECTED!!!");
    } 
    else {
        trace("SOME COMMENTS DETECTED!!!");
        return $comments_list;
    }    
  }

}



?>
