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
                    <input type="hidden" name="source_type" id="vmSourceType" value="custom_text">

                    <div class="vm-source-tabs">
                        <div class="vm-source-tab active" id="vmTabCustom" onclick="vmSwitchTab('custom_text')">✍️ Write My Own Text</div>
                        <div class="vm-source-tab" id="vmTabQuran" onclick="vmSwitchTab('quran_ayah')">📖 Pick a Quran Ayah</div>
                    </div>

                    <div id="vmCustomSection">
                        <div class="form-group">
                            <label>Your Text (Bengali, Arabic, or English)</label>
                            <textarea name="text_content" id="vmTextContent" class="form-control" rows="4" maxlength="400" placeholder="Write the text you want shown on the video...">{{ old('text_content') }}</textarea>
                            <small class="text-muted">Max 400 characters. Keep it short for best results (1-2 sentences). A voice reads this aloud automatically if an AI voice is configured.</small>
                        </div>
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
                        এই ভিডিও বানাতে খরচ হবে <strong>${{ number_format($template->cost, 4) }}</strong> (আপনার earning balance থেকে কাটবে)।
                        আপনার বর্তমান ব্যালেন্স: ${{ number_format(Auth::user()->earning_balance, 4) }}
                    </div>

                    <button type="submit" class="btn btn-success btn-lg btn-block mt-4">Generate Video</button>
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
    var surahList = [];

    var surahSelect = document.getElementById('vmSurah');
    var fromSelect = document.getElementById('vmFromAyah');
    var toSelect = document.getElementById('vmToAyah');
    var previewBox = document.getElementById('vmAyahPreview');
    var textArea = document.getElementById('vmTextContent');

    window.vmSwitchTab = function (mode) {
        document.getElementById('vmSourceType').value = mode;
        var isQuran = mode === 'quran_ayah';
        document.getElementById('vmCustomSection').style.display = isQuran ? 'none' : 'block';
        document.getElementById('vmQuranSection').style.display = isQuran ? 'block' : 'none';
        document.getElementById('vmTabCustom').classList.toggle('active', !isQuran);
        document.getElementById('vmTabQuran').classList.toggle('active', isQuran);
        textArea.required = !isQuran;
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
