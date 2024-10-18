<?php

class Dashboard extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
  }

  function index()
  {
    $data = array();
    $this->load->view('dashboard/index', $data);
  }
}
