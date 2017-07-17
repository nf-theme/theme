@extends('layouts.app')

@php
    var_dump(get_post_type());exit;
@endphp

@section('content')
    @while(have_posts()) @php(the_post())
        @include('partials.content-single-'.get_post_type())
    @endwhile
@endsection
