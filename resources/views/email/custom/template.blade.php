@extends('email.custom.master') 

@section('subject')

{{$subject}}

@stop 

@section('greeting')

{{$greeting}}

@stop 

@section('content')

{!! $content !!}

@stop
