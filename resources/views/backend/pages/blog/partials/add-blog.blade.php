<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-plus"></i> New Blog Post</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="row" action="{{ route('admin.blog.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title." required>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="feature_image">Feature Image</label>
                    <input type="file" class="form-control" name="feature_image" accept="image/*">
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="details">Content</label>
                    <textarea class="textarea" name="details" placeholder="Write the blog post here." style="width: 100%; height: 250px;"></textarea>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" checked>
                        <label class="custom-control-label" for="status">Publish immediately</label>
                    </div>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
        </div>
        </form>
      </div>
    </div>
  </div>
