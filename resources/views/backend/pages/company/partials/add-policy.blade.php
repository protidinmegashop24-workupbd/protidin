<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-plus"></i> New Policy</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="row" action="{{ route('admin.policy.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title.">
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="details">Short Description</label>
                    <textarea class="textarea" name="details" placeholder="Enter details." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
