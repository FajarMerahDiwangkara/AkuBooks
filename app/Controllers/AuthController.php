<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function sign_up() 
    {
        # https://codeigniter.com/userguide3/libraries/input.html#using-post-get-cookie-or-server-data
        $accountData = new \App\Models\UserAccount();
        $accountData->set_uuid4(\App\Models\UserAccount::generate_uuid4());
        if(isset($_POST['password_plaintext'])) {
            $accountData->set_password_plaintext($_POST['password_plaintext']);
        } else {
            $accountData->set_password_plaintext(null);
        }
        if(isset($_POST['email_address'])) {
            $accountData->set_email_address($_POST['email_address']);
        } else {
            $accountData->set_email_address(null);
        }
        if(isset($_POST['name'])) {
            $accountData->set_name($_POST['name']);
        } else {
            $accountData->set_name(null);
        }
        if(isset($_POST['username'])) {
            $accountData->set_username($_POST['username']);
        } else {
            $accountData->set_username(null);
        }
        return json_encode(\App\Models\Auth::sign_up($accountData));
    }

    public function sign_in() {
        $email_address = null;
        $password_plaintext = null;
        if(isset($_POST['email_address'])) {
            $email_address = $_POST['email_address'];
        }
        if(isset($_POST['password_plaintext'])) {
            $password_plaintext = $_POST['password_plaintext'];
        }
        return json_encode(\App\Models\Auth::sign_in($email_address, $password_plaintext));
    }
}

?>