@extends('layout')

@section('title', 'Expresiones Regulares')

@section('content')
<x-chat.workspace :examples="$examples" />
@endsection
