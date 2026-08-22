<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
include "./head.php"; 
?>

<style>
body {
    font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
    background: #f8fafc;
    color: #334155;
    overflow-x: hidden;
}

/* Animated Hero Section */
.hero-section {
    background: linear-gradient(-45deg, #0f172a, #065f46, #047857, #0f172a);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    color: white;
    padding: 85px 0;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 28px 28px;
    opacity: 0.7;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes floatBranch {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(-3deg); }
}

.hero-icon-container {
    width: 130px;
    height: 130px;
    border-radius: 30px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(14px);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, 0.18);
    animation: floatBranch 6s ease-in-out infinite;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.swecha-card {
    background: #ffffff;
    border-radius: 28px;
    padding: 35px;
    margin-bottom: 25px;
    transition: all 0.35s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
}

.swecha-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
}

.impact-card {
    background: linear-gradient(135deg, #10b981 0%, #047857 100%);
    color: #ffffff;
    padding: 35px 25px;
    border-radius: 28px;
    text-align: center;
    box-shadow: 0 15px 40px rgba(16, 185, 129, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.impact-num {
    font-family: 'Outfit', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    color: #fbbf24;
    line-height: 1;
    margin-bottom: 4px;
}

.impact-label {
    font-size: 0.95rem;
    font-weight: 700;
    color: #ffffff;
    opacity: 0.95;
    margin: 0;
}

.activity-card {
    background: #ffffff;
    color: #1e293b;
    padding: 30px;
    border-radius: 24px;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
    transition: all 0.35s ease;
}

.activity-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 38px rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
}

.section-header-title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.5rem;
    font-weight: 800;
    color: #0f172a;
    text-align: center;
    margin-bottom: 45px;
    position: relative;
}

.section-header-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background: linear-gradient(to right, #10b981, #059669);
    margin: 12px auto 0;
    border-radius: 2px;
}

.hero-logo-container {
    width: 170px;
    height: 170px;
    max-width: 170px;
    max-height: 170px;
    border-radius: 50%;
    background: #ffffff;
    padding: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 4px solid rgba(255, 255, 255, 0.35);
    animation: floatBranch 6s ease-in-out infinite;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4), 0 0 35px rgba(52, 211, 153, 0.6);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    overflow: hidden;
    margin: 0 auto;
}

.hero-logo-container:hover {
    transform: scale(1.08) rotate(3deg);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 50px rgba(52, 211, 153, 0.85);
    border-color: #34d399;
}

.hero-logo-img {
    width: 100%;
    height: 100%;
    max-width: 100%;
    max-height: 100%;
    border-radius: 50%;
    object-fit: cover;
    display: block;
}

/* ── Swecha Events & Gallery Slideshow ── */
.swecha-gallery-section {
    padding: 70px 0;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
}

.swecha-slideshow-card {
    background: #0f172a;
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 25px 60px rgba(15, 23, 42, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.swecha-main-slide-viewport {
    width: 100%;
    height: 560px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0f172a;
    overflow: hidden;
    cursor: pointer;
}

.swecha-slide-bg-blur {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: blur(30px) brightness(0.45);
    transform: scale(1.2);
    opacity: 0.9;
    transition: opacity 0.4s ease;
    pointer-events: none;
    z-index: 1;
}

.swecha-slide-img {
    position: relative;
    z-index: 2;
    max-width: 96%;
    max-height: 94%;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: opacity 0.4s ease, transform 0.4s ease;
    opacity: 1;
    border-radius: 12px;
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.swecha-slide-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.5) 60%, transparent 100%);
    padding: 30px 30px 20px;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    pointer-events: none;
}

.swecha-slide-caption {
    font-family: 'Outfit', sans-serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.swecha-slide-subcaption {
    font-size: 0.92rem;
    color: #34d399;
    font-weight: 600;
    margin-top: 4px;
}

.swecha-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
}

.swecha-nav-btn:hover {
    background: #10b981;
    color: #ffffff;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
}

.swecha-nav-prev { left: 20px; }
.swecha-nav-next { right: 20px; }

