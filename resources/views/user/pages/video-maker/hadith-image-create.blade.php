@extends('user.layouts.master')
@section('css')
    <style>
        .vm-form-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .vm-preview-thumb {
            width: 100%;
            max-width: 220px;
            border-radius: 12px;
            margin-bottom: 16px;
        }
        .vm-cost-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 14px;
            font-size: 15px;
            margin-top: 16px;
        }
        .vm-cost-box strong { color: #16a34a; font-size: 20px; }
    </style>
@endsection

@section('user-content')
<div class="container-fluid mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">{{ $title }}</h4>
                <a href="{{ route('user.video-maker.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="vm-form-card">
                <img src="{{ asset($imageTemplate->background_path) }}" class="vm-preview-thumb" alt="{{ $imageTemplate->name }}">
                <h5>{{ $imageTemplate->name }}</h5>

                <form action="{{ route('user.video-maker.hadith-image-store') }}" method="POST" id="vmForm">
                    @csrf
                    <input type="hidden" name="image_template_id" value="{{ $imageTemplate->id }}">

                    <div class="form-group" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;">
                        <label style="font-weight:bold;">হাদিস খুঁজে বসান (ঐচ্ছিক)</label>
                        <div class="form-row">
                            <div class="form-group col-7">
                                <select class="form-control" id="vmHadithCollection">
                                    <option value="">সংগ্রহ বাছাই করুন...</option>
                                </select>
                            </div>
                            <div class="form-group col-5">
                                <input type="number" min="1" class="form-control" id="vmHadithNumber" placeholder="হাদিস নং">
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="vmLoadHadithBtn">এই হাদিসটা বসান</button>
                        <div id="vmHadithLoadMsg" class="mt-2" style="font-size:13px;"></div>
                    </div>

                    <div class="form-group">
                        <label>আরবি হাদিস (Arabic)</label>
                        <textarea name="arabic_text" id="vmArabicText" class="form-control" rows="3" maxlength="1000" dir="rtl" required placeholder="উপর থেকে হাদিস বাছাই করুন, অথবা নিজে লিখুন...">{{ old('arabic_text') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>বাংলা অনুবাদ (ঐচ্ছিক)</label>
                        <textarea name="translation_text" id="vmTranslationText" class="form-control" rows="3" maxlength="1000" placeholder="বাংলা অনুবাদ লিখুন...">{{ old('translation_text') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>রেফারেন্স (ঐচ্ছিক)</label>
                        <input type="text" name="reference_text" id="vmReferenceText" class="form-control" maxlength="255" placeholder="যেমন: সহীহ বুখারী, হাদিস নং ১" value="{{ old('reference_text') }}">
                    </div>

                    <div class="vm-cost-box">
                        এটা বানাতে খরচ হবে <strong>${{ number_format($hadithImageCost, 4) }}</strong> (আপনার earning balance থেকে কাটবে)।
                        আপনার বর্তমান ব্যালেন্স: ${{ number_format(Auth::user()->earning_balance, 4) }}
                    </div>

                    <button type="submit" class="btn btn-success btn-lg btn-block mt-4">Generate Image</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
(function () {
    var hadithCollectionsUrl = @json(route('user.video-maker.hadith-collections'));
    var hadithPreviewUrl = @json(route('user.video-maker.hadith-preview'));

    var hadithCollectionSelect = document.getElementById('vmHadithCollection');
    var hadithNumberInput = document.getElementById('vmHadithNumber');
    var hadithLoadMsg = document.getElementById('vmHadithLoadMsg');
    var arabicTextArea = document.getElementById('vmArabicText');
    var translationTextArea = document.getElementById('vmTranslationText');
    var referenceTextInput = document.getElementById('vmReferenceText');

    fetch(hadithCollectionsUrl)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            hadithCollectionSelect.innerHTML = '<option value="">সংগ্রহ বাছাই করুন...</option>';
            if (!data.length) {
                hadithCollectionSelect.innerHTML = '<option value="">এখনো সেটআপ হয়নি -- অ্যাডমিনকে জানান</option>';
                return;
            }
            data.forEach(function (c) {
                var opt = document.createElement('option');
                opt.value = c.key;
                opt.textContent = c.name + ' (' + c.count + 'টা হাদিস)';
                hadithCollectionSelect.appendChild(opt);
            });
        })
        .catch(function () {
            hadithCollectionSelect.innerHTML = '<option value="">লোড করা যায়নি</option>';
        });

    document.getElementById('vmLoadHadithBtn').addEventListener('click', function () {
        var collection = hadithCollectionSelect.value;
        var number = hadithNumberInput.value;
        if (!collection || !number) {
            hadithLoadMsg.className = 'mt-2 text-danger';
            hadithLoadMsg.textContent = 'সংগ্রহ আর হাদিস নম্বর দুটোই বাছাই করুন।';
            return;
        }

        hadithLoadMsg.className = 'mt-2 text-muted';
        hadithLoadMsg.textContent = 'লোড হচ্ছে...';

        fetch(hadithPreviewUrl + '?collection=' + encodeURIComponent(collection) + '&number=' + encodeURIComponent(number))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.error) {
                    hadithLoadMsg.className = 'mt-2 text-danger';
                    hadithLoadMsg.textContent = data.error;
                    return;
                }
                arabicTextArea.value = data.arabic || '';
                translationTextArea.value = data.translation || '';
                referenceTextInput.value = data.reference || '';
                hadithLoadMsg.className = 'mt-2 text-success';
                hadithLoadMsg.textContent = 'বসানো হয়েছে -- নিচে দেখুন, চাইলে এডিট করতে পারবেন।';
            })
            .catch(function () {
                hadithLoadMsg.className = 'mt-2 text-danger';
                hadithLoadMsg.textContent = 'লোড করা যায়নি, আবার চেষ্টা করুন।';
            });
    });
})();
</script>
@endsection
