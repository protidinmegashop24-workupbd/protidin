@extends('backend.layouts.master')

@section('title')
    {{ $title }} - Dashboard
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Lucky Draw</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Lucky Draw</li>
          </ol>
        </div>
      </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i> New Round
            </button>
        </div>

        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Ticket Price</th>
                            <th>Prize % (1st/2nd/3rd)</th>
                            <th>Tickets Sold</th>
                            <th>Pool</th>
                            <th>Draws At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datas as $data)
                            <tr>
                                <td>{{ $data->title }}</td>
                                <td>${{ number_format((float)$data->price, 4) }}</td>
                                <td>{{ $data->prize_percent_1st }}% / {{ $data->prize_percent_2nd }}% / {{ $data->prize_percent_3rd }}%</td>
                                <td>
                                    <a href="{{ route('admin.lottery-bought-user', $data->id) }}">{{ $data->tickets_sold }}</a>
                                    @if($data->max_tickets_per_user)
                                        <br><small class="text-muted">max {{ $data->max_tickets_per_user }}/user</small>
                                    @endif
                                </td>
                                <td>${{ number_format($data->pool, 4) }}</td>
                                <td>
                                    {{ $data->draws_at ? $data->draws_at->format('d M Y, h:i A') : '-' }}
                                    @if($data->drawn_at)
                                        <br><span class="badge badge-success">Drawn {{ $data->drawn_at->format('d M, h:i A') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($data->status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal{{ $data->id }}"><i class="fas fa-edit"></i></button>

                                        @if(!$data->drawn_at)
                                            @if($data->status == 1)
                                                <a href="{{ route('admin.lottery.deactive', $data->id) }}" class="btn btn-sm btn-warning" title="Deactivate"><i class="fas fa-pause"></i></a>
                                            @else
                                                <a href="{{ route('admin.lottery.active', $data->id) }}" class="btn btn-sm btn-info" title="Activate"><i class="fas fa-play"></i></a>
                                            @endif

                                            <a href="{{ route('admin.lottery.draw-now', $data->id) }}" class="btn btn-sm btn-success" onclick="return confirm('Draw this round right now? This cannot be undone.');" title="Draw Now"><i class="fas fa-dice"></i></a>
                                        @endif

                                        <a href="{{ route('admin.lottery-bought-user', $data->id) }}" class="btn btn-sm btn-secondary" title="View Tickets / Winners"><i class="fas fa-list"></i></a>

                                        @if(!$data->tickets_sold)
                                            <a href="{{ route('admin.lottery.delete', $data->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this round?');" title="Delete"><i class="fas fa-trash"></i></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Edit modal for this round --}}
                            <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.lottery.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit: {{ $data->title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                @include('backend.pages.lottery.partials.form', ['data' => $data])
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="8" class="text-center">No rounds created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- Create modal --}}
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.lottery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Lucky Draw Round</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @include('backend.pages.lottery.partials.form', ['data' => null])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
