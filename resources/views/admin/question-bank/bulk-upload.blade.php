@extends('backend.layouts.master')

@section('title','Bulk Upload Questions (Admin)')
@section('back-content')

<div class="container-fluid mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Bulk Upload Questions</h3>
    <a href="{{ route('admin.question-bank.index') }}" class="btn btn-outline-dark">Back to list</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="font-weight:900;">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger" style="font-weight:900;">{{ session('error') }}</div>
  @endif

  @if(session('skipped') && count(session('skipped')) > 0)
    <div class="alert alert-warning">
      <strong>এই লাইনগুলো স্কিপ করা হয়েছে:</strong>
      <ul class="mb-0">
        @foreach(session('skipped') as $line)
          <li>{{ $line }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <h5>CSV ফরম্যাট</h5>
      <p>Excel/Google Sheets-এ এই কলামগুলো এই ক্রমে বসিয়ে CSV হিসেবে সেভ করুন (হেডার লাইন থাকলেও চলবে, না থাকলেও চলবে):</p>
      <div class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead><tr>
            <th>topic</th><th>question</th><th>option1</th><th>option2</th><th>option3</th><th>option4</th><th>correct_answer</th>
          </tr></thead>
          <tbody>
            <tr>
              <td>general</td>
              <td>বাংলাদেশের রাজধানীর নাম কী?</td>
              <td>ঢাকা</td><td>চট্টগ্রাম</td><td>রাজশাহী</td><td>খুলনা</td>
              <td>ঢাকা</td>
            </tr>
          </tbody>
        </table>
      </div>
      <p class="mb-0">
        <strong>topic</strong> অবশ্যই এই চারটার একটা হতে হবে: <code>general</code>, <code>islamic</code>, <code>bangladesh_gk</code>, <code>sports</code>।
        <strong>correct_answer</strong>-এর লেখা option1-option4 এর কোনো একটার সাথে হুবহু (অক্ষরে অক্ষরে) মিলতে হবে।
      </p>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.question-bank.bulk-upload.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
          <label>CSV File</label>
          <input type="file" name="csv_file" class="form-control" accept=".csv,text/csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload &amp; Add Questions</button>
      </form>
    </div>
  </div>
</div>

@endsection
