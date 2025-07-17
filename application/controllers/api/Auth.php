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

use Util\Date;

class Auth extends CI_Controller
{
    var $auth_model;

    function __construct()
    {
        parent::__construct();

        // Set content type header to JSON for API responses
        header('Content-Type: application/json');

        // Optional: Allow CORS if needed
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        $this->load->helper(['auth']);
        $this->load->library(['form_validation', 'mail']);
        $this->load->model('auth_model');
    }

    function register()
    {
        if ($this->input->post()) {
            $user = register_form();
            validate_register_form();
            if ($this->form_validation->run() != FALSE) {
                $user_id = $this->auth_model->save($user);
                $user_token = user_token_form($user_id);
                $token = $this->auth_model->save_token($user_token);
                echo json_encode(array('success' => true, 'token' => $token));
            } else {
                echo json_encode(['success' => false, 'errors' => $this->form_validation->error_array()]);
            }
        } else {
            echo json_encode(array('success' => false, 'Invalid request.'));
        }
    }

    function login()
    {
        if ($this->input->post()) {
            list($username, $password) = login_form();
            $user = $this->auth_model->read_by_username_and_password($username, $password);
            if ($user) {
                $user_token = user_token_form($user->id, Date::now()->minutes_from_now(20));
                $token = $this->auth_model->save_token($user_token);
                echo json_encode(array('success' => true, 'token' => $token));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Invalid username or password. Please try again!'));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
        }
    }

    function profile()
    {
        if ($this->input->method(true) === 'GET') {
            $token = $this->input->get('token');
            $user_token = $this->auth_model->read_by_user_token($token);
            if ($user_token) {
                $profile = $this->auth_model->read($user_token->user_id);
                echo json_encode(['success' => true, 'profile' => trim_profile($profile)]);
            } else {
                echo json_encode(array('success' => false, 'message' => 'Invalid or expired token.'));
            }
        } else if ($this->input->method(true) === 'POST') {
            $token = $this->input->post('token');
            $user_token = $this->auth_model->read_by_user_token($token);
            if ($user_token) {
                $profile = profile_form();
                validate_profile_form();
                if ($this->form_validation->run() != FALSE) {
                    $this->auth_model->update($profile, $user_token->user_id);
                    echo json_encode(['status' => true, 'message' => 'Profile updated successfully.']);
                } else {
                    echo json_encode(['success' => false, 'errors' => $this->form_validation->error_array()]);
                }
            } else {
                echo json_encode(array('success' => false, 'message' => 'Invalid or expired token.'));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Invalid request'));
        }
    }

    function logout()
    {
        $token = $this->input->post('token');
        if ($this->input->post()) {
            $user_token = $this->auth_model->read_by_user_token($token);
            if ($user_token) {
                $this->auth_model->delete_tokens($user_token->user_id);
                echo json_encode(array('success' => true, 'message' => 'Logged out successfully.'));
            } else {
                echo json_encode(['success' => true, 'message' => 'Invalid or expired token.']);
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
        }
    }

    function forgot_password()
    {
        if ($this->input->method(true) === 'POST') {
            $token = $this->input->post('token');
            $user_token = $this->auth_model->read_by_user_token($token);
            if ($user_token) {
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
                    echo json_encode(['success' => true, 'message' => 'Password reset link has been sent!']);
                } else {
                    echo json_encode(['success' => false, 'errors' => $this->form_validation->error_array()]);
                }
            } else {
                echo json_encode(['success' => true, 'message' => 'Invalid or expired token.']);
            }
        } else {
            echo json_encode(array('success' => false, 'Invalid request.'));
        }
    }

    function reset_password()
    {
        if ($this->input->method(true) === 'POST') {
            $token = $this->input->post('token');
            $user_token = $this->auth_model->read_by_user_token($token);
            if ($user_token) {
                $password = $this->input->post('password');
                validate_reset_password_form();
                if ($this->form_validation->run() != FALSE) {
                    $encrypted_password = password_hash($password, PASSWORD_BCRYPT);
                    $updated_user = array(
                        'password' => $encrypted_password,
                        'updated_at' => Date::now(),
                        'token' => bin2hex(random_bytes(32))
                    );
                    $this->auth_model->update($updated_user, $user_token->user_id);
                    $this->session->set_flashdata('info', 'Password updated successfully');
                    redirect('auth/login');
                }
            } else {
                echo json_encode(['success' => true, 'message' => 'Invalid or expired token.']);
            }
        } else {
            echo json_encode(array('success' => false, 'Invalid request.'));
        }
    }
}
