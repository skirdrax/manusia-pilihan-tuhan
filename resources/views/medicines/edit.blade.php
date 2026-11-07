@extends('layouts.app')

@section('title', 'Ubah Obat')
@section('section', 'Ubah obat')

@section('content')
    <div class="page-heading compact-heading"><div><p class="eyebrow">INVENTARIS APOTEK</p><h1>Ubah data obat</h1><p class="page-description">Perbarui informasi {{ $medicine->name }}.</p></div></div>
    <div class="form-panel"><form action="{{ route('medicines.update', $medicine) }}" method="POST">@csrf @method('PUT') @include('medicines.form', ['submitLabel' => 'Simpan perubahan'])</form></div>
@endsection