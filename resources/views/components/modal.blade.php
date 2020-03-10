<!-- Modal -->
<div class="modal fade" id="{{ $modal_id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modal_id }}_title" aria-hidden="true">
  <div class="modal-dialog @if(isset($modal_class)) {{ $modal_class }}  @endif" role="document">
    <div class="modal-content @if(isset($modal_content_class)) {{ $modal_content_class }}  @endif">
      <div class="modal-header @if(isset($modal_header_class)) {{ $modal_header_class }}  @endif">
        <h5 class="modal-title" id="{{ $modal_id }}_title">
          {{ $modal_title }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body @if(isset($modal_body_class)) {{ $modal_body_class }}  @endif">
        {{ $modal_body }}
      </div>

      @if(isset($modal_footer))
        <div class="modal-footer @if(isset($modal_footer_class)) {{ $modal_footer_class }}  @endif">
          {{ $modal_footer }}
        </div>
      @endif
    </div>
  </div>
</div>
