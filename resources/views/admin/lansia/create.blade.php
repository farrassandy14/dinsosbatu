@extends('layouts.admin')
@section('pageTitle','Tambah Data')

@section('content')
  <h2 class="text-xl font-semibold mb-4">Tambah Data</h2>

  @if ($errors->any())
    <div class="mb-4 rounded-md bg-red-50 text-red-700 px-4 py-3">
      <strong>Periksa input berikut:</strong>
      <ul class="list-disc pl-5 mt-1">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.lansia.store') }}"
        class="grid gap-4 grid-cols-1 md:grid-cols-3 bg-white rounded-lg shadow-sm p-5">
    @csrf

    <div>
      <label class="block text-sm font-medium mb-1">NIK (16 digit)</label>
      <input type="text" name="nik" value="{{ old('nik') }}" required
             inputmode="numeric" pattern="[0-9]{16}" maxlength="16"
             class="w-full border rounded-md px-3 py-2" placeholder="3579xxxxxxxxxxxx">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
      <input type="text" name="nama" value="{{ old('nama') }}" required
             class="w-full border rounded-md px-3 py-2">
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
      <select id="kecamatan" name="kecamatan" required class="w-full border rounded-md px-3 py-2">
        <option value="">-- Pilih Kecamatan --</option>
        @foreach ($kecamatan as $kec)
          <option value="{{ $kec }}" {{ old('kecamatan')===$kec?'selected':'' }}>{{ $kec }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Desa/Kelurahan</label>
      <select id="desa" name="desa" required class="w-full border rounded-md px-3 py-2">
        <option value="">-- Pilih Desa/Kelurahan --</option>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Jenis Bantuan</label>
      <select name="jenis_bantuan" required class="w-full border rounded-md px-3 py-2">
        <option value="">-- Pilih Jenis Bantuan --</option>
        @foreach ($jenisMap as $key => $label)
          <option value="{{ $key }}" {{ old('jenis_bantuan')===$key?'selected':'' }}>
            {{ $label }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Tahun</label>
      <select name="tahun" required class="w-full border rounded-md px-3 py-2">
        <option value="">-- Pilih Tahun --</option>
        @foreach ($years as $y)
          <option value="{{ $y }}" {{ old('tahun')==$y?'selected':'' }}>{{ $y }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Sumber Dana</label>
      <select name="sumber_dana" required class="w-full border rounded-md px-3 py-2">
        <option value="">-- Pilih Sumber Dana --</option>
        @foreach ($sumberDana as $sd)
          <option value="{{ $sd }}" {{ old('sumber_dana')===$sd?'selected':'' }}>{{ $sd }}</option>
        @endforeach
      </select>
    </div>

    <div class="md:col-span-3">
      <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
        Simpan
      </button>
      <a href="{{ route('admin.lansia.index') }}" class="ml-2 px-4 py-2 rounded-md border">Batal</a>
    </div>
  </form>

  <script>
    const DESA_MAP = @json($desaMap ?? []);
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
    document.addEventListener('DOMContentLoaded', function() {
      const kec = document.getElementById('kecamatan');
      const desa = document.getElementById('desa');
      kec.addEventListener('change', function(){ setDesaOptions(kec, desa); });
      setDesaOptions(kec, desa, @json(old('desa','')));
    });
  </script>
@endsection
