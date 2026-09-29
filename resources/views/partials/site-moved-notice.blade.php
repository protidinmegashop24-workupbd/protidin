@if(request()->getHost() === 'workupbd.com')
<div id="site-moved-overlay" style="position:fixed;inset:0;background:rgba(15,23,42,.75);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:18px;max-width:480px;width:100%;padding:32px 28px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.25);position:relative;">
        <button type="button" onclick="document.getElementById('site-moved-overlay').style.display='none';"
                style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:22px;line-height:1;color:#94a3b8;cursor:pointer;">&times;</button>

        <div style="font-size:40px;margin-bottom:10px;">🚀</div>
        <h3 style="font-weight:800;color:#0f172a;margin-bottom:12px;">আমরা নতুন ঠিকানায় চলে এসেছি!</h3>
        <p style="color:#475569;line-height:1.8;margin-bottom:22px;">
            আমাদের সাইট এখন নতুন ঠিকানায়: <strong>protidinmegashop.com</strong><br>
            আপনার একাউন্ট, ব্যালেন্স ও সব তথ্য নিরাপদ আছে। একই ইমেইল ও পাসওয়ার্ড দিয়ে নতুন সাইটে লগইন করুন।
        </p>
        <a href="https://protidinmegashop.com" style="display:inline-block;background:#16a34a;color:#fff;padding:13px 28px;border-radius:10px;font-weight:700;text-decoration:none;">
            নতুন সাইটে যান
        </a>
    </div>
</div>
@endif
