@extends('frontend.layouts.master')

@section('front-content')

<section class="sv-hero">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6">
                <div class="sv-hero-content">
                    <span class="sv-badge">Protidin Mega Earn Surveys</span>
                    <h1>Earn Online by Completing Simple Survey Tasks</h1>
                    <p>
                        Discover a simple way to earn online through survey participation on Protidin Mega Earn. 
                        Answer question-based tasks, stay active, and build a steady earning habit with a user-friendly system made for beginners and regular users alike.
                    </p>

                    <ul class="sv-hero-points">
                        <li>✔ Easy survey tasks for active users</li>
                        <li>✔ No advanced skill required</li>
                        <li>✔ Beginner-friendly earning flow</li>
                        <li>✔ Access available surveys after login</li>
                    </ul>

                    <div class="sv-hero-btns">
                        <a href="{{ route('surveys.index') }}" class="sv-btn-primary">
                            Start Survey Earning
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <div class="sv-hero-img">
                    <img src="https://workupbd.com/blog/wp-content/uploads/2026/04/survey-imej-mainpage.png" alt="Survey Earning">
                </div>
            </div>

        </div>
    </div>
</section>

<section class="sv-live-strip">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="sv-live-card">
                    <div class="sv-live-label">Live Earnings Counter</div>
                    <div class="sv-live-amount" id="liveEarningAmount">$128.45</div>
                    <div class="sv-live-sub">
                        Live-style showcase to present the survey earning environment in an engaging way.
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="sv-live-card">
                    <div class="sv-live-label">Users Earning Now</div>
                    <div class="sv-live-users">
                        <span id="liveUserCount">24</span> users are completing surveys right now
                    </div>
                    <div class="sv-live-sub">
                        A dynamic activity section to build motivation and platform trust.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sv-steps">
    <div class="container">
        <div class="sv-section-head text-center">
            <span class="sv-mini-title">Simple Process</span>
            <h2>How Survey Earning Works on Protidin Mega Earn</h2>
            <p>
                Start from your account dashboard, open available surveys, submit your answers, and grow your activity inside the platform step by step.
            </p>
        </div>

        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="sv-step-box">
                    <div class="sv-step-number">01</div>
                    <h3>Login to Your Account</h3>
                    <p>
                        Access the survey area from your Protidin Mega Earn account and explore currently available survey tasks.
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="sv-step-box">
                    <div class="sv-step-number">02</div>
                    <h3>Choose and Complete Surveys</h3>
                    <p>
                        Open a survey, answer the questions carefully, and complete the task according to the instructions.
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="sv-step-box">
                    <div class="sv-step-number">03</div>
                    <h3>Earn and Stay Active</h3>
                    <p>
                        Survey rewards help you stay active inside the platform and build a simple earning routine online.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sv-benefits">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="sv-benefit-content">
                    <span class="sv-mini-title">Why Choose Surveys</span>
                    <h2>A Flexible Earning Option for New and Active Users</h2>
                    <p>
                        Survey tasks are designed for users who want a simple entry point into online earning. 
                        The process is easy to understand, user-friendly, and ideal for building consistent activity inside Protidin Mega Earn.
                    </p>

                    <ul class="sv-benefit-list">
                        <li>✔ Suitable for beginners</li>
                        <li>✔ Easy dashboard access</li>
                        <li>✔ Question-based earning flow</li>
                        <li>✔ Supports consistent platform engagement</li>
                        <li>✔ Clean and simple user experience</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="sv-benefit-card">
                    <div class="sv-benefit-card-icon">
                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                    </div>
                    <h3>Survey Task System</h3>
                    <p>
                        Protidin Mega Earn survey tools are created to make question-based earning more structured and approachable. 
                        You can log in, view available survey titles, open the tasks, and begin completing them with a smooth user journey.
                    </p>

                    <div class="sv-mini-grid">
                        <div class="sv-mini-item">
                            <strong>Easy Access</strong>
                            <span>Open surveys from your account</span>
                        </div>
                        <div class="sv-mini-item">
                            <strong>Simple Tasks</strong>
                            <span>Answer questions and submit</span>
                        </div>
                        <div class="sv-mini-item">
                            <strong>Beginner Friendly</strong>
                            <span>No advanced skill needed</span>
                        </div>
                        <div class="sv-mini-item">
                            <strong>Growth Ready</strong>
                            <span>Stay active and keep earning</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sv-cta">
    <div class="container text-center">
        <div class="sv-cta-box">
            <span class="sv-mini-title">Start Today</span>
            <h2>Explore the Survey Area and Start Your Progress</h2>
            <p>
                Log in to your account, enter the survey page, and begin answering tasks through the Protidin Mega Earn system.
            </p>

            <a href="{{ route('surveys.index') }}" class="sv-btn-primary">
                Go To Survey Page
            </a>
        </div>
    </div>
</section>

@endsection

