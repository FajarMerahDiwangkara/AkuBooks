<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index() 
    {
        # https://codeigniter.com/user_guide/models/model.html#models
        # https://stackoverflow.com/questions/3173501/whats-the-difference-between-double-colon-and-arrow-in-php

        $testAccountData = new \App\Models\UserAccount();

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
