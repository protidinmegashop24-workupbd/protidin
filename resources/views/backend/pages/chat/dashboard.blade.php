@extends('backend.layouts.master')

@section('title','Live Chat')
@section('back-content')

@include('partials.firebase-chat-config')

<style>
    .pme-admin-chat { display: flex; height: 78vh; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fff; }
    .pme-session-list { width: 280px; border-right: 1px solid #e2e8f0; overflow-y: auto; flex-shrink: 0; }
    .pme-session-item { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; cursor: pointer; }
    .pme-session-item:hover { background: #f8fafc; }
    .pme-session-item.active { background: #eaf7f0; border-left: 3px solid #22ab59; }
    .pme-session-item .name { font-weight: 800; font-size: 14px; color: #172b4d; }
    .pme-session-item .time { font-size: 11px; color: #94a3b8; }
    .pme-session-item .unread-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #dc3545; margin-left: 6px; }
    .pme-chat-main { flex: 1; display: flex; flex-direction: column; }
    .pme-chat-main-header { padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-weight: 800; }
    .pme-chat-main-body { flex: 1; overflow-y: auto; padding: 14px; background: #f3f8fc; }
    .pme-amsg { max-width: 70%; padding: 8px 12px; border-radius: 12px; margin-bottom: 8px; font-size: 13.5px; line-height: 1.5; word-wrap: break-word; }
    .pme-amsg.user { background: #fff; border: 1px solid #e2e8f0; margin-right: auto; }
    .pme-amsg.admin { background: #22ab59; color: #fff; margin-left: auto; }
    .pme-amsg.bot { background: #fef9c3; border: 1px solid #f59e0b; margin-right: auto; }
    .pme-chat-main-footer { display: flex; gap: 8px; padding: 12px; border-top: 1px solid #e2e8f0; }
    .pme-chat-main-footer input { flex: 1; border: 1px solid #dce7f2; border-radius: 10px; padding: 10px 14px; }
    .pme-chat-main-footer button { background: #22ab59; color: #fff; border: none; border-radius: 10px; padding: 0 18px; font-weight: 700; }

    @media (max-width: 767px) {
        .pme-admin-chat { flex-direction: column; height: auto; }
        .pme-session-list { width: 100%; max-height: 180px; border-right: none; border-bottom: 1px solid #e2e8f0; }
        .pme-chat-main { height: 60vh; }
        .pme-amsg { max-width: 88%; }
    }
</style>

<div class="container-fluid mt-3">
    <h4 class="mb-3">Live Chat</h4>

    <div class="pme-admin-chat">
        <div class="pme-session-list" id="pmeSessionList">
            <div class="p-3 text-muted">লোড হচ্ছে...</div>
        </div>
        <div class="pme-chat-main">
            <div class="pme-chat-main-header" id="pmeActiveName">একটা কথোপকথন বেছে নিন</div>
            <div class="pme-chat-main-body" id="pmeActiveBody"></div>
            <div class="pme-chat-main-footer">
                <input type="text" id="pmeAdminInput" placeholder="রিপ্লাই লিখুন..." onkeydown="if(event.key==='Enter') window.pmeAdminSend();">
                <button onclick="window.pmeAdminSend()">Send</button>
            </div>
        </div>
    </div>
</div>

<audio id="pmeNotifySound" preload="auto">
    <source src="https://cdn.jsdelivr.net/gh/naptha/beep-boop-assets/notify.mp3" type="audio/mpeg">
</audio>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import {
        getDatabase, ref, push, onValue, serverTimestamp
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";

    const app = initializeApp(window.PME_FIREBASE_CONFIG);
    const db = getDatabase(app);

    const chatsRef = ref(db, 'chats');
    const sessionListEl = document.getElementById('pmeSessionList');
    const activeNameEl = document.getElementById('pmeActiveName');
    const activeBodyEl = document.getElementById('pmeActiveBody');
    const notifySound = document.getElementById('pmeNotifySound');

    let sessions = {};
    let activeSessionId = null;
    let activeUnsub = null;
    let firstLoad = true;
    const seenMessageIds = new Set();

    function renderSessionList() {
        const entries = Object.entries(sessions).sort(function (a, b) {
            return (b[1].lastMessageAt || 0) - (a[1].lastMessageAt || 0);
        });

        if (entries.length === 0) {
            sessionListEl.innerHTML = '<div class="p-3 text-muted">কোনো কথোপকথন নেই</div>';
            return;
        }

        sessionListEl.innerHTML = entries.map(function ([sid, meta]) {
            const time = meta.lastMessageAt ? new Date(meta.lastMessageAt).toLocaleString() : '';
            return '<div class="pme-session-item' + (sid === activeSessionId ? ' active' : '') + '" onclick="window.pmeOpenSession(\'' + sid + '\')">'
                + '<div class="name">' + (meta.name || 'Guest') + '</div>'
                + '<div class="time">' + time + '</div>'
                + '</div>';
        }).join('');
    }

    onValue(chatsRef, function (snap) {
        const data = snap.val() || {};
        sessions = {};
        Object.keys(data).forEach(function (sid) {
            sessions[sid] = data[sid].meta || {};
        });
        renderSessionList();
    });

    // Plays a notification sound for any new user message across all
    // sessions (skips the initial page-load backlog and admin's own
    // messages, otherwise every historical message would ding on open).
    function watchAllSessionsForSound() {
        onValue(chatsRef, function (snap) {
            const data = snap.val() || {};
            Object.entries(data).forEach(function ([sid, chat]) {
                const messages = chat.messages || {};
                Object.entries(messages).forEach(function ([mid, msg]) {
                    if (seenMessageIds.has(mid)) return;
                    seenMessageIds.add(mid);
                    if (!firstLoad && msg.sender === 'user') {
                        notifySound.play().catch(function () {});
                    }
                });
            });
            firstLoad = false;
        });
    }
    watchAllSessionsForSound();

    window.pmeOpenSession = function (sid) {
        activeSessionId = sid;
        activeNameEl.textContent = (sessions[sid] && sessions[sid].name) || 'Guest';
        activeBodyEl.innerHTML = '';
        renderSessionList();

        if (activeUnsub) activeUnsub();

        activeUnsub = onValue(ref(db, 'chats/' + sid + '/messages'), function (snap) {
            const data = snap.val() || {};
            const list = Object.values(data).sort(function (a, b) { return (a.at || 0) - (b.at || 0); });
            activeBodyEl.innerHTML = list.map(function (m) {
                return '<div class="pme-amsg ' + m.sender + '">' + m.text + '</div>';
            }).join('');
            activeBodyEl.scrollTop = activeBodyEl.scrollHeight;
        });
    };

    window.pmeAdminSend = function () {
        if (!activeSessionId) return;
        const input = document.getElementById('pmeAdminInput');
        const text = input.value.trim();
        if (!text) return;

        push(ref(db, 'chats/' + activeSessionId + '/messages'), {
            sender: 'admin',
            text: text,
            at: serverTimestamp()
        });
        input.value = '';
    };
</script>

@endsection
