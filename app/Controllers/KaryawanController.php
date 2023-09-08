<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class KaryawanController extends BaseController
{
    public function index()
    {
        return view('karyawan/index');
    }
}
