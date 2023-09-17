<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardKaryawanController extends BaseController
{
    public function index()
    {
        $data = array('sidebar_active' => 'karyawan');
        return view('karyawan', $data);
    }
}
?>
