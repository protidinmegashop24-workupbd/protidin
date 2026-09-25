<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-user-plus"></i> New User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="row" action="{{ route('admin.user.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <div class="form-group col-lg-6 col-md-6 col-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter User Name">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-6">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required placeholder="Enter Username">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter Role email">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-6">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required placeholder="Phone number must be start  01/+8801/8801 ">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-6">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter Password">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-6">
                    <label for="password-confirm">Confirm Password</label>
                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required placeholder="Enter Confirm Password">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-6">
                    <label for="role_id">Roles</label>
                    <select class="form-control select2" name="role_id" style="width: 100%;">
                        <option selected="selected">---Select One---</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="exampleInputFile">Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input upload" id="image" name="image" type="file" accept="image/*" required onchange="readURL(this);">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id="">Upload</span>
                      </div>
                    </div><br>
                    <div class="newEmployeeUploadImg"><img id="photo" src="#" /></div>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
