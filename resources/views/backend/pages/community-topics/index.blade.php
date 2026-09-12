@extends('backend.layouts.master')

@section('title')
    Community Topics - Dashboard
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Community Topics</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Community Topics</li>
          </ol>
        </div>
      </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Add New Topic</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.community-topics.store') }}" method="POST" class="form-inline">
                    @csrf
                    <div class="form-group mr-2 mb-2">
                        <input type="text" name="icon" class="form-control" placeholder="Emoji (e.g. 💻)" style="width:120px;">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Topic name (e.g. Technology)" required style="width:250px;">
                    </div>
                    <button type="submit" class="btn btn-success mb-2"><i class="fas fa-plus"></i> Add Topic</button>
                </form>
            </div>
        </div>

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">All Topics</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">#SL</th>
                            <th width="10%">Icon</th>
                            <th width="30%">Name</th>
                            <th width="20%">Slug</th>
                            <th width="15%">Posts</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topics as $key => $topic)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $topic->icon }}</td>
                                <td>{{ $topic->name }}</td>
                                <td>{{ $topic->slug }}</td>
                                <td>{{ $topic->posts_count }}</td>
                                <td>
                                    <a href="" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_topic_{{$key}}"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.community-topics.delete', $topic->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Delete this topic? Posts tagged with it will just lose that tag, they will not be deleted.')"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>

                            <div class="modal fade" id="edit_topic_{{$key}}">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header bg-success">
                                      <h4 class="modal-title">Update Topic</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.community-topics.update', $topic->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Icon (emoji)</label>
                                                <input type="text" class="form-control" name="icon" value="{{ $topic->icon }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ $topic->name }}" required>
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
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No topics yet. Run <code>/system-add-community-topics/&lt;token&gt;</code> first to seed the default set, or add one above.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
