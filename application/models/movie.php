<?php
class Movie extends Model {
  public $title;
  public static $MOVIE_TABLE = 'movies';
  public static $GENRE_TABLE = 'genres';
  public static $LANG_TABLE = 'languages';

  public static $MOVIE_ATTRS = Array('id', 'title', 'description', 'featured_image', 'length', 'release_date', 'created_at', 'updated_at');
  public static $GENER_ATTRS = Array('value');
  public static $LANG_ATTRS = Array('value');
  public static $SORT_MAP = Array("length.asc" => Array("length", "asc"), 
                                  "length.desc" => Array("length", "desc"), 
                                  "release_date.asc" => Array("release_date", "asc"),
                                  "release_date.desc" => Array("release_date", "desc")
                                  );

  public function add($opts){
    $date = new DateTime();
    $time_stamp = $date->getTimeStamp();
    $file_path = "img/".$time_stamp.".".pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
    $upload_file = "/var/www/html/filmy-ajency/public/img/".$time_stamp.".".pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);

    if(move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_file)) {
       // "file successfully uploaded";
    } else {
      return "image cannot be uploaded";
    }

    $opts['featured_image'] = $file_path;
    $date = new DateTime($opts['release_date']);
    $time = explode(':', $opts['release_time']);
    $date->add(new DateInterval('PT'.$time[0].'H'.$time[1].'M'));
    
    // call this block 
    $bind_params_block = function($obj, $key, $value){ 
      $key = ':'.$key;
      if (is_numeric($value)){
        return $obj->bindParam($key, $value, PDO::PARAM_INT);
      } else {
        return $obj->bindParam($key, $value, PDO::PARAM_STR);
      }  
    };

    $query = "INSERT INTO ".self::$MOVIE_TABLE." (title, description, featured_image, length, release_date, genre_id, language_id, created_at, updated_at) VALUES( :title, :description, :featured_image, :length, :release_date, :genre_id, :language_id, now(), now())";

    $dbo = $this->prepare($query);
    $release_date_time = $date->format('Y-m-d h:i:s');
        
    $opts['release_date'] = $release_date_time;
    unset($opts['release_time']);

    foreach ($opts as $key => $value) {
      $bind_params_block($dbo, $key, $value);
    }

    if ($dbo->execute()) {
      return "movie successfully added";
    }else{
      unlink("/var/www/html/filmy-ajency/public/img/".$time_stamp.".".pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
      return "error while adding movie";
    } 

  } 

  static public function get_languages_list(){
    $result = Array();
    $query = "select id, value from ".self::$LANG_TABLE;
    $db = new DbQuery(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $db_obj = $db->prepare($query);
    if($db_obj->execute()) {
      while ($row = $db_obj->fetch()) {
        array_push($result, $row);
      }
    }  
    return $result;
  }

  static public function get_genres_list(){
    $result = Array();
    $query = "select id, value from ".self::$GENRE_TABLE;
    $db = new DbQuery(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $db_obj = $db->prepare($query);
    if($db_obj->execute()) {
      while ($row = $db_obj->fetch()) {
        array_push($result, $row);
      }
    }  
    return $result; 
  }

  static public function filter($opts){ 
    // this can be made as a function and added in the helper. But task is small so no need to.
    $result = Array();
    $sub_query = '';
    $join_query = '';
    $where_query = '';
    $query_bind_keys = Array('language', 'genre', 'limit', 'offset');
    $count_bind_keys = Array('language', 'genre');

    $select_each_block = function($query, $table, $const){
      foreach ($const as $attr) {
        $query .= " `{$table}`.`{$attr}`, ";
      }
      return $query;
    };

    $bind_params_block = function($obj, $key, $value){ 
      $key = ':'.$key;
      if (is_numeric($value)){
        return $obj->bindParam($key, $value, PDO::PARAM_INT);
      } else {
        return $obj->bindParam($key, strtolower($value), PDO::PARAM_STR);
      }  
    };

    $sub_query = ' SELECT ';
    $count_query = " SELECT count(`".self::$MOVIE_TABLE."`.`id`) as count ";
    $sub_query = $select_each_block($sub_query, self::$MOVIE_TABLE, self::$MOVIE_ATTRS);

    if (isset($opts['language'])) {
      $sub_query = $select_each_block($sub_query, self::$LANG_TABLE, self::$LANG_ATTRS);
    }  

    if (isset($opts['genre'])) {
      $sub_query = $select_each_block($sub_query, self::$GENRE_TABLE, self::$LANG_ATTRS);
    }  
    
    $sub_query = rtrim($sub_query, ", ");
    $join_query .= " FROM `".self::$MOVIE_TABLE."`  INNER JOIN `".self::$GENRE_TABLE."`  ON ".self::$GENRE_TABLE.".id = ".self::$MOVIE_TABLE.".genre_id INNER JOIN `".self::$LANG_TABLE."`  ON ".self::$LANG_TABLE.".id = ".self::$MOVIE_TABLE.".language_id ";
    $where_query .=  " WHERE ";

    // code duplication while checking the case. This problem will be solved
    // in case we use any ORM. Which i snot implemented in the simple CSM.
    if (isset($opts['language'])) {
      $lang_where_clause = " ".self::$LANG_TABLE.".value = :language";
    }  

    if (isset($opts['genre'])) {
      $genre_where_clause = " ".self::$GENRE_TABLE.".value = :genre";
    }  
    
    if(isset($lang_where_clause)&& isset($genre_where_clause)){
      $where_query .=  $lang_where_clause." AND ". $genre_where_clause ;
    }else if(isset($lang_where_clause)){
      $where_query .= $lang_where_clause;
    }else if(isset($genre_where_clause)){
      $where_query .= $genre_where_clause;
    }
    // joinning both the queries
    $sub_query .= $join_query . $where_query;
    $count_query .= $join_query . $where_query;

    if (isset($opts['sort'])) {
      $arr = self::$SORT_MAP[$opts['sort']];
      if (isset($arr) && sizeof($arr)>1) {
        $sub_query .= " order by `".self::$MOVIE_TABLE."`.`{$arr[0]}` {$arr[1]}";
      }  
    }      
    
    if (isset($opts['limit'])) {
      $sub_query .= " limit :limit ";
    } 
    
    if (isset($opts['offset'])) {
      $sub_query .= " offset :offset ";
    }  
    
    $db = new DbQuery(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    
    $db_count_q = $db->prepare($count_query);
    
    foreach ($count_bind_keys as $key) {
      if (isset($opts[$key])) { 
        $bind_params_block($db_count_q, $key, $opts[$key]);
      }  
    }

    if ($db_count_q->execute()) {
      while($row = $db_count_q->fetch()){
        $count = $row['count'];
      }
    } 

    $result['count'] = $count;
  
    $db_obj = $db->prepare($sub_query);


    foreach ($query_bind_keys as $key) {
      if (isset($opts[$key])) { 
        $bind_params_block($db_obj, $key, $opts[$key]);
      }  
    }
    
    $temp_result  = array();

    if($db_obj->execute()) {
      while($row = $db_obj->fetch()){
        array_push($temp_result, $row);
      }
    } 

    $result['movies'] = $temp_result;
    return $result;
  }
  
}
