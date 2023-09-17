<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = array('sidebar_active' => 'dashboard');
        return view('index', $data);
    }
}
?>