@section('css')
<style>
.sv-hero{
    padding: 95px 0;
    background: linear-gradient(135deg, #eff8ff 0%, #f8fffb 45%, #ffffff 100%);
    overflow: hidden;
}
.sv-badge{
    display: inline-block;
    background: #e8f7ee;
    color: #15803d;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 999px;
    margin-bottom: 18px;
}
.sv-hero-content h1{
    font-size: 44px;
    font-weight: 800;
    line-height: 1.18;
    color: #0f172a;
    margin-bottom: 16px;
}
.sv-hero-content p{
    font-size: 16px;
    line-height: 1.95;
    color: #64748b;
    margin-bottom: 22px;
    max-width: 590px;
}
.sv-hero-points{
    list-style: none;
    padding: 0;
    margin: 0 0 28px;
}
.sv-hero-points li{
    font-size: 16px;
    color: #1e293b;
    margin-bottom: 10px;
}
.sv-hero-btns{
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.sv-btn-primary{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 28px;
    border-radius: 12px;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #fff !important;
    font-weight: 700;
    text-decoration: none !important;
    box-shadow: 0 14px 28px rgba(22, 163, 74, 0.22);
    transition: all .25s ease;
}
.sv-btn-primary:hover{
    transform: translateY(-2px);
    box-shadow: 0 18px 32px rgba(22, 163, 74, 0.28);
}
.sv-hero-img img{
    max-width: 100%;
    height: auto;
    border-radius: 24px;
    box-shadow: 0 20px 42px rgba(15,23,42,.10);
}

.sv-live-strip{
    padding: 36px 0 20px;
    background: #fff;
}
.sv-live-card{
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 12px 30px rgba(15,23,42,.05);
    height: 100%;
}
.sv-live-label{
    font-size: 14px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 10px;
}
.sv-live-amount{
    font-size: 34px;
    font-weight: 800;
    color: #16a34a;
    line-height: 1.2;
}
.sv-live-users{
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.4;
}
.sv-live-users span{
    color: #2563eb;
}
.sv-live-sub{
    margin-top: 10px;
    color: #64748b;
    font-size: 14px;
    line-height: 1.8;
}

.sv-steps{
    padding: 85px 0 65px;
    background: #ffffff;
}
.sv-section-head{
    max-width: 760px;
    margin: 0 auto;
}
.sv-mini-title{
    display: inline-block;
    color: #16a34a;
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 10px;
}
.sv-section-head h2{
    font-size: 36px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 14px;
}
.sv-section-head p{
    font-size: 16px;
    color: #64748b;
    line-height: 1.9;
}
.sv-step-box{
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 28px 24px;
    box-shadow: 0 10px 24px rgba(15,23,42,.05);
    height: 100%;
}
.sv-step-number{
    font-size: 14px;
    font-weight: 800;
    color: #16a34a;
    margin-bottom: 12px;
}
.sv-step-box h3{
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 12px;
}
.sv-step-box p{
    font-size: 15px;
    color: #64748b;
    line-height: 1.9;
    margin: 0;
}

.sv-benefits{
    padding: 85px 0;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}
.sv-benefit-content h2{
    font-size: 36px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 16px;
}
.sv-benefit-content p{
    font-size: 16px;
    line-height: 1.95;
    color: #64748b;
    margin-bottom: 22px;
}
.sv-benefit-list{
    list-style: none;
    padding: 0;
    margin: 0;
}
.sv-benefit-list li{
    margin-bottom: 11px;
    font-size: 16px;
    color: #1e293b;
}
.sv-benefit-card{
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    box-shadow: 0 18px 36px rgba(15,23,42,.07);
    padding: 32px 28px;
}
.sv-benefit-card-icon{
    width: 72px;
    height: 72px;
    border-radius: 18px;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 18px;
    box-shadow: 0 14px 28px rgba(34, 197, 94, 0.22);
}
.sv-benefit-card h3{
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 14px;
}
.sv-benefit-card p{
    color: #64748b;
    font-size: 15px;
    line-height: 1.9;
    margin-bottom: 24px;
}
.sv-mini-grid{
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}
.sv-mini-item{
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
}
.sv-mini-item strong{
    display: block;
    color: #0f172a;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 6px;
}
.sv-mini-item span{
    color: #64748b;
    font-size: 13px;
}

.sv-cta{
    padding: 80px 0 95px;
    background: #ffffff;
}
.sv-cta-box{
    max-width: 850px;
    margin: 0 auto;
    background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%);
    border: 1px solid #dbeafe;
    border-radius: 26px;
    padding: 42px 24px;
    box-shadow: 0 16px 36px rgba(15,23,42,.06);
}
.sv-cta-box h2{
    font-size: 36px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 14px;
}
.sv-cta-box p{
    color: #64748b;
    font-size: 16px;
    line-height: 1.9;
    max-width: 650px;
    margin: 0 auto 24px;
}

@media (max-width: 991px){
    .sv-hero{
        padding: 75px 0;
    }
    .sv-hero-content h1,
    .sv-section-head h2,
    .sv-benefit-content h2,
    .sv-cta-box h2{
        font-size: 30px;
    }
    .sv-hero-img{
        margin-top: 28px;
    }
}

@media (max-width: 575px){
    .sv-hero-content h1{
        font-size: 26px;
    }
    .sv-live-amount{
        font-size: 28px;
    }
    .sv-live-users{
        font-size: 20px;
    }
    .sv-mini-grid{
        grid-template-columns: 1fr;
    }
    .sv-benefit-card{
        padding: 24px 18px;
    }
}
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let earning = 128.45;
    let users = 24;

    const earningEl = document.getElementById('liveEarningAmount');
    const userEl = document.getElementById('liveUserCount');

    if (earningEl) {
        setInterval(() => {
            const add = (Math.random() * 1.7).toFixed(2);
            earning = parseFloat(earning) + parseFloat(add);
            earningEl.textContent = '$' + earning.toFixed(2);
        }, 3500);
    }

    if (userEl) {
        setInterval(() => {
            const change = Math.floor(Math.random() * 5) - 2;
            users = users + change;

            if (users < 12) users = 12;
            if (users > 48) users = 48;

            userEl.textContent = users;
        }, 2500);
    }
});
</script>
@endsection