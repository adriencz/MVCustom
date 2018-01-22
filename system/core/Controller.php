<?php
defined('ROOTFILE') OR exit('No direct script access allowed');


class Global_Controller {


  public function view($filename, $data=null) {
    if ($data!=null) {
      extract($data);
    }
    if (file_exists(ROOTFILE.'application/views/'.$filename.'.php'))
    {
      require(ROOTFILE.'application/views/'.$filename.'.php');
    }
  }

/* ---------------------------------------------------- */


  public function model($filename) {
    if (file_exists(ROOTFILE.'application/models/'.$filename.'.php'))
    {
      require(ROOTFILE.'application/models/'.$filename.'.php');
      $this->$filename = new $filename();
    }
  }

/* ---------------------------------------------------- */


  public function helper($filename) {
    if (file_exists(ROOTFILE.'system/helpers/'.$filename.'.php'))
    {
      require(ROOTFILE.'system/helpers/'.$filename.'.php');
    }
  }

  /* ---------------------------------------------------- */


    public function library($filename) {
      if (file_exists(ROOTFILE.'system/libraries/'.$filename.'.php'))
      {
        require(ROOTFILE.'system/library/'.$filename.'.php');
        $this->$filename = new $filename();
      }
    }

  /* ---------------------------------------------------- */


    public function mvcError($error) {
      if (file_exists(ROOTFILE.'system/errors/error.php'))
      {
        require(ROOTFILE.'system/errors/error.php');
      }
    }


    /* ---------------------------------------------------- */


    public function post($data) {
      if (isset($_POST[$data])) {
        $data = htmlspecialchars($_POST[$data], ENT_QUOTES);
        return $data;
      }
    }


    /* ---------------------------------------------------- */


    public function redirect($link) {
      header("Location: ".$link."");
    }


    /* ---------------------------------------------------- */


    public function previous() {
      header("Location: ".$_SERVER['HTTP_REFERER']."");
    }


    /* ---------------------------------------------------- */


    public function xss($value) {
      $value = htmlspecialchars($value, ENT_QUOTES);
      return $value;
    }











}