.swecha-counter-pill {
    position: absolute;
    top: 20px;
    left: 20px;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    z-index: 10;
    letter-spacing: 0.5px;
}

.swecha-controls-top {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    gap: 10px;
    z-index: 10;
}

.swecha-ctrl-icon-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.25s ease;
}

.swecha-ctrl-icon-btn:hover {
    background: #10b981;
    color: #ffffff;
}

/* Scrollable Thumbnails Strip */
.swecha-thumbs-wrapper {
    margin-top: 20px;
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding: 10px 4px 15px;
    scroll-behavior: smooth;
}

.swecha-thumbs-wrapper::-webkit-scrollbar {
    height: 6px;
}

.swecha-thumbs-wrapper::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.swecha-thumbs-wrapper::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 10px;
}

.swecha-thumb-item {
    width: 110px;
    height: 80px;
    flex-shrink: 0;
    border-radius: 14px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.swecha-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.swecha-thumb-item:hover {
    transform: translateY(-3px);
    border-color: #10b981;
}

.swecha-thumb-item.active {
    border-color: #10b981;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
    transform: scale(1.05);
}

.swecha-thumb-item.active::after {
    content: '';
    position: absolute;
    inset: 0;
    border: 2px solid #10b981;
    border-radius: 12px;
    pointer-events: none;
}

/* Lightbox Modal */
.swecha-lightbox-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(2, 6, 23, 0.95);
    backdrop-filter: blur(15px);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.swecha-lightbox-modal.active {
    display: flex;
}

.swecha-lightbox-img {
    max-width: 92vw;
    max-height: 88vh;
    border-radius: 16px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.8);
    object-fit: contain;
}

.swecha-lightbox-close {
    position: absolute;
    top: 25px;
    right: 30px;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 1px solid rgba(255,255,255,0.3);
    transition: all 0.25s ease;
}

.swecha-lightbox-close:hover {
    background: #ef4444;
    color: #ffffff;
}

@media (max-width: 768px) {
    .swecha-main-slide-viewport {
        height: 320px;
    }
    .swecha-slide-caption {
        font-size: 1rem;
    }
}
</style>

