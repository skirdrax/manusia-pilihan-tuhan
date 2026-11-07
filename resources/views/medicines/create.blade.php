@extends('layouts.app')

@section('title', 'Tambah Obat')
@section('section', 'Tambah obat')

@section('content')
    <div class="page-heading compact-heading"><div><p class="eyebrow">INVENTARIS APOTEK</p><h1>Tambah obat</h1><p class="page-description">Masukkan informasi produk baru ke inventaris.</p></div></div>
    <div class="form-panel"><form action="{{ route('medicines.store') }}" method="POST">@csrf @include('medicines.form', ['submitLabel' => 'Simpan obat'])</form></div>
@endsection