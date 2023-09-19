<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index() 
    {
        # https://codeigniter.com/user_guide/models/model.html#models
        # https://stackoverflow.com/questions/3173501/whats-the-difference-between-double-colon-and-arrow-in-php
        # https://stackoverflow.com/questions/2418473/difference-between-require-include-require-once-and-include-once

        $testAccountData = new \App\Models\UserAccount();
        $testAccountData->set_uuid4(\App\Models\UserAccount::generate_uuid4());
        $testAccountData->set_password_plaintext("adminadmin");
        $testAccountData->set_email_address("admin@admin.com");
        $testAccountData->set_name("Admin name");
        $testAccountData->set_username("Admin username");

        print_r(\App\Models\Auth::sign_up($testAccountData));

        $returnBody = ["log" => "homepage"];
        $returnBody = json_encode($returnBody);
        $returnStatusCode = 200;
        # https://codeigniter.com/user_guide/outgoing/response.html?highlight=s
        return $this
        ->response
        ->setStatusCode($returnStatusCode)
        ->setBody($returnBody);
    }
}
?>
