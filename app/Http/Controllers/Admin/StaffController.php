<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index(Request $req)
    {
        $users = User::query()
            ->orderByRaw("role='admin' DESC")   // admin utama di paling atas
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.staff.index', compact('users'));
    }

    public function store(StoreStaffRequest $req)
    {
        $v = $req->validated();
        $v['password']  = bcrypt($v['password']);
        $v['role']      = 'staff';
        $v['is_active'] = true;

        User::create($v);

        return back()->with('ok', 'Staf berhasil ditambahkan.');
    }

    public function update(UpdateStaffRequest $req, User $user)
    {
        // Admin utama tidak boleh diubah dari sini (UI juga disembunyikan)
        if (($user->role ?? null) === 'admin') {
            return back()->with('err', 'Admin utama tidak dapat diubah dari sini.');
        }

        $v = $req->validated();

        if (!empty($v['password'])) {
            $v['password'] = bcrypt($v['password']);
        } else {
            unset($v['password']);
        }

        // pastikan tetap staff (bukan admin)
        $v['role'] = 'staff';

        $user->update($v);

        return back()->with('ok', 'Data staf berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // tidak boleh hapus diri sendiri
        if (Auth::id() === $user->id) {
            return back()->with('err', 'Tidak bisa menghapus akun sendiri.');
        }

        // tidak boleh hapus admin utama
        if (($user->role ?? null) === 'admin') {
            return back()->with('err', 'Admin utama tidak dapat dihapus.');
        }

        $user->delete();

        return back()->with('ok', 'Staf berhasil dihapus.');
    }
}
