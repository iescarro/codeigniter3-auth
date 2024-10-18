<?php
class Auth_model extends CI_Model
{

  function __construct() {}

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
}
