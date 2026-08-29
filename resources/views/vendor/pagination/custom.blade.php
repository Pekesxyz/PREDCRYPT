@if ($paginator->hasPages())
<div class="flex items-center justify-between border-t border-border px-4 py-3 sm:px-6 mt-8 w-full">
  <div class="flex flex-col sm:flex-row flex-1 items-center justify-between gap-4">
    {{-- Text Keterangan (Disembunyikan di HP agar lebih lega) --}}
    <div class="hidden sm:block">
      <p class="text-sm text-text-secondary">
        Showing
        <span class="font-medium text-text-primary">{{ $paginator->firstItem() }}</span>
        to
        <span class="font-medium text-text-primary">{{ $paginator->lastItem() }}</span>
        of
        <span class="font-medium text-text-primary">{{ $paginator->total() }}</span>
        results
      </p>
    </div>
    
    {{-- Nomor Pagination (Ditampilkan di semua ukuran layar) --}}
    <div class="w-full sm:w-auto flex justify-center overflow-x-auto pb-1 sm:pb-0">
      <nav aria-label="Pagination" class="isolate inline-flex -space-x-px rounded-md shadow-sm border border-border">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-text-muted border-r border-border bg-bg-tertiary cursor-not-allowed">
              <span class="sr-only">Previous</span>
              <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5">
                <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
              </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-text-secondary border-r border-border bg-bg-primary hover:bg-bg-secondary focus:z-20 transition-colors">
              <span class="sr-only">Previous</span>
              <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5">
                <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
              </svg>
            </a>
        @endif

        {{-- Custom Logic for Max 5 Elements --}}
        @php
            $customElements = [];
            $lastPage = $paginator->lastPage();
            $currentPage = $paginator->currentPage();

            if ($lastPage <= 5) {
                $customElements[] = range(1, $lastPage);
            } else {
                if ($currentPage <= 3) {
                    $customElements[] = range(1, 4);
                    $customElements[] = '...';
                    $customElements[] = [$lastPage];
                } elseif ($currentPage >= $lastPage - 2) {
                    $customElements[] = [1];
                    $customElements[] = '...';
                    $customElements[] = range($lastPage - 3, $lastPage);
                } else {
                    $customElements[] = [1];
                    $customElements[] = '...';
                    $customElements[] = range($currentPage - 1, $currentPage + 1);
                    $customElements[] = '...';
                    $customElements[] = [$lastPage];
                }
            }
            
            $elements = [];
            foreach ($customElements as $item) {
                if (is_string($item)) {
                    $elements[] = $item;
                } else {
                    $urls = [];
                    foreach ($item as $page) {
                        $urls[$page] = $paginator->url($page);
                    }
                    $elements[] = $urls;
                }
            }
        @endphp

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-text-muted border-r border-border bg-bg-primary focus:outline-offset-0">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="relative z-10 inline-flex items-center bg-accent border-r border-border px-4 py-2 text-sm font-semibold text-white focus:z-20">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-text-secondary border-r border-border bg-bg-primary hover:bg-bg-secondary focus:z-20 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-text-secondary bg-bg-primary hover:bg-bg-secondary focus:z-20 transition-colors">
              <span class="sr-only">Next</span>
              <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5">
                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
              </svg>
            </a>
        @else
            <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-text-muted bg-bg-tertiary cursor-not-allowed">
              <span class="sr-only">Next</span>
              <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5">
                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
              </svg>
            </span>
        @endif
      </nav>
    </div>
  </div>
</div>
@endif
