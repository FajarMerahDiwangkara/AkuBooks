<?php

namespace App\Controllers;

class DashboardHomeController extends BaseController
{
    public function index(): string
    {
        $data = array('sidebar_active' => 'dashboard');
        return view('index', $data);
    }
}
?>
