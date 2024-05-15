<!-- php
    $item->appends(request()->all());
endphp -->
@if( $item->lastPage() > 1  )
    <nav>
        <ul class="pagination pagination-sm">

            @if($item->currentPage()>1)
                <li class="page-item" id="example_previous">
                    <a href="{{ $item->previousPageUrl() }}" class="page-link" >{{ $item->currentPage()-1 }}</a>
                </li>
            @endif


            <li class="page-item active" id="example_previous">
                    <a href="{{ $item->url($item->currentPage()) }}" class="page-link" >{{ $item->currentPage() }}</a>
            </li>


            @if($item->currentPage()<$item->lastPage())
            <li class="page-item" id="example_previous">
                    <a href="{{ $item->nextPageUrl() }}" class="page-link" >{{ $item->currentPage()+1 }}</a>
            </li>
            @endif

        </ul>
    </nav>
@endif