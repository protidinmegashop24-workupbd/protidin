{{--
    Live chat widget (Firebase Realtime Database). Floating green button,
    local Bengali keyword FAQ auto-reply, WhatsApp fallback when the bot
    can't answer, and a live channel to/from the admin dashboard
    (backend.pages.chat.dashboard).

    Setup: fill in resources/views/partials/firebase-chat-config.blade.php
    with your Firebase project's config, then in the Firebase Console set
    Realtime Database rules to (tighten later if you add Firebase Auth):
        {
          "rules": { "chats": { ".read": true, ".write": true } }
        }
--}}
@include('partials.firebase-chat-config')

<style>
    #pmeChatBtn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #22ab59;
        box-shadow: 0 8px 22px rgba(34,171,89,.4);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 99997;
        border: none;
        color: #fff;
        font-size: 26px;
    }
    #pmeChatBtn:hover { background: #1b8f4b; }
    #pmeChatBadge {
        position: absolute;
        top: -2px; right: -2px;
        background: #dc3545;
        color: #fff;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        min-width: 18px;
        height: 18px;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
    }
    #pmeChatWindow {
        position: fixed;
        bottom: 96px;
        right: 24px;
        width: 340px;
        max-width: calc(100vw - 32px);
        height: 460px;
        max-height: calc(100vh - 140px);
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 16px 40px rgba(15,23,42,.22);
        display: none;
        flex-direction: column;
        overflow: hidden;
        z-index: 99998;
        font-family: Arial, Helvetica, sans-serif;
    }
    #pmeChatWindow.open { display: flex; }
    .pme-chat-header {
        background: #22ab59;
        color: #fff;
        padding: 14px 16px;
        font-weight: 800;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .pme-chat-header button {
        background: none;
        border: none;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        line-height: 1;
    }
    .pme-chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
        background: #f3f8fc;
    }
    .pme-msg {
        max-width: 80%;
        padding: 8px 12px;
        border-radius: 12px;
        margin-bottom: 8px;
        font-size: 13.5px;
        line-height: 1.5;
        word-wrap: break-word;
    }
    .pme-msg.user { background: #22ab59; color: #fff; margin-left: auto; border-bottom-right-radius: 2px; }
    .pme-msg.bot, .pme-msg.admin { background: #fff; color: #172b4d; border: 1px solid #e2e8f0; margin-right: auto; border-bottom-left-radius: 2px; }
    .pme-msg.admin { border-color: #22ab59; }
    .pme-msg a { color: inherit; text-decoration: underline; }
    .pme-chat-footer {
        display: flex;
        gap: 6px;
        padding: 10px;
        border-top: 1px solid #e2e8f0;
    }
    .pme-chat-footer input {
        flex: 1;
        border: 1px solid #dce7f2;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13.5px;
    }
    .pme-chat-footer button {
        background: #22ab59;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0 14px;
        font-weight: 700;
        cursor: pointer;
    }
</style>

<button id="pmeChatBtn" onclick="window.pmeChatToggle()">
    💬
    <span id="pmeChatBadge"></span>
</button>

<div id="pmeChatWindow">
    <div class="pme-chat-header">
        <span>Protidin Mega Earn Support</span>
        <button onclick="window.pmeChatToggle()">&times;</button>
    </div>
    <div class="pme-chat-body" id="pmeChatBody"></div>
    <div class="pme-chat-footer">
        <input type="text" id="pmeChatInput" placeholder="আপনার প্রশ্ন লিখুন..." onkeydown="if(event.key==='Enter') window.pmeChatSend();">
        <button onclick="window.pmeChatSend()">Send</button>
    </div>
</div>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import {
        getDatabase, ref, push, onChildAdded, serverTimestamp, set
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";

    const app = initializeApp(window.PME_FIREBASE_CONFIG);
    const db = getDatabase(app);

    // Same session persists across visits (per browser) so the admin sees
    // one continuous conversation per user instead of a new thread every
    // page load. Logged-in users get an identifiable session id; guests
    // get a random one stored in localStorage.
    const isLoggedIn = @json(Auth::check());
    const userLabel = @json(Auth::check() ? (Auth::user()->name . ' (' . Auth::user()->code . ')') : null);
    const userId = @json(Auth::check() ? Auth::id() : null);

    let sessionId = localStorage.getItem('pme_chat_session');
    if (!sessionId) {
        sessionId = (isLoggedIn ? 'user_' + userId + '_' : 'guest_') + Math.random().toString(36).slice(2, 10);
        localStorage.setItem('pme_chat_session', sessionId);
    }

    const metaRef = ref(db, 'chats/' + sessionId + '/meta');
    set(metaRef, {
        name: userLabel || 'Guest',
        userId: userId,
        lastMessageAt: serverTimestamp()
    });

    const messagesRef = ref(db, 'chats/' + sessionId + '/messages');

    const body = document.getElementById('pmeChatBody');
    const badge = document.getElementById('pmeChatBadge');
    const chatWindow = document.getElementById('pmeChatWindow');
    let unread = 0;
    let greeted = false;

    function addBubble(sender, text) {
        const div = document.createElement('div');
        div.className = 'pme-msg ' + sender;
        div.innerHTML = text;
        body.appendChild(div);
        body.scrollTop = body.scrollHeight;
    }

    onChildAdded(messagesRef, function (snap) {
        const msg = snap.val();
        if (!msg) return;
        addBubble(msg.sender, msg.text);
        greeted = true;

        if (msg.sender !== 'user' && !chatWindow.classList.contains('open')) {
            unread++;
            badge.textContent = unread;
            badge.style.display = 'flex';
        }
    });

    window.pmeChatToggle = function () {
        chatWindow.classList.toggle('open');
        if (chatWindow.classList.contains('open')) {
            unread = 0;
            badge.style.display = 'none';
            if (!greeted) {
                greeted = true;
                sendMessage('bot', 'আসসালামু আলাইকুম! আমি Protidin Mega Earn-এর সাহায্যকারী। Job, Withdraw, Verification, Premium account, Referral, Deposit অথবা Ban/Block নিয়ে প্রশ্ন থাকলে লিখুন।');
            }
        }
    };

    function sendMessage(sender, text) {
        push(messagesRef, {
            sender: sender,
            text: text,
            at: serverTimestamp()
        });
        set(metaRef, {
            name: userLabel || 'Guest',
            userId: userId,
            lastMessageAt: serverTimestamp()
        });
    }

    // Keyword -> Bengali FAQ answer. Matched against the lowercased
    // message; first matching topic wins. Grounded in this site's real
    // flows (exact numbers like minimum withdraw are admin-configurable,
    // so the bot points to the live page instead of a number that could
    // go stale).
    const FAQ = [
        {
            keywords: ['job', 'জব', 'কাজ', 'টাস্ক', 'ptc'],
            answer: 'ড্যাশবোর্ড থেকে "Find Job" বা "Post PTC Job" মেনুতে গিয়ে উপলব্ধ কাজ দেখতে পারবেন। কাজ সম্পন্ন করার পর তা Review-এর জন্য জমা হয়, অনুমোদন হলে আপনার earning balance-এ টাকা যোগ হয়।'
        },
        {
            keywords: ['withdraw', 'উইথড্র', 'টাকা তুল', 'ক্যাশ আউট', 'withdrawal'],
            answer: 'Withdraw করতে ড্যাশবোর্ডের "Withdraw" মেনুতে যান। সর্বনিম্ন withdraw পরিমাণ ও মাধ্যম (bKash/Nagad ইত্যাদি) ওই পেজেই দেখানো থাকে। রিকোয়েস্ট করার পর অ্যাডমিন অনুমোদন করলেই টাকা পাবেন।'
        },
        {
            keywords: ['verify', 'ভেরিফাই', 'verification', 'kyc', 'nid'],
            answer: 'অ্যাকাউন্ট ভেরিফাই করতে Profile থেকে "Verify Account" অপশনে যান — NID/জন্ম নিবন্ধন দিয়ে ফ্রিতে, অথবা আপনার earning/deposit balance থেকে ইনস্ট্যান্ট ভেরিফাই — দুটো অপশনই আছে।'
        },
        {
            keywords: ['premium', 'প্রিমিয়াম', 'vip', 'upgrade'],
            answer: 'প্রিমিয়াম/আপগ্রেড সংক্রান্ত অফার থাকলে সেটা ড্যাশবোর্ডে বা নোটিফিকেশনে দেখানো হয়। এই মুহূর্তে নির্দিষ্ট কোনো তথ্য দরকার হলে নিচের WhatsApp লিংকে যোগাযোগ করুন।'
        },
        {
            keywords: ['referral', 'রেফার', 'রেফারেল', 'invite', 'commission'],
            answer: 'আপনার রেফারেল লিংক Profile/Dashboard-এ পাবেন। কেউ আপনার লিংক দিয়ে জয়েন করে ডিপোজিট/আর্নিং করলে আপনি কমিশন পাবেন — হার (%) সাইটের নীতিমালা অনুযায়ী নির্ধারিত।'
        },
        {
            keywords: ['deposit', 'ডিপোজিট', 'জমা', 'টাকা জমা'],
            answer: 'Deposit করতে "Deposit" মেনুতে যান — bKash/Nagad ম্যানুয়াল পদ্ধতি বা ইনস্ট্যান্ট গেটওয়ে (ShopPay) দিয়ে সাথে সাথে ডিপোজিট করতে পারবেন।'
        },
        {
            keywords: ['ban', 'ব্যান', 'block', 'suspend', 'সাসপেন্ড', 'বন্ধ'],
            answer: 'অ্যাকাউন্ট ব্যান/সাসপেন্ড হলে সাধারণত নিয়ম ভঙ্গের কারণে হয় (যেমন একাধিক অ্যাকাউন্ট, জাল কাজ)। কারণ জানতে বা আপিল করতে নিচের WhatsApp লিংকে সরাসরি অ্যাডমিনের সাথে যোগাযোগ করুন।'
        }
    ];

    function findAnswer(text) {
        const lower = text.toLowerCase();
        for (const item of FAQ) {
            if (item.keywords.some(function (k) { return lower.includes(k); })) {
                return item.answer;
            }
        }
        return null;
    }

    window.pmeChatSend = function () {
        const input = document.getElementById('pmeChatInput');
        const text = input.value.trim();
        if (!text) return;

        sendMessage('user', text);
        input.value = '';

        const answer = findAnswer(text);
        setTimeout(function () {
            if (answer) {
                sendMessage('bot', answer);
            } else {
                sendMessage('bot', 'দুঃখিত, এই প্রশ্নের উত্তর আমার কাছে নেই। সরাসরি আমাদের সাথে যোগাযোগ করুন: <a href="https://wa.me/8801914402558" target="_blank">WhatsApp-এ মেসেজ করুন</a>');
            }
        }, 500);
    };
</script>
