<?php
defined('ROOTFILE') OR exit('No direct script access allowed');


class Global_Model {

  /*----------------- Class properties -----------------*/

  protected $where;
  protected $limit;
  protected $from;
  protected $select;
  protected $order_by;

  // database

  protected $db = null;
  protected $db_dsn;
  protected $db_hostname;
  protected $db_dbname;
  protected $db_username;
  protected $db_password;
  protected $db_encoding;


  function __construct() {
    if ($this->db === null) {
      require_once(ROOTFILE.'system/config/database.php');
        $this->db_dsn = $database['dsn'];
        $this->db_hostname = $database['hostname'];
        $this->db_dbname = $database['database'];
        $this->db_username = $database['username'];
        $this->db_password = $database['password'];
        $this->db_encoding = $database['encoding'];
        $this->db = new PDO($this->db_dsn.':host='.$this->db_hostname.';dbname='.$this->db_dbname.';charset='.$this->db_encoding, $this->db_username, $this->db_password);
    }
  }


  protected function select($select) {
    $this->select = $select;
    return $this;
  }

/* ---------------------------------------------------- */

  protected function from($from) {
    $this->from = $from;
    return $this;
  }

/* ---------------------------------------------------- */

  protected function where($where) {
    $this->where = $where;
    return $this;
  }

/* ---------------------------------------------------- */

  protected function limit($limit) {
    $this->limit = $limit;
    return $this;
  }

/* ---------------------------------------------------- */

  protected function order_by($order_by) {
    $this->order_by = $order_by;
    return $this;
  }

/* ---------------------------------------------------- */

  protected function get() {
    if (!isset($this->select)) {
      $this->select = '*';
    }

    if (isset($this->where) && isset($this->order_by)) {

      if (isset($this->limit)) {
        $query = $this->db->prepare("SELECT ".$this->select." FROM ".$this->from." WHERE ".$this->where." ORDER BY ".$this->order_by." LIMIT ".$this->limit."");
      }else {
        $query = $this->db->prepare("SELECT ".$this->select." FROM ".$this->from." WHERE ".$this->where." ORDER BY ".$this->order_by."");
      }

    }
    elseif (isset($this->where) && !isset($this->order_by)) {

      if (isset($this->limit)) {
        $query = $this->db->prepare("SELECT ".$this->select." FROM ".$this->from." WHERE ".$this->where." LIMIT ".$this->limit."");
      }else {
        $query = $this->db->prepare("SELECT ".$this->select." FROM ".$this->from." WHERE ".$this->where."");
      }

    }
    elseif (!isset($this->where) && isset($this->order_by)) {

      if (isset($this->limit)) {
        $query = $this->db->prepare("SELECT ".$this->select." FROM ".$this->from." ORDER BY ".$this->order_by." LIMIT ".$this->limit."");
      }else {
        $query = $this->db->prepare("SELECT ".$this->select." FROM ".$this->from." ORDER BY ".$this->order_by."");
      }

    }
    elseif (!isset($this->where) && !isset($this->order_by)) {

      if (isset($this->limit)) {
        $query = $this->db->prepare("SELECT ".$this->select." FROM ".$this->from." LIMIT ".$this->limit."");
      }else {
        $query = $this->db->prepare("SELECT ".$this->select." FROM ".$this->from."");
      }

    }

    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
  }



/* ---------------------------------------------------- */


protected function insert($data) {

  $keys = array_keys($data);
  $values = array_values($data);

  $keySql = implode(', ', $keys);
  $valueSql = Implode(', :', $keys);

  $query = $this->db->prepare("INSERT INTO ".$this->from." (".$keySql.") VALUES (:".$valueSql.")");

  for ($i=0; $i < count($keys) ; $i++) {

    if (gettype($values[$i]) == 'integer') {

      $query->bindParam(":".$keys[$i], $values[$i], PDO::PARAM_INT);

    }
    elseif (gettype($values[$i]) == 'string') {

      $query->bindParam(":".$keys[$i], $values[$i], PDO::PARAM_STR);

    }
    elseif (gettype($values[$i]) == 'double') {

      $query->bindParam(":".$keys[$i], $values[$i], PDO::PARAM_FLOAT);

    }
    elseif (gettype($values[$i]) === NULL) {

      $query->bindParam(":".$keys[$i], $values[$i], PDO::PARAM_NULL);

    }
    elseif (gettype($values[$i]) === true || gettype($values[$i]) === false) {

      $query->bindParam(":".$keys[$i], $values[$i], PDO::PARAM_NULL);

    }
  }

  $query->execute();

}




/* ---------------------------------------------------- */


protected function update($data, $idkey, $idValue) {

  foreach ($data as $key => $value) {
    $query = $this->db->prepare("UPDATE ".$this->from." SET ".$key." = '".$value."' WHERE ".$idkey." = ".$idValue."");
    $query->execute();
  }

}



/* ---------------------------------------------------- */


protected function delete($idkey, $idValue) {
    $query = $this->db->prepare("DELETE FROM ".$this->from." WHERE ".$idkey." = ".$idValue."");
    $query->execute();
}


/* ---------------------------------------------------- */


protected function count() {
  if (!isset($this->select)) {
    $this->select = '*';
  }

  if (isset($this->where) && isset($this->order_by)) {

    if (isset($this->limit)) {
      $query = $this->db->prepare("SELECT count(".$this->select.") FROM ".$this->from." WHERE ".$this->where." ORDER BY ".$this->order_by." LIMIT ".$this->limit."");
    }else {
      $query = $this->db->prepare("SELECT count(".$this->select.") FROM ".$this->from." WHERE ".$this->where." ORDER BY ".$this->order_by."");
    }

  }
  elseif (isset($this->where) && !isset($this->order_by)) {

    if (isset($this->limit)) {
      $query = $this->db->prepare("SELECT count(".$this->select.") FROM ".$this->from." WHERE ".$this->where." LIMIT ".$this->limit."");
    }else {
      $query = $this->db->prepare("SELECT count(".$this->select.") FROM ".$this->from." WHERE ".$this->where."");
    }

  }
  elseif (!isset($this->where) && isset($this->order_by)) {

    if (isset($this->limit)) {
      $query = $this->db->prepare("SELECT count(".$this->select.") FROM ".$this->from." ORDER BY ".$this->order_by." LIMIT ".$this->limit."");
    }else {
      $query = $this->db->prepare("SELECT count(".$this->select.") FROM ".$this->from." ORDER BY ".$this->order_by."");
    }

  }
  elseif (!isset($this->where) && !isset($this->order_by)) {

    if (isset($this->limit)) {
      $query = $this->db->prepare("SELECT count(".$this->select.") FROM ".$this->from." LIMIT ".$this->limit."");
    }else {
      $query = $this->db->prepare("SELECT count(".$this->select.") FROM ".$this->from."");
    }

  }

  $query->execute();
  return $query->fetchColumn();
}




}
