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
    $this->load->helper(['html', 'url', 'form', 'auth']);
    $this->load->library('session');
    $this->load->model('auth_model');
  }

  function register()
  {
    $data = array();
    if ($this->input->post()) {
      $user = register_form();
      $this->auth_model->save($user);
      $this->session->set_flashdata('info', 'Registration successful. Please login to continue!');
      redirect('auth/login');
    }
    $this->load->view('auth/register', $data);
  }

  function login()
  {
    $data['info'] = $this->session->flashdata('info');
    if ($this->input->post()) {
      list($username, $password) = login_form();
      $user = $this->auth_model->read_by_username_and_password($username, $password);
      if ($user) {
        $this->session->set_userdata('user_id', $user->id);
        $this->session->set_userdata('user_name', $user->name);
        redirect('dashboard');
      } else {
        $data['warning'] = 'Invalid username or  password. Please try again!';
      }
    }
    $this->load->view('auth/login', $data);
  }

  function logout()
  {
    $this->session->sess_destroy();
    redirect('auth/login');
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
