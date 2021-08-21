@if (isset($as) && $as == 'modal')
  <div class="modal fade" id="modalSares" tabindex="-1" role="dialog" aria-labelledby="modalSares"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">{{-- tittle --}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="feather feather-x">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
        <div id="error">
        </div>
        @if ($isLoad = isset($type, $count))
          @includeWhen($isLoad , 'inc.component', ['as' => 'loader','type' => $type, 'count' => $count])
        @endif
        <div id="content-body">
          {{-- content --}}
        </div>
      </div>
    </div>
  </div>
@elseif (isset($as) && $as == 'loader')
  <div class="modal-wrap" id="modal-loader">
    @for ($i = 0; $i < $row = $count ? $count : 2; $i++)
      <div class="container-loader">
        <div class="post-loader">
          <div class="avatar-loader"></div>
          <div class="line-loader"></div>
          <div class="line-loader"></div>
        </div>

        <div class="post-loader">
          <div class="avatar-loader"></div>
          <div class="line-loader"></div>
          <div class="line-loader"></div>
        </div>

        <div class="post-loader">
          <div class="avatar-loader"></div>
          <div class="line-loader"></div>
          <div class="line-loader"></div>
        </div>
      </div>
    @endfor
  </div>
@elseif (isset($as) && $as == 'pagination')
  {{ $result->onEachSide(5)->links('vendor.pagination.paging-sares') }}
@else

@endif
