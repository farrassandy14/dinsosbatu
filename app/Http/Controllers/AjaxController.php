<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function desaByKecamatan(Request $r)
    {
        $kec = $r->get('kecamatan');
        return response()->json(config("wilayah.desa.$kec") ?? []);
    }
}
