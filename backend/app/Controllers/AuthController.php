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
        $accountData->set_password_plaintext($_POST['password_plaintext']);
        $accountData->set_email_address($_POST['email_address']);
        $accountData->set_name($_POST['name']);
        $accountData->set_username($_POST['username']);
        print_r(\App\Models\Auth::sign_up($accountData));
    }
}

?>