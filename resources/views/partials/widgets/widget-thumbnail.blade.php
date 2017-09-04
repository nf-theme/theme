<article class="item">
    <figure>
        <a href="{{ $url }}">
            <img src="{{ asset('images/3x2.png') }}" 
                alt="{{ $title }}" 
                style="background-image: url({{ $thumbnail }});" 
            />
        </a>
    </figure>
    <div class="info">
        <div class="title">
            <a href="{{ $url }}">
                <h3>
                    {{ createExcerptFromContent($title, 10) }}
                </h3>
            </a>
        </div>
        <div class="entry-updated">
            {{ $date }}
        </div>
    </div>
</article>