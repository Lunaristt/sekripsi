@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">

            {{-- Tombol Pertama --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}" rel="first">&laquo;&laquo;</a></li>
            @endif

            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            {{-- Daftar Halaman --}}
            @foreach ($elements as $element)
                {{-- Tanda "..." --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Link halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @elseif (
                            $page == 1 ||
                            $page == $paginator->lastPage() ||
                            ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2)
                        )
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @elseif ($page == $paginator->currentPage() - 3 || $page == $paginator->currentPage() + 3)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
            @endif

            {{-- Tombol Terakhir --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" rel="last">&raquo;&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&raquo;&raquo;</span></li>
            @endif

        </ul>
    </nav>
@endif
