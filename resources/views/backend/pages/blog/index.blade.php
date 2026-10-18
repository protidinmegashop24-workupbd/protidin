@extends('backend.layouts.master')

@section('title')
    Blog - Dashboard
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.css') }}">
    <style>
        .new-user{
            float: right;
        }
        .blog-thumb{
            width: 60px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
@endsection

@section('back-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Blog Posts</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">All Blog Posts</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <div class="row">
                    <div class="col-10"><h3 class="card-title">All Blog Posts</h3></div>
                    <div class="col-2">
                        <button type="button" class="btn btn-default btn-sm pull-right new-user" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> New Post</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="2%">#SL</th>
                    <th width="8%">Image</th>
                    <th width="27%">Title</th>
                    <th width="12%">Date</th>
                    <th width="10%">Status</th>
                    <th width="18%">Action</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($blogs as $key=>$blog)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>
                                @if($blog->feature_image)
                                    <img src="{{ URL::to($blog->feature_image) }}" class="blog-thumb" alt="">
                                @else
                                    --
                                @endif
                            </td>
                            <td>{{ $blog->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($blog->news_date)->format('d M Y') }}</td>
                            <td>
                                @if($blog->status == 1)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>
                                @if($blog->status == 1)
                                    <a href="{{ route('blog.details', $blog->slug) }}" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                @endif
                                <a href="" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{$key}}"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.blog.delete',$blog->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>

                        @include('backend.pages.blog.partials.edit-blog')

                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No blog post yet. Click "New Post" to write the first one.</td>
                        </tr>
                    @endforelse
                </tbody>
              </table>
            </div>
        </div>
    </div>
    @include('backend.pages.blog.partials.add-blog')
  </section>
@endsection

@section('js')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        $(function () {
          $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
          });
        });

        $(function () {
            $('.textarea').summernote({ height: 250 });
        });
    </script>
@endsection
