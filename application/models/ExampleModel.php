<?php
defined('ROOTFILE') OR exit('No direct script access allowed');




class ExampleModel extends Global_Model {


  public function getExample() {
    $this->select('*');
    $this->from("posts");
    $this->limit(10);
    // create a request method in ---> core/Model.php
    $query = $this->get();
    return $query;
  }




}
