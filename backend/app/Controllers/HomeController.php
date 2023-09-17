<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index() 
    {
        $returnBody = [];
        $returnStatusCode = 200;
        # https://codeigniter.com/user_guide/outgoing/response.html?highlight=s
        return $this
        ->response
        ->setStatusCode($returnStatusCode)
        ->setBody($returnBody);
    }
}
?>
