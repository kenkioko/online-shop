<!-- Modal -->
<div class="modal fade" id="{{ $modal_id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modal_id }}_title" aria-hidden="true">
  <div class="modal-dialog {{ $modal_class ?? ''}}" role="document">
    <div class="modal-content {{ $modal_content_class ?? '' }}">
      <div class="modal-header {{ $modal_header_class ?? '' }}">
        <h5 class="modal-title {{ $modal_title_class ?? '' }}" id="{{ $modal_id }}_title">
          {{ $modal_title }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body {{ $modal_body_class ?? '' }}">
        {{ $modal_body }}
      </div>

      @if(isset($modal_footer))
        <div class="modal-footer {{ $modal_footer_class ?? '' }}">
          {{ $modal_footer }}
        </div>
      @endif
    </div>
  </div>
</div>
