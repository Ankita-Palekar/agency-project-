<?php

class DbQuery{
  private $connection;
  public $last_query;
  private $magic_quotes_active;
  private $real_escape_string_exists;
  
  function __construct($db_host, $db_user, $db_pass, $db_name)
  { 
    $this->open_connection($db_host, $db_user, $db_pass, $db_name);
  }
  
  public function open_connection($db_host, $db_user, $db_pass, $db_name)
  {
    // ATTR_PERSISTENT is used for the persistant connection so that db connection will be cached in case there is a next request
    try {
      $this->connection = new PDO('mysql:host='.$db_host.';dbname='.$db_name, $db_user, $db_pass, array(PDO::ATTR_PERSISTENT => true));
    } catch (Exception $e) {
      throw new Exception("DB connection cannot be established");
    }
  }

  public function close_connection()
  {
    if(isset($this->connection))
    {
      mysqli_close($this->connection);
      unset($this->connection);
    }
  }

  public function sanity_table_check(){
    if (!isset($this->_table)) {
      throw new Exception("table does not exist for class {get_class($this)}");
    }  
  }

  public  function select($id)
  {
    // TODO some real escape needs to be done here.
    $query = 'select * from `'.$this->_table.'` where id = '.$id;
    return $query;
  }


  public function select_all()
  {
    $query = 'select * from `'.$this->_table.'`';
    return $query;
  }

  public function prepare($query)
  { 
    return $this->connection->prepare($query);
  }

  public function bind_param($key,$value)
  {
    return $this->bind_param($key,$value);
  }

  public function execute($params= array(''))
  {
    return $this->execute($params);
  }  

  public function fetch()
  {
    return $this->fetch();
  }
}



 
