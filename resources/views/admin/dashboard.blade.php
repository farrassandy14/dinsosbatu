@extends('layouts.admin')

@section('pageTitle','Selamat Datang, Admin!')

@section('content')
  {{-- Ringkasan Data --}}
  <h3 class="text-lg font-semibold mb-4">Ringkasan Data</h3>
  <div class="grid gap-4 grid-cols-1 md:grid-cols-3 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-5 flex items-center">
      <div class="text-blue-600 bg-blue-50 p-3 rounded-full mr-4"><i class='bx bx-user-pin text-3xl'></i></div>
      <div>
        <h4 class="text-2xl font-semibold">{{ number_format($totalLansia) }}</h4>
        <p class="text-gray-500 text-sm">Total Data Lansia</p>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-5 flex items-center">
      <div class="text-blue-600 bg-blue-50 p-3 rounded-full mr-4"><i class='bx bx-map-pin text-3xl'></i></div>
      <div>
        <h4 class="text-2xl font-semibold">{{ $totalKec }}</h4>
        <p class="text-gray-500 text-sm">Jumlah Kecamatan</p>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-5 flex items-center">
      <div class="text-blue-600 bg-blue-50 p-3 rounded-full mr-4"><i class='bx bx-buildings text-3xl'></i></div>
      <div>
        <h4 class="text-2xl font-semibold">{{ $totalDesa }}</h4>
        <p class="text-gray-500 text-sm">Jumlah Desa/Kelurahan</p>
      </div>
    </div>
  </div>

  {{-- Aksi Cepat --}}
  <h3 class="text-lg font-semibold mb-4">Aksi Cepat</h3>
  <div class="flex flex-col md:flex-row gap-4">
    <a href="{{ route('admin.lansia.index') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center hover:shadow-md transition">
      <i class='bx bx-edit-alt text-2xl mr-3'></i> <span>Kelola Data Individual</span>
    </a>
    @if(auth()->user()->role==='admin')
    <a href="{{ route('admin.staff.index') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center hover:shadow-md transition">
      <i class='bx bxs-user-account text-2xl mr-3'></i> <span>Manajemen Staf</span>
    </a>
    @endif
  </div>
@endsection
