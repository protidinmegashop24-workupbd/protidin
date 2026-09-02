@extends('backend.layouts.master')
@section('title')
Community Post Management
@endsection
@section('css')
    <style>
        .card {
            border: 1px solid #e1e1e1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        /* Alpha 6 specific adjustments */
        .search-row {
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .table thead th {
            border-top: none;
            background-color: #292b2c;
            color: white;
        }
        /* Modal Footer Customization for Left/Right buttons */
        .modal-footer-custom {
            display: flex;
            justify-content: space-between; /* Delete left e, Close right e thakar jonno */
            width: 100%;
        }
    </style>
@endsection
@section('back-content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Community Post Management</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Community Rate</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div id="alertContainer"></div>
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Community Post Management</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="search-row">
                            <div>
                                <form class="row" action="{{route('admin.cummunityPostList')}}" method="GET">
                                    <!-- Search Box -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="searchInput">Search Post For Maintain</label>
                                            <div class="input-group">
                                                <input type="number" name="postId" value="{{ request()->get('postId') }}" class="form-control" id="searchInput" placeholder="Post ID">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="submit" name="action" value="findById"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form class="row" action="{{route('admin.cummunityPostList')}}" method="GET">
                                    <!-- From Date -->
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="fromDate">From Date</label>
                                            <input type="date" class="form-control" id="fromDate" name="fromDate" value="{{ request()->get('fromDate') }}">
                                        </div>
                                    </div>

                                    <!-- To Date -->
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="toDate">To Date</label>
                                            <input type="date" class="form-control" id="toDate" name="toDate" value="{{ request()->get('toDate') }}">
                                        </div>
                                    </div>

                                    <!-- Filter Button -->
                                    <div class="col-md-2" style="margin-top: 30px;">
                                        <button type="submit" class="btn btn-success btn-block" name="action" value="findByDate">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 2nd Row: Data Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-block p-0"> <!-- card-block used in alpha version -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0" id="dataTable">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="max-width:15px;">#</th>
                                                <th style="max-width:40px;">User Code</th>
                                                <th style="max-width:40px;">Post Id</th>
                                                <th>Name</th>
                                                <th>Time</th>
                                                <th>Image</th>
                                                <th>text</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($posts as $post)
                                                <tr class="text-center" 
                                                    data-id="{{ $post->id }}"
                                                    data-userid="{{ $post->userId ?? 'annonymous' }}"
                                                    data-username="{{ $post->user?->name ?? 'annonymous' }}"
                                                    data-date="{{ $post->created_at->format('Y-m-d H:i') }}"
                                                    data-content="{{ \Illuminate\Support\Str::words(strip_tags($post->postContent), 50, '...') }}"
                                                    data-image="{{ $post->image ? asset($post->image) : '' }}"
                                                >
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $post->userId ?? 'annonymous' }}</td>
                                                    <td>{{ $post->id }}</td>
                                                    <td>{{ $post->user?->name ?? 'annonymous' }}</td>
                                                    <td>{{ $post->created_at->diffForHumans() }}</td>
                                                    <td>
                                                        @if($post->image)
                                                            <img src="{{ asset($post->image) }}" alt="post image" style="max-height:50px">
                                                        @else
                                                            No Image
                                                        @endif
                                                    </td>
                                                    <td>{!! \Illuminate\Support\Str::words(strip_tags($post->postContent), 5, '...') !!}</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" onclick="copyToClipboard('{{route('publicPostLink',$post->id) }}')" target="_blank">
                                                            <i class="fa fa-copy"></i> Copy
                                                        </a>
                                                        <button class="btn btn-info btn-sm view-btn" data-toggle="modal" data-target="#detailsModal">
                                                            <i class="fa fa-eye"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 3rd Row: Pagination -->        
                @if ($posts->hasPages())
                    <div class="row mt-3">
                        <div class="col-12">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $posts->previousPageUrl() ?? '#' }}" tabindex="-1">Previous</a>
                                    </li>
                                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                        <li class="page-item  {{ $posts->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link {{ $posts->currentPage() == $page ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    <li class="page-item {{ $posts->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $posts->nextPageUrl() ?? '#' }}">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>



@endsection
@section('js')
<!-- Dynamic Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Post Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="m_id"></span></p>
                <p><strong>User ID:</strong> <span id="m_userid"></span></p>
                <p><strong>Name:</strong> <span id="m_name"></span></p>
                <p><strong>Date:</strong> <span id="m_date"></span></p>
                <p><strong>Content:</strong> <span id="m_content"></span></p>
                <img id="m_image" src="" style="max-width:100%; margin-top:10px;" />
            </div>
            <div class="modal-footer" style="justify-content: space-between">
                <button type="button" class="btn btn-danger" onclick="showDeleteConfirm()">
                    <i class="fa fa-trash"></i> Delete
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

  <!-- Confirmation Modal (Are you sure?) -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.deleteFeedPost') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="deletePostId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this post?
                </div>
                <div class="modal-footer" style="justify-content: space-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>

<script>
 $(document).ready(function() {
    $('.view-btn').on('click', function() {
        var tr = $(this).closest('tr');

        $('#m_id').text(tr.data('id'));
        $('#m_userid').text(tr.data('userid'));
        $('#m_name').text(tr.data('username'));
        $('#m_date').text(tr.data('date'));
        $('#m_content').html(tr.data('content'));

        var img = tr.data('image');
        if(img){
            $('#m_image').attr('src', img).show();
        } else {
            $('#m_image').hide();
        }

        // Set ID for static delete form
        $('#deletePostId').val(tr.data('id'));
    });
});

// Show static delete modal
function showDeleteConfirm() {
    // Hide the first modal
    $('#detailsModal').modal('hide');

    // Wait for the hide transition to finish
    $('#detailsModal').on('hidden.bs.modal', function () {
        // Show the second modal
        $('#deleteConfirmModal').modal('show');

        // Remove the handler to avoid multiple triggers
        $(this).off('hidden.bs.modal');
    });
}
$('#deleteConfirmModal').on('hidden.bs.modal', function () {
    // Remove any remaining backdrop manually
    $('.modal-backdrop').remove();
});
function copyToClipboard(text) {
    // Create a temporary input element
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);

    // Select the text
    tempInput.select();
    tempInput.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text
    document.execCommand('copy');

    // Remove the temporary input
    document.body.removeChild(tempInput);

    // Optional: alert / toast
    alert('Link copied to clipboard!');
}
</script>
@endsection