<body>
    <?php include "nav.php"; ?>
    
    <!-- Animated Hero Section -->
    <section class="hero-section">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span style="color: #10b981; background: rgba(16, 185, 129, 0.15); font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.82rem; display: inline-block; padding: 6px 16px; border-radius: 99px; margin-bottom: 16px; border: 1px solid rgba(16, 185, 129, 0.3);">
                        <i class="fas fa-code-branch" style="margin-right: 6px;"></i>Open Source & Digital Freedom
                    </span>
                    <h1 style="font-family: 'Outfit', sans-serif; font-size: 3.4rem; font-weight: 900; margin-bottom: 15px; color: #ffffff; line-height: 1.15;">Swecha Club</h1>
                    <p style="font-size: 1.25rem; opacity: 0.92; color: #a7f3d0; max-width: 650px; line-height: 1.6;">Promoting Free & Open Source Software, collaborative learning, Linux systems, and community tech contributions.</p>
                </div>
                <div class="col-md-4 text-center mt-4 mt-md-0">
                    <div class="hero-logo-container" title="Swecha Club Official Logo">
                        <img src="images/swecha_logo.jpg" alt="Swecha Club Logo" class="hero-logo-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Club Overview -->
    <section style="padding: 60px 0;">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-md-12">
                    <div class="swecha-card h-100">
                        <h2 style="font-family: 'Outfit', sans-serif; color: #0f172a; font-weight: 800; font-size: 2rem; margin-bottom: 20px;">About Swecha Club</h2>
                        <p style="color: #475569; line-height: 1.85; margin-bottom: 20px; font-size: 1.05rem;">
                            Swecha Club at SRKREC is dedicated to promoting free software, open source culture, and digital freedom. 
                            We believe in the power of collaborative learning and sharing knowledge through open source contributions.
                        </p>
                        <p style="color: #475569; line-height: 1.85; margin: 0; font-size: 1.05rem;">
                            Our mission is to create awareness about free software alternatives, encourage students to contribute to 
                            open source projects, and build a community of tech enthusiasts who believe in digital freedom.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Activities -->
    <section style="padding: 60px 0; background: #ffffff;">
        <div class="container">
            <h2 class="section-header-title">Recent Activities</h2>
            <div class="row g-4">
                <div class="col-md-6 mb-4">
                    <div class="activity-card h-100">
                        <div class="activity-header mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
                            <span class="badge bg-success mb-2" style="padding: 6px 12px; border-radius: 99px;">FEST 2025</span>
                            <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #0f172a; margin: 0;"><i class="fas fa-calendar-alt" style="color: #10b981; margin-right: 8px;"></i> Swecha Freedom Fest 2025</h4>
                            <p style="font-size: 0.9rem; color: #64748b; margin-top: 6px; font-weight: 600;">March 15-17, 2025 | SRKREC Campus</p>
                        </div>
                        <p style="color: #475569; line-height: 1.7; margin-bottom: 20px;">A three-day celebration of software freedom, digital rights, and open source innovation. An immersive experience of learning, collaboration, and technological advancement.</p>
                        <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 style="color: #0f172a; font-weight: 800; margin-bottom: 12px; font-family: 'Outfit', sans-serif;">Event Highlights</h6>
                            <ul style="list-style: none; padding: 0; margin: 0; color: #334155; font-size: 0.9rem;">
                                <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> Open Source Exhibition & Project Showcase</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> Tech Talks by Industry Experts</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> Hands-on Workshops on Latest Technologies</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> 36-Hour Open Source Hackathon</li>
                                <li><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> Community Networking Sessions</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="activity-card h-100">
                        <div class="activity-header mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
                            <span class="badge bg-primary mb-2" style="padding: 6px 12px; border-radius: 99px;">ONGOING</span>
                            <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #0f172a; margin: 0;"><i class="fas fa-book" style="color: #3b82f6; margin-right: 8px;"></i> Mission Kithab</h4>
                            <p style="font-size: 0.9rem; color: #64748b; margin-top: 6px; font-weight: 600;">Ongoing Initiative | Digital Freedom in Education</p>
                        </div>
                        <p style="color: #475569; line-height: 1.7; margin-bottom: 20px;">A revolutionary digital library initiative making educational resources freely accessible to all. Supporting the vision of knowledge freedom and open education.</p>
                        <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 style="color: #0f172a; font-weight: 800; margin-bottom: 12px; font-family: 'Outfit', sans-serif;">Key Features</h6>
                            <ul style="list-style: none; padding: 0; margin: 0; color: #334155; font-size: 0.9rem;">
                                <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #3b82f6; margin-right: 8px;"></i> Comprehensive E-book Collection (10,000+ titles)</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #3b82f6; margin-right: 8px;"></i> Open Educational Resources & Study Materials</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #3b82f6; margin-right: 8px;"></i> Interactive Learning Platforms</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #3b82f6; margin-right: 8px;"></i> Collaborative Resource Development</li>
                                <li><i class="fas fa-check-circle" style="color: #3b82f6; margin-right: 8px;"></i> Mobile-Friendly Access</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Center -->
    <section style="padding: 60px 0; background: #f8fafc;">
        <div class="container">
            <h2 class="section-header-title">Swecha Learning Center</h2>
            <div class="row g-4">
                <div class="col-md-4 mb-4">
                    <div class="swecha-card h-100" style="border-top: 4px solid #ef4444;">
                        <div style="text-align: center; margin-bottom: 25px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); color: #ef4444; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 12px;">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #0f172a; margin-top: 5px;">Training Programs</h4>
                        </div>
                        <ul style="color: #475569; line-height: 1.8; list-style: none; padding-left: 0; font-size: 0.9rem;">
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #ef4444; margin-right: 8px;"></i> Linux System Administration</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #ef4444; margin-right: 8px;"></i> Open Source Development</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #ef4444; margin-right: 8px;"></i> Modern Web Technologies</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #ef4444; margin-right: 8px;"></i> Python Programming & Apps</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #ef4444; margin-right: 8px;"></i> DevOps Tools & Practices</li>
                            <li><i class="fas fa-check" style="color: #ef4444; margin-right: 8px;"></i> Cloud Computing Open Source</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="swecha-card h-100" style="border-top: 4px solid #10b981;">
                        <div style="text-align: center; margin-bottom: 25px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(16, 185, 129, 0.1); color: #10b981; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 12px;">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #0f172a; margin-top: 5px;">Resources & Tools</h4>
                        </div>
                        <ul style="color: #475569; line-height: 1.8; list-style: none; padding-left: 0; font-size: 0.9rem;">
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Learning Materials Repository</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Interactive Video Tutorials</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Hands-on Practice Projects</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Technical Documentation</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Community Support Forums</li>
                            <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Code Repositories & Examples</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="swecha-card h-100" style="border-top: 4px solid #6366f1;">
                        <div style="text-align: center; margin-bottom: 25px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(99, 102, 241, 0.1); color: #6366f1; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 12px;">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #0f172a; margin-top: 5px;">Special Programs</h4>
                        </div>
                        <ul style="color: #475569; line-height: 1.8; list-style: none; padding-left: 0; font-size: 0.9rem;">
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #6366f1; margin-right: 8px;"></i> Open Source Contribution Workshops</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #6366f1; margin-right: 8px;"></i> Summer of Code Programs</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #6366f1; margin-right: 8px;"></i> Tech Mentorship Initiatives</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #6366f1; margin-right: 8px;"></i> Industry Expert Sessions</li>
                            <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: #6366f1; margin-right: 8px;"></i> Hackathons & Code Sprints</li>
                            <li><i class="fas fa-check" style="color: #6366f1; margin-right: 8px;"></i> Certification Programs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events & Gallery Slideshow -->
    <section class="swecha-gallery-section" id="events">
        <div class="container">
            <div class="text-center mb-5">
                <span style="color: #10b981; background: rgba(16, 185, 129, 0.12); font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.82rem; display: inline-block; padding: 6px 18px; border-radius: 99px; margin-bottom: 12px; border: 1px solid rgba(16, 185, 129, 0.25);">
                    <i class="fas fa-camera-retro me-2"></i>EVENTS & PHOTO GALLERY
                </span>
                <h2 class="section-header-title" style="margin-bottom: 15px;">Swecha Club Events Showcase</h2>
                <p style="color: #64748b; font-size: 1.1rem; max-width: 680px; margin: 0 auto;">
                    Explore moments, workshops, freedom fests, and community hackathons at Swecha Learning Center SRKREC.
                </p>
            </div>

            <!-- Main Slideshow Card -->
            <div class="swecha-slideshow-card">
                <!-- Counter Badge -->
                <div class="swecha-counter-pill" id="swechaCounter">Photo 1 of 31</div>

                <!-- Top Control Buttons -->
                <div class="swecha-controls-top">
                    <button class="swecha-ctrl-icon-btn" id="swechaPlayPauseBtn" onclick="toggleSwechaAutoplay()" title="Play / Pause Slideshow">
                        <i class="fas fa-pause"></i>
                    </button>
                    <button class="swecha-ctrl-icon-btn" onclick="openSwechaLightbox()" title="View Fullscreen">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>

                <!-- Navigation Arrows -->
                <button class="swecha-nav-btn swecha-nav-prev" onclick="prevSwechaSlide()" title="Previous Image">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="swecha-nav-btn swecha-nav-next" onclick="nextSwechaSlide()" title="Next Image">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Main Viewport -->
                <div class="swecha-main-slide-viewport" onclick="openSwechaLightbox()">
                    <img src="images/swecha/swecha_event_01.jpg" alt="Background Blur" class="swecha-slide-bg-blur" id="swechaSlideBgBlur">
                    <img src="images/swecha/swecha_event_01.jpg" alt="Swecha Event Photo 1" class="swecha-slide-img" id="swechaMainSlideImg">
                    
                    <div class="swecha-slide-overlay">
                        <div>
                            <h4 class="swecha-slide-caption" id="swechaSlideCaption">Swecha Freedom Fest & Community Workshops</h4>
                            <div class="swecha-slide-subcaption" id="swechaSlideSubcaption"><i class="fas fa-tag me-1"></i> Swecha Learning Center SRKREC</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scrollable Thumbnail Strip -->
            <div class="swecha-thumbs-wrapper" id="swechaThumbsContainer">
                <!-- 31 Thumbnails dynamically populated -->
            </div>
        </div>
    </section>

    <!-- Fullscreen Lightbox Modal -->
    <div class="swecha-lightbox-modal" id="swechaLightboxModal" onclick="closeSwechaLightbox(event)">
        <span class="swecha-lightbox-close" onclick="closeSwechaLightbox(event)">&times;</span>
        <img src="" alt="Swecha Event Large View" class="swecha-lightbox-img" id="swechaLightboxImg">
    </div>

    <script>
        const totalSwechaPhotos = 31;
        const swechaImageFolder = "images/swecha/";
        let currentSwechaIndex = 1;
        let swechaAutoplayTimer = null;
        let isSwechaPlaying = true;

        const captionsList = [
            "Swecha Freedom Fest 2025 Opening Ceremony",
            "Open Source & Linux Workshop Hands-on Session",
            "Student Developers Community Gathering",
            "Technical Hackathon Team Collaboration",
            "Free Software Freedom Advocacy Talk",
            "Hands-on Python & Web Development BootCamp",
            "Mission Kithab Resource Distribution",
            "Swecha Learning Center Interactive Coding Sprint",
            "Peer-to-Peer Mentorship & Code Review",
            "Faculty & Guest Speakers Keynote Presentation",
            "Open Source Exhibition & Project Showcase",
            "Digital Freedom & Privacy Awareness Workshop",
            "Linux Installation & System Admin Camp",
            "Community Contribution & Pull Request Marathon",
            "Team Building & Collaborative Learning Circle",
            "Certificate Distribution & Achievers Honor",
            "Hackathon Pitching & Product Demos",
            "Industry Expert Interactive Session",
            "Git & GitHub Masterclass Workshop",
            "SRKREC Campus Open Source Hackathon",
            "Swecha Andhra Pradesh Regional Meetup",
            "Collaborative Problem Solving Session",
            "Student Projects Exhibition & Poster Demo",
            "Software Freedom Celebration & Cake Cutting",
            "Web Technologies & DevOps Training Session",
            "Community Learning Circle Discussion",
            "Technical Quiz & Open Source Trivia Winners",
            "Hands-on Lab Training at SRKREC CSD",
            "Open Education & Free Knowledge Initiative",
            "Swecha Club Members Group Photo",
            "Closing Ceremony & Future Roadmap 2025"
        ];

        document.addEventListener("DOMContentLoaded", () => {
            initSwechaGallery();
        });

        function initSwechaGallery() {
            const thumbsContainer = document.getElementById("swechaThumbsContainer");
            if (!thumbsContainer) return;
            thumbsContainer.innerHTML = "";

            for (let i = 1; i <= totalSwechaPhotos; i++) {
                const numStr = String(i).padStart(2, '0');
                const imgPath = `${swechaImageFolder}swecha_event_${numStr}.jpg`;

                const thumbItem = document.createElement("div");
                thumbItem.className = `swecha-thumb-item ${i === 1 ? 'active' : ''}`;
                thumbItem.id = `swechaThumb_${i}`;
                thumbItem.onclick = () => goToSwechaSlide(i);
                thumbItem.innerHTML = `<img src="${imgPath}" alt="Thumbnail ${i}" loading="lazy">`;

                thumbsContainer.appendChild(thumbItem);
            }

            updateSwechaSlideDisplay();
            startSwechaAutoplay();
        }

        function goToSwechaSlide(index) {
            currentSwechaIndex = index;
            updateSwechaSlideDisplay();
            resetSwechaAutoplay();
        }

        function nextSwechaSlide() {
            currentSwechaIndex = currentSwechaIndex >= totalSwechaPhotos ? 1 : currentSwechaIndex + 1;
            updateSwechaSlideDisplay();
            resetSwechaAutoplay();
        }

        function prevSwechaSlide() {
            currentSwechaIndex = currentSwechaIndex <= 1 ? totalSwechaPhotos : currentSwechaIndex - 1;
            updateSwechaSlideDisplay();
            resetSwechaAutoplay();
        }

        function updateSwechaSlideDisplay() {
            const mainImg = document.getElementById("swechaMainSlideImg");
            const bgBlur = document.getElementById("swechaSlideBgBlur");
            const counterPill = document.getElementById("swechaCounter");
            const captionEl = document.getElementById("swechaSlideCaption");
            if (!mainImg || !counterPill || !captionEl) return;

            const numStr = String(currentSwechaIndex).padStart(2, '0');
            const imgPath = `${swechaImageFolder}swecha_event_${numStr}.jpg`;

            mainImg.style.opacity = "0.2";
            if (bgBlur) bgBlur.style.opacity = "0.2";
            setTimeout(() => {
                mainImg.src = imgPath;
                if (bgBlur) bgBlur.src = imgPath;
                mainImg.style.opacity = "1";
                if (bgBlur) bgBlur.style.opacity = "0.9";
            }, 150);

            counterPill.innerText = `Photo ${currentSwechaIndex} of ${totalSwechaPhotos}`;
            captionEl.innerText = captionsList[currentSwechaIndex - 1] || "Swecha Freedom Fest & Community Events";

            document.querySelectorAll(".swecha-thumb-item").forEach(t => t.classList.remove("active"));
            const activeThumb = document.getElementById(`swechaThumb_${currentSwechaIndex}`);
            const thumbsWrapper = document.getElementById("swechaThumbsContainer");
            if (activeThumb && thumbsWrapper) {
                activeThumb.classList.add("active");
                const scrollPos = activeThumb.offsetLeft - (thumbsWrapper.clientWidth / 2) + (activeThumb.clientWidth / 2);
                thumbsWrapper.scrollTo({ left: scrollPos, behavior: 'smooth' });
            }
        }

        function startSwechaAutoplay() {
            if (swechaAutoplayTimer) clearInterval(swechaAutoplayTimer);
            swechaAutoplayTimer = setInterval(() => {
                currentSwechaIndex = currentSwechaIndex >= totalSwechaPhotos ? 1 : currentSwechaIndex + 1;
                updateSwechaSlideDisplay();
            }, 3500);
            isSwechaPlaying = true;
            const btn = document.getElementById("swechaPlayPauseBtn");
            if (btn) btn.innerHTML = '<i class="fas fa-pause"></i>';
        }

        function pauseSwechaAutoplay() {
            if (swechaAutoplayTimer) clearInterval(swechaAutoplayTimer);
            isSwechaPlaying = false;
            const btn = document.getElementById("swechaPlayPauseBtn");
            if (btn) btn.innerHTML = '<i class="fas fa-play"></i>';
        }

        function toggleSwechaAutoplay() {
            if (isSwechaPlaying) pauseSwechaAutoplay();
            else startSwechaAutoplay();
        }

        function resetSwechaAutoplay() {
            if (isSwechaPlaying) startSwechaAutoplay();
        }

        function openSwechaLightbox() {
            const numStr = String(currentSwechaIndex).padStart(2, '0');
            const modal = document.getElementById("swechaLightboxModal");
            const modalImg = document.getElementById("swechaLightboxImg");
            if (modal && modalImg) {
                modalImg.src = `${swechaImageFolder}swecha_event_${numStr}.jpg`;
                modal.classList.add("active");
            }
        }

        function closeSwechaLightbox(e) {
            if (e.target.id === "swechaLightboxModal" || (e.target.className && e.target.className.includes && e.target.className.includes("swecha-lightbox-close"))) {
                const modal = document.getElementById("swechaLightboxModal");
                if (modal) modal.classList.remove("active");
            }
        }
    </script>

    <?php include "footer.php"; ?>
</body>
</html>
