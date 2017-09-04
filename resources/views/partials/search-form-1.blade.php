<form class="vcd-form-search" action="{{ esc_url(home_url('/')) }}">
    <input type="text" 
            placeholder="{{ $placeholder }}" 
            name="s" 
            value="{{ get_search_query() }}">
    <button type="submit" class="search-icon">
        <i class="fa fa-search" aria-hidden="true"></i>
    </button>
</form>