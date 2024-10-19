<?php

function login_form()
{
  $obj = &get_instance();
  return array(
    $obj->input->post('username'),
    $obj->input->post('password'),
  );
}

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
