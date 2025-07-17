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

function register_form()
{
  $obj = &get_instance();
  $encrypted_password = password_hash($obj->input->post('password'), PASSWORD_BCRYPT);
  return array(
    'name' => $obj->input->post('name'),
    'email' => $obj->input->post('email'),
    'password' => $encrypted_password,
  );
}

function validate_register_form()
{
  $obj = &get_instance();
  $obj->form_validation->set_rules('name', 'Name', 'required');
  $obj->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
  $obj->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
  $obj->form_validation->set_rules('confirm_password', 'Confirm password', 'required|matches[password]');
}

function login_form()
{
  $obj = &get_instance();
  return array(
    $obj->input->post('username'),
    $obj->input->post('password'),
  );
}

function profile_form()
{
  $obj = &get_instance();
  $user = array(
    'name' => $obj->input->post('name'),
    'email' => $obj->input->post('email'),
  );
  $password = $obj->input->post('password');
  if ($password) {
    $encrypted_password = password_hash($password, PASSWORD_BCRYPT);
    $user['password'] = $encrypted_password;
  }
  return $user;
}

function validate_profile_form()
{
  $obj = &get_instance();
  $obj->form_validation->set_rules('name', 'Name', 'required');
  $obj->form_validation->set_rules('email', 'Email', 'required|valid_email');
  $password = $obj->input->post('password');
  if ($password) {
    $obj->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
    $obj->form_validation->set_rules('confirm_password', 'Confirm password', 'required|matches[password]');
  }
}

function validate_reset_password_form()
{
  $obj = &get_instance();
  $obj->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
  $obj->form_validation->set_rules('confirm_password', 'Confirm password', 'required|matches[password]');
}

function user_token_form($user_id, $expires_at = null)
{
  $obj = &get_instance();
  return array(
    'user_id' => $user_id,
    'token' => bin2hex(random_bytes(32)),
    'expires_at' => $expires_at,
    'created_at' => date('Y-m-d H:i:s')
  );
}

function trim_profile($profile)
{
  return array(
    'name' => $profile->name,
    'email' => $profile->email,
  );
}
