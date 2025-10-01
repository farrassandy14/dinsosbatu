@extends('layouts.admin')
@section('pageTitle','Manajemen Staf')

@section('content')
  {{-- Bar atas: tombol tambah --}}
  <div class="mb-5 flex items-center justify-end">
    <button id="btn-tambah"
      class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-white text-sm font-medium shadow-sm hover:bg-blue-700">
      <span class="mr-2 text-base leading-none">+</span> Tambah Staf
    </button>
  </div>

  {{-- Flash --}}
  @if (session('ok') || session('err'))
    <div class="mb-4 rounded-md px-4 py-3 {{ session('ok') ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
      {{ session('ok') ?? session('err') }}
    </div>
  @endif
  @if ($errors->any())
    <div class="mb-4 rounded-md bg-red-50 text-red-700 px-4 py-3">
      <strong>Periksa input berikut:</strong>
      <ul class="list-disc pl-5 mt-1">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  {{-- Kartu Tambah (toggle) --}}
  <div id="card-create" class="mb-6 hidden">
    <div class="rounded-lg bg-white p-5 shadow-sm border">
      <h3 class="mb-4 text-lg font-semibold">Tambah Staf</h3>
      <form method="POST" action="{{ route('admin.staff.store') }}" class="grid gap-4 grid-cols-1 md:grid-cols-3">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
          <input type="text" name="name" required class="w-full rounded-md border px-3 py-2" placeholder="Nama staf">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input type="email" name="email" required class="w-full rounded-md border px-3 py-2" placeholder="email@contoh.com">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Password</label>
          <input type="password" name="password" required class="w-full rounded-md border px-3 py-2" placeholder="Min. 8 karakter">
        </div>
        <div class="md:col-span-3">
          <button class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan</button>
          <button type="button" id="btn-batal" class="ml-2 rounded-md border px-4 py-2">Batal</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Tabel daftar staf --}}
  <div class="rounded-lg bg-white p-5 shadow-sm border">
    <div class="mb-4 flex items-center justify-between">
      <h3 class="text-lg font-semibold">Daftar Staf</h3>
      <div class="text-sm text-gray-500">Total: {{ number_format($users->total()) }}</div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-[840px] w-full table-auto divide-y border rounded-md">
        <thead class="bg-gray-50">
          <tr class="text-left text-sm">
            <th class="px-4 py-3 w-16">No</th>
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3 w-[200px] text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse ($users as $i => $u)
            @php
              $isSelf  = auth()->id() === $u->id;
              $isAdmin = ($u->role ?? null) === 'admin';
            @endphp

            <tr class="text-sm">
              <td class="px-4 py-3">{{ $users->firstItem() + $i }}</td>
              <td class="px-4 py-3">
                {{ $u->name }}
                @if($isAdmin)
                  <span class="ml-2 rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-700 align-middle">Admin utama</span>
                @endif
              </td>
              <td class="px-4 py-3">{{ $u->email }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                  @if(!$isAdmin)
                    <button type="button"
                      class="rounded-md border border-gray-300 px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50"
                      onclick="toggleEdit({{ $u->id }})">Edit</button>

                    @if(!$isSelf)
                      <form action="{{ route('admin.staff.destroy',$u->id) }}" method="POST"
                            onsubmit="return confirm('Hapus staf ini?')">
                        @csrf @method('DELETE')
                        <button class="rounded-md border border-red-300 px-3 py-1.5 text-xs text-red-600 hover:bg-red-50">
                          Hapus
                        </button>
                      </form>
                    @else
                      <span class="text-xs text-gray-400">akun sendiri</span>
                    @endif
                  @else
                    {{-- admin utama: tanpa tombol aksi --}}
                    <span class="text-xs text-gray-400">—</span>
                  @endif
                </div>
              </td>
            </tr>

            {{-- Form edit hanya untuk non-admin --}}
            @if(!$isAdmin)
              <tr id="row-edit-{{ $u->id }}" class="hidden">
                <td colspan="4" class="px-4 py-4">
                  <div class="rounded-md border bg-gray-50 p-4">
                    <form method="POST" action="{{ route('admin.staff.update',$u->id) }}"
                          class="grid gap-4 grid-cols-1 md:grid-cols-3">
                      @csrf @method('PATCH')

                      <div>
                        <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name',$u->name) }}" required
                               class="w-full rounded-md border px-3 py-2">
                      </div>
                      <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email',$u->email) }}" required
                               class="w-full rounded-md border px-3 py-2">
                      </div>
                      <div>
                        <label class="block text-sm font-medium mb-1">Password (opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                               class="w-full rounded-md border px-3 py-2">
                      </div>

                      <div class="md:col-span-3">
                        <button class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Update</button>
                        <button type="button" class="ml-2 rounded-md border px-4 py-2"
                                onclick="toggleEdit({{ $u->id }})">Batal</button>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            @endif
          @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada staf.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
  </div>

  <script>
    // Toggle kartu tambah
    const cardCreate = document.getElementById('card-create');
    document.getElementById('btn-tambah')?.addEventListener('click',()=>cardCreate.classList.toggle('hidden'));
    document.getElementById('btn-batal')?.addEventListener('click',()=>cardCreate.classList.add('hidden'));

    // Toggle form edit per baris
    function toggleEdit(id){
      document.getElementById('row-edit-'+id)?.classList.toggle('hidden');
    }
  </script>
@endsection
