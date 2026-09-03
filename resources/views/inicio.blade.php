@extends('layout')

@section('title', 'Chatbot con Expresiones Regulares | Questions TO BE')

@section('content')
<x-chat.workspace :examples="$examples" />
@endsection
