@extends('layouts.admin')

@section('pageTitle','Kelola Data Lansia')

@section('content')

  {{-- Bar atas: cari & tombol tambah --}}
  <div class="mb-5 flex items-center gap-3">
    <form method="GET" action="{{ route('admin.lansia.index') }}" class="flex-1">
      <input
        name="q"
        value="{{ $q }}"
        placeholder="Cari berdasarkan NIK atau Nama..."
        class="w-full rounded-lg border px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
        autocomplete="off"
      />
    </form>

    <button id="btn-tambah"
      class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white text-sm font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
      <span class="text-base leading-none">+</span>
      <span>Tambah Data Baru</span>
    </button>
  </div>

  {{-- Notif validasi global --}}
  @if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3">
      <strong>Periksa input berikut:</strong>
      <ul class="list-disc pl-5 mt-1">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- FORM TAMBAH (toggle card) --}}
  <div id="card-create" class="mb-6 {{ old('_context')==='create' ? '' : 'hidden' }}">
    <div class="rounded-lg bg-white p-5 shadow-sm">
      <h3 class="mb-4 text-lg font-semibold">Tambah Data</h3>

      <form method="POST" action="{{ route('admin.lansia.store') }}" class="grid gap-4 grid-cols-1 md:grid-cols-3">
        @csrf
        <input type="hidden" name="_context" value="create">

        <div>
          <label class="block text-sm font-medium mb-1">NIK (16 digit)</label>
          <input type="text" name="nik" value="{{ old('nik') }}" required inputmode="numeric"
                 pattern="[0-9]{16}" maxlength="16"
                 class="w-full rounded-md border px-3 py-2">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
          <input type="text" name="nama" value="{{ old('nama') }}" required
                 class="w-full rounded-md border px-3 py-2">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
          <div class="flex items-center gap-6 h-[42px]">
            <label class="inline-flex items-center gap-2">
              <input type="radio" name="jk" value="L" {{ old('jk','L')==='L'?'checked':'' }}> Laki-laki
            </label>
            <label class="inline-flex items-center gap-2">
              <input type="radio" name="jk" value="P" {{ old('jk')==='P'?'checked':'' }}> Perempuan
            </label>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Kecamatan</label>
          <select id="kec_create" name="kecamatan" required class="w-full rounded-md border px-3 py-2">
            <option value="">-- Pilih Kecamatan --</option>
            @foreach ($kecamatan as $kec)
              <option value="{{ $kec }}" {{ old('kecamatan')===$kec?'selected':'' }}>{{ $kec }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Desa/Kelurahan</label>
          <select id="desa_create" name="desa" required class="w-full rounded-md border px-3 py-2">
            <option value="">-- Pilih Desa/Kelurahan --</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Jenis Bantuan</label>
          <select name="jenis_bantuan" required class="w-full rounded-md border px-3 py-2">
            <option value="">-- Pilih Jenis Bantuan --</option>
            <option value="INSENTIF"   {{ old('jenis_bantuan')==='INSENTIF'?'selected':'' }}>
              Bantuan insentif Lansia Rp. 500.000 / bulan
            </option>
            <option value="PERMAKANAN" {{ old('jenis_bantuan')==='PERMAKANAN'?'selected':'' }}>
              Bantuan insentif Lansia Permakanan
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Tahun</label>
          <select name="tahun" required class="w-full rounded-md border px-3 py-2">
            <option value="">-- Pilih Tahun --</option>
            @foreach ($years as $y)
              <option value="{{ $y }}" {{ old('tahun')==$y?'selected':'' }}>{{ $y }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Sumber Dana</label>
          <select name="sumber_dana" required class="w-full rounded-md border px-3 py-2">
            <option value="">-- Pilih Sumber Dana --</option>
            <option value="APBD" {{ old('sumber_dana')==='APBD'?'selected':'' }}>APBD</option>
            <option value="APBN" {{ old('sumber_dana')==='APBN'?'selected':'' }}>APBN</option>
          </select>
        </div>

        <div class="md:col-span-3">
          <button class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan</button>
          <button type="button" id="btn-batal" class="ml-2 rounded-md border px-4 py-2">Batal</button>
        </div>
      </form>
    </div>
  </div>

  {{-- TABEL DATA --}}
  <div class="rounded-lg bg-white p-5 shadow-sm">
    <div class="mb-3 flex items-center justify-between">
      <h3 class="text-lg font-semibold">Daftar Data Lansia</h3>
      <div class="text-sm text-gray-500">Total: {{ number_format($data->total()) }}</div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full border rounded-lg overflow-hidden">
        <thead class="bg-gray-50">
          <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
            <th class="px-3 py-3 w-12">No</th>
            <th class="px-3 py-3 w-56">NIK</th>
            <th class="px-3 py-3 w-48">Nama</th>
            <th class="px-3 py-3 w-32">Jenis Kelamin</th>
            <th class="px-3 py-3 w-36">Kecamatan</th>
            <th class="px-3 py-3 w-44">Desa/Kelurahan</th>
            <th class="px-3 py-3">Jenis Bantuan</th>
            <th class="px-3 py-3 w-20">Tahun</th>
            <th class="px-3 py-3 w-24">Sumber Dana</th>
            <th class="px-3 py-3 w-40 text-right">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          @forelse ($data as $i => $row)
            <tr class="text-sm even:bg-slate-50/40 hover:bg-slate-50">
              <td class="px-3 py-3 align-middle">{{ $data->firstItem() + $i }}</td>
              <td class="px-3 py-3 align-middle font-mono">{{ $row->nik }}</td>
              <td class="px-3 py-3 align-middle">{{ $row->nama }}</td>
              <td class="px-3 py-3 align-middle">{{ $row->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
              <td class="px-3 py-3 align-middle">{{ $row->kecamatan }}</td>
              <td class="px-3 py-3 align-middle">{{ $row->desa }}</td>
              <td class="px-3 py-3 align-middle">
                {{ $row->jenis_bantuan === 'INSENTIF'
                    ? 'Bantuan insentif Lansia Rp. 500.000 / bulan'
                    : 'Bantuan insentif Lansia Permakanan' }}
              </td>
              <td class="px-3 py-3 align-middle">{{ $row->tahun }}</td>
              <td class="px-3 py-3 align-middle">{{ $row->sumber_dana }}</td>
              <td class="px-3 py-3 align-middle">
                <div class="flex justify-end gap-2">
                  {{-- EDIT --}}
                  <button type="button"
                          class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50"
                          onclick="toggleEdit({{ $row->id }})">
                    Edit
                  </button>

                  {{-- HAPUS --}}
                  <form action="{{ route('admin.lansia.destroy',$row->id) }}"
                        method="POST"
                        onsubmit="return confirm('Hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100">
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>

            {{-- Form edit (toggle) --}}
            <tr id="row-edit-{{ $row->id }}" class="hidden bg-gray-50">
              <td colspan="10" class="px-3 py-3">
                <form method="POST" action="{{ route('admin.lansia.update',$row->id) }}"
                      class="grid gap-3 grid-cols-1 md:grid-cols-3">
                  @csrf
                  @method('PUT')

                  <div>
                    <label class="block text-sm font-medium mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik',$row->nik) }}" required
                           inputmode="numeric" pattern="[0-9]{16}" maxlength="16"
                           class="w-full rounded-md border px-3 py-2">
                  </div>

                  <div>
                    <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama',$row->nama) }}" required
                           class="w-full rounded-md border px-3 py-2">
                  </div>

                  <div>
                    <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                    <div class="flex items-center gap-6 h-[42px]">
                      <label class="inline-flex items-center gap-2">
                        <input type="radio" name="jk" value="L" {{ old('jk',$row->jk)==='L'?'checked':'' }}> Laki-laki
                      </label>
                      <label class="inline-flex items-center gap-2">
                        <input type="radio" name="jk" value="P" {{ old('jk',$row->jk)==='P'?'checked':'' }}> Perempuan
                      </label>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium mb-1">Kecamatan</label>
                    <select name="kecamatan" class="w-full rounded-md border px-3 py-2 kec-edit"
                            required data-target="#desa-{{ $row->id }}">
                      <option value="">-- Pilih Kecamatan --</option>
                      @foreach ($kecamatan as $kec)
                        <option value="{{ $kec }}" {{ old('kecamatan',$row->kecamatan)===$kec?'selected':'' }}>{{ $kec }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium mb-1">Desa/Kelurahan</label>
                    <select id="desa-{{ $row->id }}" name="desa" class="w-full rounded-md border px-3 py-2" required>
                      <option value="">-- Pilih Desa/Kelurahan --</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium mb-1">Jenis Bantuan</label>
                    <select name="jenis_bantuan" required class="w-full rounded-md border px-3 py-2">
                      <option value="INSENTIF"   {{ old('jenis_bantuan',$row->jenis_bantuan)==='INSENTIF'?'selected':'' }}>Bantuan insentif Lansia Rp. 500.000 / bulan</option>
                      <option value="PERMAKANAN" {{ old('jenis_bantuan',$row->jenis_bantuan)==='PERMAKANAN'?'selected':'' }}>Bantuan insentif Lansia Permakanan</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium mb-1">Tahun</label>
                    <select name="tahun" required class="w-full rounded-md border px-3 py-2">
                      @foreach ($years as $y)
                        <option value="{{ $y }}" {{ old('tahun',$row->tahun)==$y?'selected':'' }}>{{ $y }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium mb-1">Sumber Dana</label>
                    <select name="sumber_dana" required class="w-full rounded-md border px-3 py-2">
                      <option value="APBD" {{ old('sumber_dana',$row->sumber_dana)==='APBD'?'selected':'' }}>APBD</option>
                      <option value="APBN" {{ old('sumber_dana',$row->sumber_dana)==='APBN'?'selected':'' }}>APBN</option>
                    </select>
                  </div>

                  <div class="md:col-span-3">
                    <button class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Update</button>
                    <button type="button" class="ml-2 rounded-md border px-4 py-2"
                            onclick="toggleEdit({{ $row->id }})">Batal</button>
                  </div>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="10" class="px-3 py-6 text-center text-gray-500">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $data->links() }}</div>
  </div>

  {{-- JS kecil: toggle + kecamatan→desa --}}
  @php $desaMapJs = is_array($desaMap ?? null) ? $desaMap : []; @endphp
  <script>
    const DESA_MAP = @json($desaMapJs);

    function setDesaOptions(kecSelect, desaSelect, selected = '') {
      const list = DESA_MAP[kecSelect.value] || [];
      desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
      list.forEach(function(d) {
        const opt = document.createElement('option');
        opt.value = d; opt.textContent = d;
        if (selected && selected === d) opt.selected = true;
        desaSelect.appendChild(opt);
      });
    }

    function toggleEdit(id) {
      const row = document.getElementById('row-edit-' + id);
      row.classList.toggle('hidden');
      if (!row.classList.contains('hidden')) {
        const kecSel  = row.querySelector('select.kec-edit');
        const desaSel = row.querySelector('select[id^="desa-"]');
        const displayRow = row.previousElementSibling;
        const currentDesa = displayRow.children[5].textContent.trim();
        setDesaOptions(kecSel, desaSel, currentDesa);
      }
    }

    const cardCreate = document.getElementById('card-create');
    document.getElementById('btn-tambah').addEventListener('click', () => cardCreate.classList.toggle('hidden'));
    document.getElementById('btn-batal')?.addEventListener('click', () => cardCreate.classList.add('hidden'));

    const kecCreate  = document.getElementById('kec_create');
    const desaCreate = document.getElementById('desa_create');
    if (kecCreate && desaCreate) {
      kecCreate.addEventListener('change', () => setDesaOptions(kecCreate, desaCreate));
      setDesaOptions(kecCreate, desaCreate, @json(old('desa','')));
    }

    document.querySelectorAll('select.kec-edit').forEach(function(sel) {
      const target = document.querySelector(sel.dataset.target);
      sel.addEventListener('change', () => setDesaOptions(sel, target));
    });
  </script>
@endsection
