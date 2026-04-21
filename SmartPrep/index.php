<?php
require_once 'config.php';
require_once 'includes/auth.php';

// If logged in → redirect to dashboard
if (isLoggedIn()) {
    redirectByRole();
}

// Page title
$title = "Welcome to " . (defined('APP_NAME') ? APP_NAME : 'SmartPrep');
require_once 'includes/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');

/* Dynamic & Premium Aesthestics */
.landing-wrapper {
    font-family: 'Outfit', sans-serif;
    flex: 1; /* take up full layout */
    display: flex;
    flex-direction: column;
    background: #ffffff;
    width: 100%;
    margin: -20px; /* Offset the 20px padding from .main-content if applicable */
}

/* Ensure layout flex is maintained and footer is pushed down */
.layout {
    padding: 0; /* Remove potential padding interfering with full width */
}

.hero-section {
    position: relative;
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    color: white;
    padding: 120px 20px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Subtle decorative blobs in hero */
.hero-section::before {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: rgba(99, 102, 241, 0.4);
    filter: blur(80px);
    border-radius: 50%;
    top: -50px;
    left: -100px;
    animation: float 6s ease-in-out infinite;
}

.hero-section::after {
    content: '';
    position: absolute;
    width: 500px;
    height: 500px;
    background: rgba(236, 72, 153, 0.3);
    filter: blur(100px);
    border-radius: 50%;
    bottom: -150px;
    right: -100px;
    animation: float 8s ease-in-out infinite reverse;
}

@keyframes float {
    0% { transform: translateY(0px) scale(1); }
    50% { transform: translateY(20px) scale(1.05); }
    100% { transform: translateY(0px) scale(1); }
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.hero-title {
    font-size: clamp(3rem, 5vw, 4.5rem);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 24px;
    background: linear-gradient(to right, #ffffff, #a5b4fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.25rem);
    font-weight: 300;
    color: #cbd5e1;
    margin-bottom: 40px;
    line-height: 1.6;
}

.btn-group-custom {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}

.btn-premium {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: white !important;
    font-weight: 600;
    border: none;
    padding: 14px 36px;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    display: inline-flex;
    align-items: center;
}

.btn-premium:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.6);
}

.btn-outline-premium {
    background: rgba(255,255,255,0.05);
    color: white !important;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.2);
    padding: 14px 36px;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    display: inline-flex;
    align-items: center;
}

.btn-outline-premium:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-3px);
    border-color: rgba(255,255,255,0.4);
}

.features-section {
    padding: 100px 20px;
    background: #f8fafc;
}

.feature-card {
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    height: 100%;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    z-index: -1;
    transition: opacity 0.4s ease;
}

.feature-card.card-1::before { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); }
.feature-card.card-2::before { background: linear-gradient(135deg, #fdf4ff 0%, #fae8ff 100%); }
.feature-card.card-3::before { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); }

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

.feature-card:hover::before {
    opacity: 1;
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 24px;
    display: inline-block;
    transition: transform 0.4s ease;
}

.feature-card:hover .feature-icon {
    transform: scale(1.15) rotate(5deg);
}

.feature-title {
    font-weight: 800;
    font-size: 1.35rem;
    color: #0f172a;
    margin-bottom: 15px;
}

.feature-desc {
    color: #64748b;
    font-weight: 400;
    line-height: 1.6;
    font-size: 1.05rem;
}

/* Stats Section */
.stats-section {
    background: white;
    padding: 60px 20px;
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #4f46e5 0%, #ec4899 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 5px;
}

.stat-label {
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 0.85rem;
}

</style>

<div class="landing-wrapper">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">
                Welcome to <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?>
            </h1>
            <p class="hero-subtitle">
                The next-generation smart university management system designed for seamless collaboration between students, teachers, and administrators.
            </p>
            <div class="btn-group-custom">
                <a href="<?= base_url('login.php') ?>" class="btn-premium">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Access Account
                </a>
                <a href="<?= base_url('register.php') ?>" class="btn-outline-premium">
                    <i class="bi bi-person-plus me-2"></i> Join Now
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value">100%</div>
                        <div class="stat-label">Digital</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value">24/7</div>
                        <div class="stat-label">Access</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value">Secure</div>
                        <div class="stat-label">Platform</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            
            <div class="text-center mb-5 pb-3">
                <h2 class="fw-bold" style="color: #0f172a; font-size: 2.75rem; font-family: 'Outfit', sans-serif;">Why Choose <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?>?</h2>
                <p class="text-muted mt-3" style="font-size: 1.15rem; max-width: 600px; margin: 0 auto; color: #64748b;">Everything you need to manage academics comprehensively and efficiently in one intuitive platform.</p>
            </div>

            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="feature-card card-1">
                        <div class="feature-icon">📚</div>
                        <h3 class="feature-title">Course Management</h3>
                        <p class="feature-desc">Organize and manage courses, subjects, and student enrollments intuitively with real-time synchronized updates.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card card-2">
                        <div class="feature-icon">📝</div>
                        <h3 class="feature-title">Attendance & Results</h3>
                        <p class="feature-desc">Effortlessly track student attendance and securely evaluate academic performance with detailed analytics.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card card-3">
                        <div class="feature-icon">📢</div>
                        <h3 class="feature-title">Instant Communication</h3>
                        <p class="feature-desc">Keep everyone in the loop with instant announcements, centralized updates, and reliable messaging channels.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>