<?php
class Auth_model extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }

  function read_by_username_and_password($username, $password)
  {
    $this->db->where('username', $username);
    $this->db->or_where('email', $username);
    $user = $this->db->get('users')->row();
    if ($user && password_verify($password, $user->password)) {
      return $user;
    }
    return null;
  }

  function read_by_username($username)
  {
    $this->db->where('username', $username);
    $this->db->or_where('email', $username);
    return $this->db->get('users')->row();
  }

  function save($user)
  {
    $this->db->insert('users', $user);
    return $this->db->insert_id();
  }
}
