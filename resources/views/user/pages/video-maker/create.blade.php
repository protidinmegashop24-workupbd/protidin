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
        .vm-source-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }
        .vm-source-tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            color: #64748b;
        }
        .vm-source-tab.active {
            border-color: #16a34a;
            color: #16a34a;
            background: #f0fdf4;
        }
        .vm-ayah-preview {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            margin-top: 10px;
            min-height: 50px;
            font-size: 18px;
            text-align: right;
            direction: rtl;
        }
        .vm-ayah-preview.vm-empty {
            color: #94a3b8;
            font-size: 14px;
            text-align: left;
            direction: ltr;
        }
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
                @if($template->thumbnail_path)
                    <img src="{{ asset($template->thumbnail_path) }}" class="vm-preview-thumb" alt="{{ $template->name }}">
                @endif
                <h5>{{ $template->name }}</h5>
                <p class="text-muted">{{ $template->category }} &middot; {{ $template->duration_seconds }} seconds</p>

                <form action="{{ route('user.video-maker.store') }}" method="POST" id="vmForm">
                    @csrf
                    <input type="hidden" name="template_id" value="{{ $template->id }}">
                    <input type="hidden" name="source_type" id="vmSourceType" value="hadith_image">

                    <div class="vm-source-tabs">
                        <div class="vm-source-tab active" id="vmTabCustom" onclick="vmSwitchTab('hadith_image')">📜 হাদিস ইমেজ</div>
                        <div class="vm-source-tab" id="vmTabQuran" onclick="vmSwitchTab('quran_ayah')">📖 Pick a Quran Ayah</div>
                    </div>

                    <div id="vmCustomSection">
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
                            <textarea name="arabic_text" id="vmArabicText" class="form-control" rows="3" maxlength="1000" dir="rtl" placeholder="উপর থেকে হাদিস বাছাই করুন, অথবা নিজে লিখুন...">{{ old('arabic_text') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>বাংলা অনুবাদ (ঐচ্ছিক)</label>
                            <textarea name="translation_text" id="vmTranslationText" class="form-control" rows="3" maxlength="1000" placeholder="বাংলা অনুবাদ লিখুন...">{{ old('translation_text') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>রেফারেন্স (ঐচ্ছিক)</label>
                            <input type="text" name="reference_text" id="vmReferenceText" class="form-control" maxlength="255" placeholder="যেমন: সহীহ বুখারী, হাদিস নং ১" value="{{ old('reference_text') }}">
                        </div>
                        <small class="text-muted">এটা একটা স্থির ছবি (ভিডিও না) বানাবে, ডাউনলোড করে পোস্ট করা যাবে।</small>
                    </div>

                    <div id="vmQuranSection" style="display:none;">
                        <div class="form-group">
                            <label>Surah</label>
                            <select class="form-control" id="vmSurah" name="quran_surah">
                                <option value="">Loading surahs...</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>From Ayah</label>
                                <select class="form-control" id="vmFromAyah" name="quran_ayah_from"></select>
                            </div>
                            <div class="form-group col-6">
                                <label>To Ayah</label>
                                <select class="form-control" id="vmToAyah" name="quran_ayah_to"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Reciter (Qari)</label>
                            <select class="form-control" id="vmReciter" name="reciter">
                                @foreach($reciters as $key => [$folder, $displayName])
                                    <option value="{{ $key }}" {{ $key === \App\Library\QuranAudioMatcher::DEFAULT_RECITER ? 'selected' : '' }}>{{ $displayName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="vmAyahPreview" class="vm-ayah-preview vm-empty">Select a surah and ayah range to preview the text here.</div>
                        <small class="text-muted d-block mt-1">Up to 20 ayahs per video. Real reciter audio is used automatically -- no AI voice setup needed for this.</small>
                    </div>

                    <div class="vm-cost-box">
                        এটা বানাতে খরচ হবে <strong id="vmCostAmount">${{ number_format($hadithImageCost, 4) }}</strong> (আপনার earning balance থেকে কাটবে)।
                        আপনার বর্তমান ব্যালেন্স: ${{ number_format(Auth::user()->earning_balance, 4) }}
                    </div>

                    <button type="submit" class="btn btn-success btn-lg btn-block mt-4" id="vmSubmitBtn">Generate Image</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
(function () {
    var surahsUrl = @json(route('user.video-maker.surahs'));
    var previewUrl = @json(route('user.video-maker.ayah-preview'));
    var hadithCollectionsUrl = @json(route('user.video-maker.hadith-collections'));
    var hadithPreviewUrl = @json(route('user.video-maker.hadith-preview'));
    var surahList = [];

    var surahSelect = document.getElementById('vmSurah');
    var fromSelect = document.getElementById('vmFromAyah');
    var toSelect = document.getElementById('vmToAyah');
    var previewBox = document.getElementById('vmAyahPreview');
    var arabicTextArea = document.getElementById('vmArabicText');

    var hadithCollectionSelect = document.getElementById('vmHadithCollection');
    var hadithNumberInput = document.getElementById('vmHadithNumber');
    var hadithLoadMsg = document.getElementById('vmHadithLoadMsg');
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

    var templateCost = {{ (float) $template->cost }};
    var hadithImageCost = {{ (float) $hadithImageCost }};

    window.vmSwitchTab = function (mode) {
        document.getElementById('vmSourceType').value = mode;
        var isQuran = mode === 'quran_ayah';
        document.getElementById('vmCustomSection').style.display = isQuran ? 'none' : 'block';
        document.getElementById('vmQuranSection').style.display = isQuran ? 'block' : 'none';
        document.getElementById('vmTabCustom').classList.toggle('active', !isQuran);
        document.getElementById('vmTabQuran').classList.toggle('active', isQuran);
        arabicTextArea.required = !isQuran;

        document.getElementById('vmCostAmount').textContent = '$' + (isQuran ? templateCost : hadithImageCost).toFixed(4);
        document.getElementById('vmSubmitBtn').textContent = isQuran ? 'Generate Video' : 'Generate Image';
    };

    function fillAyahSelect(select, count, selected) {
        select.innerHTML = '';
        for (var i = 1; i <= count; i++) {
            var opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i;
            if (i === selected) opt.selected = true;
            select.appendChild(opt);
        }
    }

    function updatePreview() {
        var surah = surahSelect.value;
        var from = fromSelect.value;
        var to = toSelect.value;
        if (!surah || !from || !to) return;

        previewBox.className = 'vm-ayah-preview vm-empty';
        previewBox.textContent = 'Loading...';

        fetch(previewUrl + '?surah=' + surah + '&from=' + from + '&to=' + to)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.text) {
                    previewBox.className = 'vm-ayah-preview';
                    previewBox.textContent = data.text;
                } else {
                    previewBox.className = 'vm-ayah-preview vm-empty';
                    previewBox.textContent = data.error || 'Could not load this ayah range.';
                }
            })
            .catch(function () {
                previewBox.className = 'vm-ayah-preview vm-empty';
                previewBox.textContent = 'Could not load preview.';
            });
    }

    surahSelect.addEventListener('change', function () {
        var surahNumber = parseInt(surahSelect.value, 10);
        var surah = surahList.find(function (s) { return s.number === surahNumber; });
        if (!surah) return;
        fillAyahSelect(fromSelect, surah.ayah_count, 1);
        fillAyahSelect(toSelect, surah.ayah_count, 1);
        updatePreview();
    });

    fromSelect.addEventListener('change', updatePreview);
    toSelect.addEventListener('change', updatePreview);

    fetch(surahsUrl)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            surahList = data;
            surahSelect.innerHTML = '';
            data.forEach(function (s) {
                var opt = document.createElement('option');
                opt.value = s.number;
                opt.textContent = s.number + '. ' + s.name_english + ' (' + s.name_translation + ')';
                surahSelect.appendChild(opt);
            });
            if (data.length) {
                surahSelect.dispatchEvent(new Event('change'));
            } else {
                surahSelect.innerHTML = '<option value="">Not set up yet -- ask admin</option>';
            }
        })
        .catch(function () {
            surahSelect.innerHTML = '<option value="">Could not load surah list</option>';
        });
})();
</script>
@endsection
