@if ($paginator->hasPages())

    <div class="pager">
        <ul class="dflex">
            @if ($paginator->onFirstPage())
                <li class="disabled" hidden aria-disabled="true">前へ</li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">前へ</a></li>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true">{{ $element }}</li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    {{-- <li class="page-item active"><a class="page-link" href="#">2</a></li> --}}
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><a>{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">次へ</a></li>
            @else
                <li class="disabled" hidden aria-disabled="true">次へ</li>
            @endif
        </ul>
    </div>

@endif
