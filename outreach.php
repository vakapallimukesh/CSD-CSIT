<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = 'outreach.php';
include 'head.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outreach & Community Engagement - SRKR CSD & CSIT</title>
    <meta name="description" content="Departmental Outreach initiatives by CSD & CSIT at SRKR Engineering College. School computer literacy, industrial visits, rural tech adoption, and community workshops.">
    <link rel="icon" href="logo-bg.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            color: #0f172a;
        }

        /* ── 1. Hero Section ── */
        .outreach-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            color: #ffffff;
            padding: 90px 0 70px 0;
            position: relative;
            overflow: hidden;
        }

        .outreach-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.12) 1px, transparent 1px);
            background-size: 32px 32px;
            opacity: 0.8;
        }

        .hero-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(239, 68, 68, 0.18);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            padding: 6px 20px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
            color: #ffffff !important;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }

        .hero-title span {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #cbd5e1 !important;
            line-height: 1.75;
            max-width: 740px;
            margin: 0 auto 20px auto;
        }

        /* Impact Metric Cards */
        .outreach-metric-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 24px 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .outreach-metric-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(239, 68, 68, 0.4);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .metric-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: #f87171;
            line-height: 1;
            margin-bottom: 6px;
        }

        .metric-label {
            font-size: 0.9rem;
            color: #e2e8f0;
            font-weight: 600;
        }

        /* ── 2. Section Header ── */
        .section-header-custom {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-tag {
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #ef4444;
            display: block;
            margin-bottom: 8px;
        }

        .section-header-custom h2 {
            font-size: 2.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .accent-bar {
            width: 70px;
            height: 4px;
            background: linear-gradient(to right, #ef4444, #f87171);
            margin: 0 auto 16px auto;
            border-radius: 4px;
        }

        .section-header-custom p {
            color: #64748b;
            font-size: 1.05rem;
            max-width: 640px;
            margin: 0 auto;
        }

        /* ── 3. Pillars Cards ── */
        .pillar-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 35px 28px;
            height: 100%;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .pillar-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ef4444, #f97316);
        }

        .pillar-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 45px rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .pillar-icon-box {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 22px;
        }

        .pillar-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .pillar-desc {
            color: #64748b;
            font-size: 0.96rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .pillar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pillar-list li {
            font-size: 0.9rem;
            color: #475569;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pillar-list li i {
            color: #ef4444;
            font-size: 0.85rem;
        }

        /* ── 4. Activities Grid & Filter ── */
        .outreach-filter-pills {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .outreach-filter-btn {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #64748b;
            padding: 8px 22px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .outreach-filter-btn:hover,
        .outreach-filter-btn.active {
            background: #ef4444;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.25);
        }

        .activity-card-custom {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
            transition: all 0.35s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .activity-card-custom:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }

        .activity-header-banner {
            position: relative;
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            overflow: hidden;
        }

        .activity-header-banner.school-bg {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .activity-header-banner.industrial-bg {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .activity-header-banner.rural-bg {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }

        .activity-banner-icon {
            font-size: 3.8rem;
            opacity: 0.25;
            position: absolute;
            right: 20px;
            bottom: -5px;
        }

        .activity-category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 14px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Card Slideshow CSS */
        .card-slideshow-viewport {
            position: relative;
            height: 260px;
            width: 100%;
            overflow: hidden;
            background: #0f172a;
        }

        .card-slide-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .card-slide-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
            opacity: 0.85;
            transition: all 0.25s ease;
        }

        .card-slide-nav:hover {
            opacity: 1;
            background: #2563eb;
            border-color: #2563eb;
            transform: translateY(-50%) scale(1.1);
        }

        .card-slide-prev { left: 10px; }
        .card-slide-next { right: 10px; }

        .card-slide-dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 6px;
        }

        .card-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.45);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .card-dot.active {
            background: #3b82f6;
            width: 18px;
            border-radius: 10px;
        }

        .activity-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .activity-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
            line-height: 1.35;
        }

        .activity-meta {
            font-size: 0.85rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .activity-desc {
            font-size: 0.92rem;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .activity-footer {
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
        }

        /* ── 5. Testimonial Section ── */
        .testimonial-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            height: 100%;
        }

        .quote-icon {
            font-size: 2rem;
            color: #fca5a5;
            margin-bottom: 15px;
        }

        .testimonial-text {
            font-size: 0.98rem;
            color: #334155;
            line-height: 1.7;
            font-style: italic;
            margin-bottom: 20px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .author-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .author-name {
            font-weight: 800;
            font-size: 0.95rem;
            color: #0f172a;
            margin: 0;
        }

        .author-role {
            font-size: 0.82rem;
            color: #64748b;
            margin: 0;
        }

        /* ── 6. CTA Section ── */
        .outreach-cta {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            padding: 60px 0;
            border-radius: 30px;
            text-align: center;
            margin-bottom: 70px;
            box-shadow: 0 20px 50px rgba(239, 68, 68, 0.3);
        }

        .cta-btn {
            background: #ffffff;
            color: #dc2626;
            padding: 13px 36px;
            border-radius: 50px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .cta-btn:hover {
            background: #0f172a;
            color: #ffffff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <?php include 'nav.php'; ?>

    <!-- 1. Hero Section -->
    <section class="outreach-hero text-center">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <span class="hero-badge-pill">
                        <i class="fas fa-hands-helping me-1"></i> SOCIETAL IMPACT & OUTREACH
                    </span>
                    <h1 class="hero-title">
                        Extending Tech Beyond <span>Boundaries</span>
                    </h1>
                    <p class="hero-subtitle">
                        Empowering school children with digital literacy, bridging academia with premier industrial visits, and driving technology adoption in rural communities through SRKR CSD & CSIT.
                    </p>
                </div>
            </div>
        </div>
    </section>



    <!-- 3. Outreach Activities Grid with Interactive Filter -->
    <section style="padding: 60px 0; background: #ffffff;">
        <div class="container">
            <div class="section-header-custom">
                <span class="section-tag">ACTUAL INITIATIVES</span>
                <h2>Recent Outreach Activities & Visits</h2>
                <div class="accent-bar"></div>
                <p>Highlights of our recent school workshops, industrial trips, and community drives.</p>
            </div>



            <!-- Grid Cards -->
            <div class="row g-4 justify-content-center" id="activitiesGrid">
                <!-- Block 1: Industrial Visit Card Slideshow -->
                <div class="col-md-6 activity-item" data-cat="industrial">
                    <div class="activity-card-custom">
                        <!-- Card Slideshow Viewport -->
                        <div class="card-slideshow-viewport" id="industrialCardSlideshow">
                            <img src="images/outreach/industrial_visit_01.jpg" alt="Industrial Visit Photo" class="card-slide-img" id="indCardSlideImg">
                            
                            <span class="activity-category-badge" style="background: rgba(37, 99, 235, 0.95); z-index: 10;">Industrial Visit</span>
                            <span id="indCardCounter" style="position: absolute; top: 15px; right: 15px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 4px 12px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); z-index: 10;">Photo 1 of 5</span>
                            
                            <button class="card-slide-nav card-slide-prev" onclick="prevIndCardSlide(event)" title="Previous Photo">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="card-slide-nav card-slide-next" onclick="nextIndCardSlide(event)" title="Next Photo">
                                <i class="fas fa-chevron-right"></i>
                            </button>

                            <div class="card-slide-dots" id="indCardDots"></div>
                        </div>
                        <div class="activity-body">
                            <div>
                                <div class="activity-meta">
                                    <span><i class="far fa-calendar-alt text-primary me-1"></i> T-Hub, Hyderabad</span>
                                    <span><i class="fas fa-users text-primary me-1"></i> CSD Dept</span>
                                </div>
                                <h4 class="activity-title">T-Hub & T-Works Hyderabad Industrial Visit</h4>
                                <p class="activity-desc">Computer Science & Design (CSD) department students visited T-Hub Innovation Campus and T-Works in Hyderabad for real-world corporate learning, prototyping labs, and tech talks.</p>
                            </div>
                            <div class="activity-footer">
                                <span><i class="fas fa-images me-1 text-primary"></i> 5 Photos Slideshow</span>
                                <span class="badge bg-primary">Industrial</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Block 2: School Program Card Slideshow -->
                <div class="col-md-6 activity-item" data-cat="school">
                    <div class="activity-card-custom">
                        <!-- Card Slideshow Viewport -->
                        <div class="card-slideshow-viewport" id="schoolCardSlideshow">
                            <img src="images/outreach/school_program_01.jpg" alt="School Program Photo" class="card-slide-img" id="schoolCardSlideImg">
                            
                            <span class="activity-category-badge" style="background: rgba(239, 68, 68, 0.95); z-index: 10;">School Program</span>
                            <span id="schoolCardCounter" style="position: absolute; top: 15px; right: 15px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 4px 12px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); z-index: 10;">Photo 1 of 5</span>
                            
                            <button class="card-slide-nav card-slide-prev" onclick="prevSchoolCardSlide(event)" title="Previous Photo">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="card-slide-nav card-slide-next" onclick="nextSchoolCardSlide(event)" title="Next Photo">
                                <i class="fas fa-chevron-right"></i>
                            </button>

                            <div class="card-slide-dots" id="schoolCardDots"></div>
                        </div>
                        <div class="activity-body">
                            <div>
                                <div class="activity-meta">
                                    <span><i class="far fa-calendar-alt text-danger me-1"></i> AICTE IDEA Lab</span>
                                    <span><i class="fas fa-school text-danger me-1"></i> School Outreach</span>
                                </div>
                                <h4 class="activity-title">School Children AICTE IDEA Lab & STEM Literacy Visit</h4>
                                <p class="activity-desc">School students visited SRKR AICTE IDEA Lab for an immersive STEM workshop covering 3D printing, CNC laser processing, robotics, and hands-on computer engineering concepts.</p>
                            </div>
                            <div class="activity-footer">
                                <span><i class="fas fa-images me-1 text-danger"></i> 5 Photos Slideshow</span>
                                <span class="badge bg-danger">School Drive</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <?php include 'footer.php'; ?>

    <script>
        function filterActivities(category, btn) {
            document.querySelectorAll('.outreach-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const items = document.querySelectorAll('.activity-item');
            items.forEach(item => {
                if (category === 'all' || item.getAttribute('data-cat') === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Industrial Visit Card Slideshow Logic
        const indPhotos = [
            "images/outreach/industrial_visit_01.jpg",
            "images/outreach/industrial_visit_02.jpg",
            "images/outreach/industrial_visit_03.jpg",
            "images/outreach/industrial_visit_04.jpg",
            "images/outreach/industrial_visit_05.jpg"
        ];
        let currentIndIndex = 0;
        let indAutoplayTimer = null;

        function updateIndCardSlide() {
            const mainImg = document.getElementById("indCardSlideImg");
            const bgBlur = document.getElementById("indCardBgBlur");
            const counter = document.getElementById("indCardCounter");

            if (mainImg) {
                mainImg.style.opacity = "0.2";
                if (bgBlur) bgBlur.style.opacity = "0.2";
                setTimeout(() => {
                    mainImg.src = indPhotos[currentIndIndex];
                    if (bgBlur) bgBlur.src = indPhotos[currentIndIndex];
                    mainImg.style.opacity = "1";
                    if (bgBlur) bgBlur.style.opacity = "0.9";
                }, 150);
            }
            if (counter) {
                counter.innerText = `Photo ${currentIndIndex + 1} of ${indPhotos.length}`;
            }

            const dotsContainer = document.getElementById("indCardDots");
            if (dotsContainer) {
                dotsContainer.querySelectorAll(".card-dot").forEach((dot, idx) => {
                    if (idx === currentIndIndex) dot.classList.add("active");
                    else dot.classList.remove("active");
                });
            }
        }

        function nextIndCardSlide(e) {
            if (e) e.stopPropagation();
            currentIndIndex = (currentIndIndex + 1) % indPhotos.length;
            updateIndCardSlide();
            resetIndAutoplay();
        }

        function prevIndCardSlide(e) {
            if (e) e.stopPropagation();
            currentIndIndex = (currentIndIndex - 1 + indPhotos.length) % indPhotos.length;
            updateIndCardSlide();
            resetIndAutoplay();
        }

        function startIndAutoplay() {
            if (indAutoplayTimer) clearInterval(indAutoplayTimer);
            indAutoplayTimer = setInterval(() => {
                currentIndIndex = (currentIndIndex + 1) % indPhotos.length;
                updateIndCardSlide();
            }, 3000);
        }

        function resetIndAutoplay() {
            startIndAutoplay();
        }

        function initIndCardSlideshow() {
            const dotsContainer = document.getElementById("indCardDots");
            if (dotsContainer) {
                dotsContainer.innerHTML = "";
                indPhotos.forEach((_, idx) => {
                    const dot = document.createElement("span");
                    dot.className = `card-dot ${idx === 0 ? 'active' : ''}`;
                    dot.onclick = (e) => {
                        e.stopPropagation();
                        currentIndIndex = idx;
                        updateIndCardSlide();
                        resetIndAutoplay();
                    };
                    dotsContainer.appendChild(dot);
                });
            }
            startIndAutoplay();
        }

        document.addEventListener("DOMContentLoaded", function() {
            initIndCardSlideshow();
            initSchoolCardSlideshow();
        });

        // School Program Card Slideshow Logic
        const schoolPhotos = [
            "images/outreach/school_program_01.jpg",
            "images/outreach/school_program_02.jpg",
            "images/outreach/school_program_03.jpg",
            "images/outreach/school_program_04.jpg",
            "images/outreach/school_program_05.jpg"
        ];
        let currentSchoolIndex = 0;
        let schoolAutoplayTimer = null;

        function updateSchoolCardSlide() {
            const mainImg = document.getElementById("schoolCardSlideImg");
            const counter = document.getElementById("schoolCardCounter");

            if (mainImg) {
                mainImg.style.opacity = "0.2";
                setTimeout(() => {
                    mainImg.src = schoolPhotos[currentSchoolIndex];
                    mainImg.style.opacity = "1";
                }, 150);
            }
            if (counter) {
                counter.innerText = `Photo ${currentSchoolIndex + 1} of ${schoolPhotos.length}`;
            }

            const dotsContainer = document.getElementById("schoolCardDots");
            if (dotsContainer) {
                dotsContainer.querySelectorAll(".card-dot").forEach((dot, idx) => {
                    if (idx === currentSchoolIndex) dot.classList.add("active");
                    else dot.classList.remove("active");
                });
            }
        }

        function nextSchoolCardSlide(e) {
            if (e) e.stopPropagation();
            currentSchoolIndex = (currentSchoolIndex + 1) % schoolPhotos.length;
            updateSchoolCardSlide();
            resetSchoolAutoplay();
        }

        function prevSchoolCardSlide(e) {
            if (e) e.stopPropagation();
            currentSchoolIndex = (currentSchoolIndex - 1 + schoolPhotos.length) % schoolPhotos.length;
            updateSchoolCardSlide();
            resetSchoolAutoplay();
        }

        function startSchoolAutoplay() {
            if (schoolAutoplayTimer) clearInterval(schoolAutoplayTimer);
            schoolAutoplayTimer = setInterval(() => {
                currentSchoolIndex = (currentSchoolIndex + 1) % schoolPhotos.length;
                updateSchoolCardSlide();
            }, 3200);
        }

        function resetSchoolAutoplay() {
            startSchoolAutoplay();
        }

        function initSchoolCardSlideshow() {
            const dotsContainer = document.getElementById("schoolCardDots");
            if (dotsContainer) {
                dotsContainer.innerHTML = "";
                schoolPhotos.forEach((_, idx) => {
                    const dot = document.createElement("span");
                    dot.className = `card-dot ${idx === 0 ? 'active' : ''}`;
                    dot.onclick = (e) => {
                        e.stopPropagation();
                        currentSchoolIndex = idx;
                        updateSchoolCardSlide();
                        resetSchoolAutoplay();
                    };
                    dotsContainer.appendChild(dot);
                });
            }
            startSchoolAutoplay();
        }
    </script>
</body>
</html>
