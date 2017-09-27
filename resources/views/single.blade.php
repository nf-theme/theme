@extends('layouts.app')

@section('content')

    @while(have_posts())
	
		{!! the_post() !!}

        @include('partials.content-single-' . get_post_type())

    @endwhile

@endsection
