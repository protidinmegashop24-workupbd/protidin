<div class="modal fade" id="edit_{{$key}}">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-edit"></i> Update Blog Post</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.blog.update',$blog->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $blog->title }}" placeholder="Enter Title." required>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="feature_image">Feature Image</label>
                    @if($blog->feature_image)
                        <div class="mb-2"><img src="{{ URL::to($blog->feature_image) }}" style="width:100px;height:70px;object-fit:cover;border-radius:4px;"></div>
                    @endif
                    <input type="file" class="form-control" name="feature_image" accept="image/*">
                    <small class="text-muted">Leave empty to keep the current image.</small>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="details">Content</label>
                    <textarea class="textarea" name="details" placeholder="Write the blog post here." style="width: 100%; height: 250px;">{!! $blog->details !!}</textarea>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="status_{{$key}}" name="status" value="1" {{ $blog->status == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="status_{{$key}}">Published</label>
                    </div>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </div>
        </form>
      </div>
    </div>
  </div>
