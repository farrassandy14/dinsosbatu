<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lansia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalLansia = Lansia::count();
        $totalKec    = count(config('wilayah.kecamatan'));
        $totalDesa   = collect(config('wilayah.desa'))->flatten()->count();

        return view('admin.dashboard', compact('totalLansia','totalKec','totalDesa'));
    }
}
