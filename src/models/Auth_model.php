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

class Auth_model extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }

  function save($user)
  {
    $this->db->set('token', guid());
    $this->db->set('created_at', date('Y-m-d H:i:s'));
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->insert('users', $user);
    return $this->db->insert_id();
  }

  function read_by_username_and_password($username, $password)
  {
    $this->db->or_where('email', $username);
    $user = $this->db->get('users')->row();
    if ($user && password_verify($password, $user->password)) {
      return $user;
    }
    return null;
  }

  function read_by_email($email)
  {
    $this->db->or_where('email', $email);
    return $this->db->get('users')->row();
  }

  function read_by_token($token)
  {
    $this->db->or_where('token', $token);
    return $this->db->get('users')->row();
  }

  function read($id)
  {
    $this->db->where('id', $id);
    return $this->db->get('users')->row();
  }

  function update($user, $id)
  {
    $this->db->update('users', $user, array('id' => $id));
  }
}
