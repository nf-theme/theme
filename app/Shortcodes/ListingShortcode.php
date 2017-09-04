<?php

namespace App\Shortcodes;

use MSC\Listing;

class ListingShortcode extends Listing
{
	public function handle($query, $opts)
	{
        //var_dump($opts);
        //var_dump(count($query->found_posts));

        if ($query->have_posts()) {
        	$i = 0;
            while ($query->have_posts()) {
                $query->the_post();

                if (get_the_excerpt() != '') {
                    $excerpt = createExcerptFromContent(get_the_excerpt(), 22);
                } else {
                    $excerpt = '';
                }

                $data = [
                	'count'  => $i,
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => $excerpt,
                    'url' => get_permalink(),
                    'thumbnail' => getPostImage(get_the_ID()),
                    'content' => get_the_content(),
                    'publish_date' => get_the_date(),
                    'date' => get_the_date(),
                    'total' => count($query->posts),
                ];

                if ($opts['layout'] == '') {
                    view('partials.listing-default', $data);
                } else {
                    view($opts['layout'], $data);
                }

                $i++;
            }
        }
	}

}