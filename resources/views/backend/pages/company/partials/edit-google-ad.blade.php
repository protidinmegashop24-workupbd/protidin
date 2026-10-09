<div class="modal fade" id="edit_{{$key}}">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-plus"></i> Update Ad</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.google-ad.update',$google_ad->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <!--<div class="form-group col-lg-12 col-md-12 col-12">-->
                <!--    <label for="name">Position</label>-->
                <!--    <select class="form-control" name="position" required id="">-->
                <!--        <option value="">Select One</option>-->
                <!--        <option value="Head" @if($google_ad->position == 'Head') selected @endif>Head</option>-->
                <!--        <option value="Body" @if($google_ad->position == 'Body') selected @endif>Body</option>-->
                <!--        <option value="Footer" @if($google_ad->position == 'Footer') selected @endif>Footer</option>-->
                <!--    </select>-->
                <!--</div>-->

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="code">{{ $google_ad->position }} Position Code</label>
                    <input type="hidden" class="form-control" name="position" value="{{ $google_ad->position }}">
                    <textarea class="textadrea" name="code" placeholder="Enter code." style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $google_ad->code }}</textarea>
                </div>

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
