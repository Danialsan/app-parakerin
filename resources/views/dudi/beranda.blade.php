@extends('layouts.app')

@section('title', 'Siswa - PKL SMKN 1 BLEGA')

@section('content')
    <h1>Selamat datang {{ ucfirst(auth()->user()->dudi->nama_pembimbing) }}</h1>
@endsection
