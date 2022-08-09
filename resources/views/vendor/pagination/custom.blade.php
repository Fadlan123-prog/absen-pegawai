@php
    $params = '';
    if(Request::has('search'))
    {
        $params .= '&search=' . Request::get('search');
    }
    if(Request::has('sap_code'))
    {
        $params .= '&sap_code=' . Request::get('sap_code');
    }
    if(Request::has('sloc'))
    {
        $params .= '&sloc=' . Request::get('sloc');
    }
@endphp
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
      @if ($paginator->onFirstPage())
          <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1">Previous</a>
          </li>
      @else
          <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}{{ $params }}">Previous</a></li>
      @endif
    
      @foreach ($elements as $element)
          @if (is_string($element))
              <li class="page-item disabled">{{ $element }}</li>
          @endif

          @if (is_array($element))
              @foreach ($element as $page => $url)
                  @if ($page == $paginator->currentPage())
                      <li class="page-item active">
                          <a class="page-link">{{ $page }}</a>
                      </li>
                  @else
                      <li class="page-item">
                          <a class="page-link" href="{{ $url }}{{ $params }}">{{ $page }}</a>
                      </li>
                  @endif
              @endforeach
          @endif
      @endforeach
      
      @if ($paginator->hasMorePages())
          <li class="page-item">
              <a class="page-link" href="{{ $paginator->nextPageUrl() }}{{ $params }}" rel="next">Next</a>
          </li>
      @else
          <li class="page-item disabled">
              <a class="page-link" href="#">Next</a>
          </li>
      @endif
  </ul>