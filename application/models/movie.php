<?php
class Movie extends Model {
  public $title;
  public static $MOVIE_TABLE = 'movies';
  public static $GENRE_TABLE = 'genre';
  public static $LANG_TABLE = 'languages';

  public static $MOVIE_ATTRS = Array('id', 'title', 'description', 'featured_image', 'length', 'release_date', 'created_at', 'updated_at');
  public static $GENER_ATTRS = Array('value');
  public static $LANG_ATTRS = Array('value');
  public static $SORT_MAP = Array("length.asc" => Array("length", "asc"), 
                                  "length.desc" => Array("length", "desc"), 
                                  "release_date.asc" => Array("release_date", "asc"),
                                  "release_date.desc" => Array("release_date", "desc")
                                  );

  static public function filter($opts){ 
    // this can be made as a function and added in the helper. But task is small so no need to.
    $result = Array();
    
    $select_each_block = function($query, $table, $const){
      foreach ($const as $attr) {
        $query .= " `{$table}`.`{$attr}`, ";
      }
      return $query;
    };

    $sub_query = ' SELECT ';
    $sub_query = $select_each_block($sub_query, self::$MOVIE_TABLE, self::$MOVIE_ATTRS);

    if (isset($opts['language'])) {
      $sub_query = $select_each_block($sub_query, self::$LANG_TABLE, self::$LANG_ATTRS);
    }  

    if (isset($opts['genre'])) {
      $sub_query = $select_each_block($sub_query, self::$GENRE_TABLE, self::$LANG_ATTRS);
    }  
    
    $sub_query = rtrim($sub_query, ", ");
    $sub_query .= " FROM `".self::$MOVIE_TABLE."` m INNER JOIN `".self::$GENRE_TABLE."` g ON g.id = m.genre_id INNER JOIN `".self::$LANG_TABLE."` l ON l.id = m.language_id ";
    $sub_query .=  " WHERE ";

    // code duplication while checking the case. This problem will be solved
    // in case we use any ORM. Which i snot implemented in the simple CSM.
    if (isset($opts['language'])) {
      $lang_where_clause = " l.value = :language";
    }  

    if (isset($opts['genre'])) {
      $genre_where_clause = " g.value = :genre";
    }  
    
    if(isset($lang_where_clause)&& isset($genre_where_clause)){
      $sub_query .=  $lang_where_clause." AND ". $genre_where_clause ;
    }else if(isset($lang_where_clause)){
      $sub_query .= $lang_where_clause;
    }else if(isset($genre_where_clause)){
      $sub_query .= $genre_where_clause;
    }

    if (isset($opts['sort'])) {
      $arr = self::$SORT_MAP[$opts['sort']];
      if (isset($arr) && sizeof($arr)>1) {
        $sub_query .= " order by `".self::$MOVIE_TABLE."`.`{$arr[0]}` {$arr[1]}";
      }  
    }      
    
    $db = new DbQuery(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    
    $db_obj = $db->prepare("select * from movies");

    if (isset($opts['language'])) {
      $db_obj->bindParam(':language', $opt['language']);
    } 

    if (isset($opts['genre'])) {
      $db_obj->bindParam(':genre', $opt['genre']);
    }  
    
    if ($db_obj->execute()) {
      while($row = $db_obj->fetch()){
        array_push($result, $row);
      }
    }  
      
  }
  
}
