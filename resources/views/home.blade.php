@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><strong id="username"  tag="{{auth()->user()->username}}">{{auth()->user()->username}}</strong> Welcome... to this beautiful admin panel.</h1>
@stop

@section('content')
    <div  id="prueba">

    </div>

@stop

@section('css')
    <link rel="stylesheet" href="build/assets/app-fa0df06d.css" >
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
