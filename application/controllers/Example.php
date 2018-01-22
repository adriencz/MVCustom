<?php
defined('ROOTFILE') OR exit('No direct script access allowed');





class Example extends Global_Controller {

  function __construct() {
    /*
    * If you want include Model, Helper, Library.. (see core/Controller.php)
    *
      $this->model('ExampleModel');
      $this->helper('HelperName');
      $this->library('libraryName');
    *
    */
  }


  /*
  * index() is a default method if any url method specified
  */

  public function index() {

    /*
    * If you want include data in view from model
    *
      $this->model('ExampleModel');
      $data['exampleKey'] = $this->ExampleModel->getExample();
      $this->view('example', $data);
    *
    */

    // create data
    $data['example'] = 'this is a data !';
    // call view 'example.php' and send it $data
    $this->view('example', $data);
  }




}
