<li class="nav-item navbar-search-always">
    <form class="form-inline" action="{{ $item['href'] }}" method="{{ $item['method'] }}">
        <div class="input-group">
            <input class="form-control form-control-navbar" type="search"
                @isset($item['id']) id="{{ $item['id'] }}" @endisset
                name="{{ $item['input_name'] }}"
                placeholder="{{ $item['text'] }}"
                aria-label="{{ $item['text'] }}">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</li>
