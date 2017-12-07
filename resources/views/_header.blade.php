<!DOCTYPE html>
<html {!! language_attributes() !!}>

  @include('partials.head')

    <body {!! body_class() !!}>

    {!! do_action('get_header') !!}

    @include('partials.header')