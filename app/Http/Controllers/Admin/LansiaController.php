<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLansiaRequest;
use App\Http\Requests\UpdateLansiaRequest;
use App\Models\Lansia;
use Illuminate\Http\Request;

class LansiaController extends Controller
{
    public function index(Request $req)
    {
        $q = trim((string) $req->get('q'));

        $data = Lansia::when($q, fn($s) => $s->where(fn($w) => $w
                ->where('nik','like',"%$q%")
                ->orWhere('nama','like',"%$q%")
            ))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.lansia.index', [
            'data'       => $data,
            'q'          => $q,
            'kecamatan'  => config('wilayah.kecamatan'),
            'desaMap'    => config('wilayah.desa'),
            'years'      => array_reverse(range(2015, (int)date('Y') + 1)),
            'jenisMap'   => config('lansia.jenis_bantuan'),
            'sumberDana' => config('lansia.sumber_dana'),
        ]);
    }

    public function create()
    {
        return view('admin.lansia.create', [
            'kecamatan'  => config('wilayah.kecamatan'),
            'desaMap'    => config('wilayah.desa'),
            'years'      => array_reverse(range(2015, (int)date('Y') + 1)),
            'jenisMap'   => config('lansia.jenis_bantuan'),
            'sumberDana' => config('lansia.sumber_dana'),
        ]);
    }

    public function store(StoreLansiaRequest $req)
    {
        Lansia::create($req->validated());
        return redirect()->route('admin.lansia.index')->with('ok','Data berhasil ditambahkan.');
    }

    // Perhatikan parameter route model: {lansium}
    public function update(UpdateLansiaRequest $req, Lansia $lansium)
    {
        $lansium->update($req->validated());
        return back()->with('ok','Data berhasil diperbarui.');
    }

    public function destroy(Lansia $lansium)
    {
        $lansium->delete();
        return back()->with('ok','Data berhasil dihapus.');
    }
}
