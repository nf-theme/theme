<!DOCTYPE html>
<html {!! language_attributes() !!}>

  @include('partials.head')

    <body {!! body_class() !!}>

    {!! do_action('get_header') !!}

    @include('partials.header')

    <div class="wrap container" role="document">
      <div class="content">
        <main class="main">
            @yield('content')
        </main>
      </div>
    </div>

    {!! do_action('get_footer') !!}

    @include('partials.footer')

    {!! wp_footer() !!}

    </body>
</html>
