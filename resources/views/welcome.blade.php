@extends('layouts.theme.app')

@section('title2', 'Documentos Tributarios')
@section('title', 'Dashboard')

@section('header_actions')
    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm" title="Volver">Volver</a>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
@endsection

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        
        <div class="layout-px-spacing">  
    <div class="row">
        <!-- Gráfico combinado -->
        <div class="col-md-12">
            <div>
                <h1>pagian en blanco</h1>
            </div>
        </div>
    </div>
</div>



@endsection
