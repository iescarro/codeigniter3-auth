<?php

/**
 * CodeIgniter3
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2024, CodeIgniter3 Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter3
 * @author	CodeIgniter3 Team
 * @copyright	Copyright (c) 2014, CodeIgniter3 Team (https://github.com/iescarro/codeigniter3)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://github.com/iescarro/codeigniter3
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
  var $auth_model;

  var $load;
  var $input;
  var $session;

  function __construct()
  {
    parent::__construct();
    $this->load->helper(['html', 'url', 'form', 'auth', 'string', 'date']);
    $this->load->library(['session', 'form_validation', 'mail']);
    $this->load->model('auth_model');
  }

  function register()
  {
    $data = array();
    if ($this->input->post()) {
      $user = register_form();
      validate_register_form();
      if ($this->form_validation->run() != FALSE) {
        $this->auth_model->save($user);
        $this->session->set_flashdata('info', 'Registration successful. Please login to continue!');
        redirect('auth/login');
      }
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

  function profile()
  {
    $user = $this->auth_model->read($this->session->userdata('user_id'));
    $this->redirect_if(!$user, 'auth/login');
    $data['info'] = $this->session->flashdata('info');
    if ($this->input->post()) {
      $profile = profile_form();
      validate_profile_form();
      if ($this->form_validation->run() != FALSE) {
        $this->auth_model->update($profile, $this->session->userdata('user_id'));
        $this->session->set_flashdata('info', 'Profile updated successfully.');
        redirect('auth/profile');
      }
    }
    $data['user'] = $user;
    $this->load->library('layout');
    $this->layout->set('user');
    $this->layout->view('auth/profile', $data);
  }

  function logout()
  {
    $this->session->sess_destroy();
    redirect('.');
  }

  function forgot_password()
  {
    $data['info'] = $this->session->flashdata('info');
    if ($this->input->post()) {
      $email = $this->input->post('email');
      $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
      if ($this->form_validation->run() != FALSE) {
        $user = $this->auth_model->read_by_email($email);
        if ($user) {
          $reset_link = base_url() . 'auth/reset_password/' . $user->token;
          $body = "Password Reset Request

Click the link below to reset your password: $reset_link";
          Mail::to($user->email)->send(new MailMessage('Password Reset Request', $body));
        }
        $this->session->set_flashdata('info', 'Password reset link has been sent!');
        redirect('auth/forgot_password');
      }
    }
    $this->load->view('auth/forgot_password', $data);
  }

  function reset_password($token = '')
  {
    $data['info'] = $this->session->flashdata('info');
    $data['token'] = $token;
    $user = $this->auth_model->read_by_token($token);

    if ($this->input->post()) {
      $password = $this->input->post('password');
      validate_reset_password_form();
      if ($this->form_validation->run() != FALSE) {
        $encrypted_password = password_hash($password, PASSWORD_BCRYPT);
        $updated_user = array(
          'password' => $encrypted_password,
          'updated_at' => date('Y-m-d H:i:s'),
          'token' => guid()
        );
        $this->auth_model->update($updated_user, $user->id);
        $this->session->set_flashdata('info', 'Password updated successfully');
        redirect('auth/login');
      }
    }
    $this->load->view('auth/reset_password', $data);
  }

  private function redirect_if($condition, $url)
  {
    if ($condition) {
      redirect($url);
    }
  }
}
