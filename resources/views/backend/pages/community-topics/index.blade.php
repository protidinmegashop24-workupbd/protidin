@extends('backend.layouts.master')

@section('title')
    Community Topics - Dashboard
@endsection

@section('css')
<style>
    .emoji-picker { display:flex; flex-wrap:wrap; gap:4px; margin-top:6px; max-width:400px; }
    .emoji-picker button {
        border: 1px solid #ddd; background:#fff; border-radius:6px;
        font-size:1.1rem; width:36px; height:36px; cursor:pointer;
    }
    .emoji-picker button:hover { background:#f0fdf4; border-color:#28a745; }
</style>
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
                <form action="{{ route('admin.community-topics.store') }}" method="POST">
                    @csrf
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-2">
                            <label>Icon (emoji)</label>
                            <input type="text" name="icon" id="add-icon-input" class="form-control" placeholder="e.g. 💻">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Topic Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Technology" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Applies To</label>
                            <select name="applies_to" class="form-control">
                                <option value="both">Both (Product + Q&amp;A/Article)</option>
                                <option value="product">Product only (affiliate product category)</option>
                                <option value="article">Q&amp;A/Article only</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Add Topic</button>
                        </div>
                    </div>
                    <label class="d-block">Pick an emoji (or type/paste your own above):</label>
                    <div class="emoji-picker" data-target="add-icon-input">
                        @foreach(['💻','🛍️','💼','📈','🔗','🏪','⭐','❓','📚','🎨','🎮','📱','💰','🏠','🚗','✈️','🍔','⚽','🎵','📷','🔧','🌐','📢','🎓','🩺','🐾','🎬','🍀'] as $emoji)
                            <button type="button" class="emoji-pick-btn">{{ $emoji }}</button>
                        @endforeach
                    </div>
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
                            <th width="8%">Icon</th>
                            <th width="25%">Name</th>
                            <th width="17%">Slug</th>
                            <th width="17%">Applies To</th>
                            <th width="10%">Posts</th>
                            <th width="18%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topics as $key => $topic)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $topic->icon }}</td>
                                <td>{{ $topic->name }}</td>
                                <td>{{ $topic->slug }}</td>
                                <td>
                                    @php $appliesTo = $topic->applies_to ?? 'both'; @endphp
                                    @if($appliesTo == 'product')
                                        <span class="badge badge-warning">Product only</span>
                                    @elseif($appliesTo == 'article')
                                        <span class="badge badge-info">Q&amp;A/Article only</span>
                                    @else
                                        <span class="badge badge-secondary">Both</span>
                                    @endif
                                </td>
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
                                                <input type="text" class="form-control" name="icon" id="edit-icon-input-{{$key}}" value="{{ $topic->icon }}">
                                                <div class="emoji-picker" data-target="edit-icon-input-{{$key}}">
                                                    @foreach(['💻','🛍️','💼','📈','🔗','🏪','⭐','❓','📚','🎨','🎮','📱','💰','🏠','🚗','✈️','🍔','⚽','🎵','📷','🔧','🌐','📢','🎓','🩺','🐾','🎬','🍀'] as $emoji)
                                                        <button type="button" class="emoji-pick-btn">{{ $emoji }}</button>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ $topic->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Applies To</label>
                                                <select name="applies_to" class="form-control">
                                                    <option value="both" @if($appliesTo=='both') selected @endif>Both (Product + Q&amp;A/Article)</option>
                                                    <option value="product" @if($appliesTo=='product') selected @endif>Product only (affiliate product category)</option>
                                                    <option value="article" @if($appliesTo=='article') selected @endif>Q&amp;A/Article only</option>
                                                </select>
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
                                <td colspan="7" class="text-center">
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

@section('js')
<script>
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('emoji-pick-btn')) return;
        var picker = e.target.closest('.emoji-picker');
        var targetInput = document.getElementById(picker.getAttribute('data-target'));
        if (targetInput) {
            targetInput.value = e.target.textContent.trim();
        }
    });
</script>
@endsection
