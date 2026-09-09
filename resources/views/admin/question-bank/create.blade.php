@extends('backend.layouts.master')

@section('title','Add Question (Admin)')
@section('back-content')

<div class="container-fluid mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Add Question</h3>
    <a href="{{ route('admin.question-bank.index') }}" class="btn btn-outline-dark">Back to list</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger" style="font-weight:900;">
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.question-bank.store') }}" id="qForm">
        @csrf

        <div class="form-group mb-3">
          <label>Topic</label>
          <select name="topic" class="form-control" required>
            <option value="general" {{ old('topic')==='general'?'selected':'' }}>General</option>
            <option value="islamic" {{ old('topic')==='islamic'?'selected':'' }}>Islamic</option>
          </select>
        </div>

        <div class="form-group mb-3">
          <label>Question</label>
          <textarea name="question" class="form-control" rows="2" required>{{ old('question') }}</textarea>
        </div>

        <label>Options (mark the correct one)</label>
        <div id="optionsWrap">
          @for ($i = 0; $i < 4; $i++)
            <div class="input-group mb-2">
              <div class="input-group-text">
                <input type="radio" name="correct_index" value="{{ $i }}" {{ $i===0?'checked':'' }} required aria-label="Correct answer">
              </div>
              <input type="text" name="options[]" class="form-control" placeholder="Option {{ $i + 1 }}" value="{{ old('options.'.$i) }}" {{ $i < 2 ? 'required' : '' }}>
            </div>
          @endfor
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary mb-3" id="addOptionBtn">+ Add another option</button>

        <input type="hidden" name="correct_option" id="correctOptionField">

        <div>
          <button type="submit" class="btn btn-primary">Save Question</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('js')
<script>
document.getElementById('addOptionBtn').addEventListener('click', function () {
    const wrap = document.getElementById('optionsWrap');
    const index = wrap.children.length;
    if (index >= 6) return;

    const row = document.createElement('div');
    row.className = 'input-group mb-2';
    row.innerHTML = `
        <div class="input-group-text">
            <input type="radio" name="correct_index" value="${index}" aria-label="Correct answer">
        </div>
        <input type="text" name="options[]" class="form-control" placeholder="Option ${index + 1}">
    `;
    wrap.appendChild(row);
});

document.getElementById('qForm').addEventListener('submit', function (e) {
    const checked = document.querySelector('input[name="correct_index"]:checked');
    const options = document.querySelectorAll('input[name="options[]"]');
    if (!checked || !options[checked.value] || !options[checked.value].value.trim()) {
        e.preventDefault();
        alert('সঠিক উত্তর হিসেবে যেই অপশনটা মার্ক করেছেন সেটা খালি রাখা যাবে না।');
        return;
    }
    document.getElementById('correctOptionField').value = options[checked.value].value.trim();
});
</script>
@endsection
