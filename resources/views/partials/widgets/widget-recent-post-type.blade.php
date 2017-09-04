<div class="widget-category {{ $layout }}">

    <div class="{{ $layout }}-content" >

        @php

        switch ($layout) {
            case 'list':
                $view = 'partials.widgets.widget-list';
                break;
            case 'thumbnail':
                $view = 'partials.widgets.widget-thumbnail';
                break;
            default:
                // code
                break;
        }

        $shortcode = '[listing post_type="' . $post_type . '" cat="' . $category . '" per_page="' . $number_posts . '" layout="' . $view . '"]';

        echo do_shortcode($shortcode);

        @endphp

    </div>
</div>