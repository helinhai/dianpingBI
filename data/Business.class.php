<?php

include_once('./utils.class.php');

class Business {
    //array
    private $_data;
    
    private static $_CATEGORIES_LIST;
    
    public static function UPDATE_CATEGORIES_LIST () {
        trace("Updating categories list...");
        $sql = "select id, name from categories;";
        $handler = new DBhandler();
        $result = $handler->fetchQuery($sql);
        $updated_list = array();
        while ( $row = mysql_fetch_array($result) ) {
            array_push($updated_list, array($row['id'], $row['name']));
        }
        self::$_CATEGORIES_LIST = $updated_list;
        trace("Categories list has update to date:");
        print_r(self::$_CATEGORIES_LIST);
    }
    
    public static function HAS_THIS_CATEGORY ( $category ) {
        for ( $i = 0; $i < count(self::$_CATEGORIES_LIST); $i++ ) {
            if ( in_array($category, self::$_CATEGORIES_LIST[$i]) ) {
                trace("Category '".$category."' found in the database.");
                $it = self::$_CATEGORIES_LIST[$i];
                trace($it[0]." => ".$it[1]);
                return $it[0];
            }
        }
        trace("Not found category '".$category."' in the database.");
        return -1;
    }
    
    public function __construct( $data ) {
        $this->_data = $data;
        $business_name = $this->_data['name'];
        $pattern = '/\(这是一条测试商户数据，仅用于测试开发，开发完成后请申请正式数据...\)/';
        $this->_data['name']  = preg_replace($pattern, '', $business_name);//preg_replace执行一个正则表达式的搜索和替换
        $this->_init_business();
    }
    
    private function _init_business () {
        //将商户类型由数组变为单值变量
        #$categories = $this->_data['categories'];
        #$this->_data['categories'] = $this->_set_categories($categories);
        
        //将区域格式设为关联数组
        $regions = $this->_data['regions'];
        $this->_data['regions'] = $this->_set_regions($regions);
        
        //将deals团购信息decode成jason
        $deals = $this->_data['deals'];
        $this->_data['deals'] = $this->_set_deals($deals);
        
        //将空值的非数组成员设置成null
        $data = $this->_data;
        while ( list ( $key, $value ) = each ($data) ) {
            //不是数组&&没有set值
            if ( !(is_array($value)) && !(isset($value)) )
              $this->_data[$key] = null;
        }
    }
    
    
    private function _set_regions ( $arr ) {
        if ( empty( $arr ) )
          return null;
        else {
            $regions = array();
            $regions['region'] = array_pop($arr);//array_pop删除数组最后一个元素
            $regions['business_district'] = array_pop($arr);
            $regions['sub_business_district'] = array_pop($arr);
            return $regions;
        }
    }
    
    private function _set_deals ( $arr ) {
        return empty($arr) ? null : json_encode($arr);
    }
    
    
    public function generateQueries () {
        return array(
                     $this->businessQuery(),
                     $this->regionQuery(),
                     $this->categoriesQuery()
                     );
    }
    
    public function businessQuery () {;
        $attributes = "(";
        $values = "VALUES (";
        $obj = $this->_data;
        
        while ( list ($key, $val) = each ( $obj ) ) {
            if ( $key == "regions" || $key == "categories" )
                continue;
            #if ( $key == "business_id" )//business表格主键改为id
               # $key = 'id';
            $attributes = $attributes . "`$key`, ";
            $val = mysql_escape_string($val);
            $values = $values . "'$val', ";           
            if ( $key == "online_reservation_url" )//之后的数据不要
                break;
        }
        
        $attributes = $attributes . "`created_by`) ";
        $values = $values . " '1');";
        
        $sql = "INSERT INTO `helinhai`.`businesses` " . $attributes . $values;
        trace ("Creating businesses insert sql query:");
        trace($sql);
        return $sql;
    }
    
    public function regionQuery () {
        $regions = $this->_data['regions'];
        $business_id = $this->_data['business_id'];
        $region = $regions['region'];
        $business_district = $regions['business_district'];
        $sub_business_district = $regions['sub_business_district'];
        
        $sql = "INSERT INTO `helinhai`.`regions` (`business_id`, `region`, `business_district`, `sub_business_district`)";
        $sql = $sql." VALUES ('$business_id', '$region', '$business_district','$sub_business_district');";
        trace("Creating regions insert sql query:");
        trace($sql);
        return $sql;
    }
    
    public function setRelationBusinessCategoriesQuery ( $category_id) {
        $business_id = $this->_data['business_id'];
        $sql = "INSERT INTO `helinhai`.`businesses_categories` (`business_id`, `category_id`) VALUES ('$business_id', '$category_id')";
        return $sql;
    }
    
    
    public function addCategoriesQuery ( $list ) {
        $sql = array();
        foreach ( $list as $i ) {
            //所有运动商户的类型parent_id = 1；
            array_push($sql, "INSERT INTO `helinhai`.`categories` (`id`, `name`, `parent_id`) VALUES (NULL, '$i', '1');");
        }
        trace("Creating categories insert sql query:");
        trace($sql);
        return $sql;
    }

    public function get_not_inserted_categories () {
        $categories = $this->_data['categories'];
        //store not insert categories name
        $not_insert_categories = array();
        foreach ( $categories as $i ) {
            $cid = self::HAS_THIS_CATEGORY ( $i );
            if ( $cid == -1 ) {
                trace("Category ".$i." not found in database.");
                array_push($not_insert_categories, $i);
            }
        }
        return $not_insert_categories;
    }
    
    public function get_category_ids () {
        $categories = $this->_data['categories'];
        $ids = array();
        foreach ( $categories as $i ) {
            array_push($ids, self::HAS_THIS_CATEGORY($i));
        }
        return $ids;
    }
    
}

?>