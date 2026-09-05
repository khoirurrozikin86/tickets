<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;

use App\Models\ScanRecord;

use App\Models\Outlet;

use App\Models\User;

use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {


        return view('admin.dashboard');
    }
}
