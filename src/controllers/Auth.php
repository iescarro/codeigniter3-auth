<?php

class Auth extends CI_Controller
{
  var $auth_model;

  var $load;
  var $input;
  var $session;

  function __construct()
  {
    parent::__construct();
    $this->load->model('auth_model');
  }

  function register()
  {
    $data = array();
    if ($this->input->post()) {
      $user = register_form();
      $this->auth_model->save($user);
      redirect('auth/login');
    }
    $this->load->view('auth/register', $data);
  }

  function login()
  {
    $data = array();
    if ($this->input->post()) {
      list($username, $password) = login_form();
      $user = $this->auth_model->read_by_username_and_password($username, $password);
      if ($user) {
        $this->session->set_userdata('user_id', $user->id);
        redirect('dashboard');
      }
    }
    $this->load->view('auth/login', $data);
  }

  function forgot()
  {
    $data = array();
    if ($this->input->post()) {
      $email = $this->input->post('email');
      $data['info'] = '';
    }
    $this->load->view('auth/forgot', $data);
  }
}
