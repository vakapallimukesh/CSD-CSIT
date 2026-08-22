<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Alumni - SRKR Engineering College (CSD & CSIT)</title>
    <meta name="description" content="Official alumni portal of CSD & CSIT departments at SRKR Engineering College. Discover our graduates, placements, careers, and achievements.">
    <link rel="icon" href="logo-bg.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Premium Google Fonts (Matching Faculty & Main Website) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Unified Website UI/UX Design System -->
    <style>
        :root {
            /* Fonts matching index.php & faculty.php */
            --font-display: 'Outfit', sans-serif;
            --font-heading: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Inter', sans-serif;

            /* Yellowish Amber & Golden Palette (Website Standard) */
            --amber-gold: #d97706;
            --bright-yellow: #f59e0b;
            --golden-champagne: #e6c280;
            --amber-badge: #b45309;
            --warm-brown: #78350f;
            --rich-espresso: #1a0d06;
            --cream-white: #fdfbf7;

            --card-bg: #ffffff;
            --text-dark: #1a0d06;
            --text-muted: #6f5f54;
            --border-light: #f3eae1;

            --shadow-subtle: 0 12px 35px rgba(180, 83, 9, 0.07);
            --shadow-hover: 0 24px 55px rgba(180, 83, 9, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--cream-white);
            color: var(--text-dark);
            line-height: 1.65;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            color: var(--text-dark);
        }

        /* ── Standardized Section Header ── */
        .site-section-header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .site-section-header .sub-tag {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--amber-badge);
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 6px 20px;
            border-radius: 999px;
            display: inline-block;
            margin-bottom: 12px;
        }

        .site-section-header h2 {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--text-dark);
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .gold-divider-line {
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, var(--bright-yellow) 0%, var(--amber-gold) 100%);
            margin: 0 auto 16px auto;
            border-radius: 4px;
        }

        .site-section-header p {
            color: var(--text-muted);
            font-size: 1.05rem;
            max-width: 640px;
            margin: 0 auto;
            line-height: 1.65;
        }

        /* ── 1. Hero Banner (Warm Espresso & Amber Gold) ── */
        .alumni-hero-banner {
            background: linear-gradient(135deg, #1a0d06 0%, #361a0c 50%, #522710 100%);
            color: #ffffff;
            padding: 95px 20px 85px;
            position: relative;
            overflow: hidden;
        }

        .alumni-hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(230, 194, 128, 0.15) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.45;
        }

        .hero-container {
            max-width: 1240px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(212, 155, 89, 0.18);
            border: 1px solid rgba(230, 194, 128, 0.4);
            color: var(--golden-champagne);
            padding: 8px 22px;
            border-radius: 50px;
            font-family: var(--font-display);
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: clamp(2.6rem, 5.5vw, 3.8rem);
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #f5ebe6 35%, #e6c280 70%, #d49b59 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.12rem;
            color: #e5d5c5;
            max-width: 560px;
            margin-bottom: 35px;
            line-height: 1.7;
        }

        .hero-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff !important;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 14px 34px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(217, 119, 6, 0.4);
            transition: all 0.3s ease;
        }

        .hero-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(217, 119, 6, 0.5);
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }

        .hero-photo-frame {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(230, 194, 128, 0.3);
            border-radius: 28px;
            padding: 12px;
            backdrop-filter: blur(12px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
        }

        .hero-photo-frame img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            border-radius: 20px;
        }

        /* ── 2. Statistics Bar ── */
        .stats-wrapper {
            max-width: 1240px;
            margin: -40px auto 70px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .stat-card-box {
            background: #ffffff;
            border-radius: 24px;
            padding: 24px 28px;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-subtle);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .stat-card-box:hover {
            transform: translateY(-5px);
            border-color: var(--amber-gold);
            box-shadow: var(--shadow-hover);
        }

        .stat-icon-circle {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: #fffbeb;
            color: var(--amber-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
            border: 1px solid #fde68a;
        }

        .stat-number {
            font-family: var(--font-display);
            font-size: 1.9rem;
            font-weight: 900;
            color: var(--text-dark);
            line-height: 1.1;
        }

        .stat-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── 3. Our Batches Section ── */
        .batches-section {
            padding: 20px 0 80px 0;
        }

        .batch-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-subtle);
            overflow: hidden;
            transition: all 0.35s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .batch-card:hover {
            transform: translateY(-8px);
            border-color: var(--amber-gold);
            box-shadow: var(--shadow-hover);
        }

        .batch-img-container {
            position: relative;
            height: 240px;
            overflow: hidden;
            background: var(--rich-espresso);
        }

        .batch-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .batch-card:hover .batch-img-container img {
            transform: scale(1.05);
        }

        .batch-count-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: linear-gradient(135deg, var(--bright-yellow) 0%, var(--amber-gold) 100%);
            color: #ffffff;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 0.82rem;
            padding: 6px 18px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.4);
        }

        .batch-body {
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .batch-year-title {
            font-family: var(--font-display);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .batch-subtitle {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--amber-badge);
            margin-bottom: 14px;
        }

        .batch-desc {
            font-size: 0.92rem;
            color: var(--text-muted);
            line-height: 1.65;
            margin-bottom: 24px;
            flex-grow: 1;
        }

        .batch-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: #fffbeb;
            color: var(--warm-brown);
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 0.92rem;
            padding: 12px;
            border-radius: 50px;
            border: 1px solid #fde68a;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .batch-btn:hover {
            background: linear-gradient(135deg, var(--bright-yellow) 0%, var(--amber-gold) 100%);
            color: #ffffff;
            border-color: var(--amber-gold);
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.3);
        }

        /* ── 4. Separate Placements Section ── */
        .placements-section {
            background: #ffffff;
            padding: 85px 0;
            border-top: 1px solid var(--border-light);
            border-bottom: 1px solid var(--border-light);
        }

        .placement-card {
            background: var(--cream-white);
            border-radius: 24px;
            border: 1px solid var(--border-light);
            box-shadow: 0 8px 25px rgba(180, 83, 9, 0.05);
            padding: 24px 20px;
            text-align: center;
            transition: all 0.35s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .placement-card:hover {
            transform: translateY(-6px);
            border-color: var(--amber-gold);
            box-shadow: var(--shadow-hover);
            background: #ffffff;
        }

        .placement-avatar-box {
            position: relative;
            width: 96px;
            height: 96px;
            margin-bottom: 16px;
        }

        .placement-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ffffff;
            box-shadow: 0 6px 20px rgba(180, 83, 9, 0.15);
        }

        .placement-package-badge {
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--bright-yellow) 0%, var(--amber-gold) 100%);
            color: #ffffff;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 0.72rem;
            padding: 3px 12px;
            border-radius: 50px;
            box-shadow: 0 3px 10px rgba(217, 119, 6, 0.3);
            white-space: nowrap;
        }

        .placement-student-name {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-top: 6px;
            margin-bottom: 4px;
        }

        .placement-role {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--amber-badge);
            margin-bottom: 8px;
        }

        .placement-company-tag {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
            background: #fffbeb;
            padding: 4px 14px;
            border-radius: 50px;
            border: 1px solid #fde68a;
        }

        /* ── 5. Achievements Section ── */
        .achievements-section {
            padding: 85px 0;
        }

        .achievement-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid var(--border-light);
            border-left: 5px solid var(--amber-gold);
            box-shadow: var(--shadow-subtle);
            padding: 28px 24px;
            transition: all 0.35s ease;
            height: 100%;
        }

        .achievement-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
        }

        .achievement-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #fffbeb;
            color: var(--amber-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 16px;
            border: 1px solid #fde68a;
        }

        .achievement-title {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .achievement-desc {
            font-size: 0.92rem;
            color: var(--text-muted);
            line-height: 1.65;
            margin: 0;
        }

        /* ── 6. Fullscreen Batch Directory Modal ── */
        .site-modal-content {
            background: #ffffff;
            border-radius: 28px;
            border: 1px solid var(--border-light);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .site-modal-header {
            background: linear-gradient(135deg, #1a0d06 0%, #361a0c 100%);
            color: #ffffff;
            padding: 24px 30px;
            border-bottom: 1px solid rgba(230, 194, 128, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .site-modal-header h4 {
            font-family: var(--font-display);
            color: #ffffff;
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
        }

        .site-modal-header p {
            color: #e5d5c5;
            font-size: 0.88rem;
            margin: 0;
        }

        .site-search-bar-wrapper {
            background: var(--cream-white);
            padding: 18px 30px;
            border-bottom: 1px solid var(--border-light);
        }

        .site-search-input {
            width: 100%;
            background: #ffffff;
            border: 2px solid var(--border-light);
            border-radius: 50px;
            padding: 12px 20px 12px 48px;
            font-size: 0.95rem;
            color: var(--text-dark);
            outline: none;
            transition: all 0.3s ease;
        }

        .site-search-input:focus {
            border-color: var(--amber-gold);
            box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.12);
        }

        .site-search-icon {
            position: absolute;
            left: 48px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--amber-gold);
            font-size: 1.1rem;
        }

        .site-modal-body {
            padding: 30px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .directory-student-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-light);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .directory-student-card:hover {
            border-color: var(--amber-gold);
            box-shadow: 0 8px 20px rgba(180, 83, 9, 0.1);
        }

        .directory-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid #fde68a;
        }

        .directory-name {
            font-family: var(--font-display);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .directory-reg {
            font-size: 0.8rem;
            font-weight: 700;
            font-family: monospace;
            color: var(--warm-brown);
            background: #fffbeb;
            padding: 2px 8px;
            border-radius: 6px;
            border: 1px solid #fde68a;
            display: inline-block;
        }

        /* ── 7. Final CTA Section ── */
        .site-cta-banner {
            background: linear-gradient(135deg, #1a0d06 0%, #361a0c 50%, #522710 100%);
            color: #ffffff;
            padding: 85px 20px;
            text-align: center;
            position: relative;
        }

        .cta-title {
            font-family: var(--font-display);
            font-size: clamp(2.2rem, 4.5vw, 3rem);
            font-weight: 900;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #ffffff 0%, #f5ebe6 35%, #e6c280 70%, #d49b59 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cta-desc {
            color: #e5d5c5;
            font-size: 1.08rem;
            max-width: 600px;
            margin: 0 auto 32px auto;
            line-height: 1.65;
        }
    </style>
</head>
<body>

    <?php include 'nav.php'; ?>

    <!-- 1. Warm Espresso & Amber Gold Hero Banner -->
    <section class="alumni-hero-banner">
        <div class="hero-container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-lg-start text-center">
                    <div class="hero-badge">
                        <i class="fa-solid fa-graduation-cap me-1"></i> SRKR CSD & CSIT ALUMNI PORTAL
                    </div>
                    <h1 class="hero-title">OUR ALUMNI</h1>
                    <p class="hero-subtitle">Celebrating the journey, achievements, and success of our graduates. Explore our alumni community, their placement achievements, and the inspiring careers they continue to build beyond graduation.</p>
                    <a href="#batches" class="hero-action-btn">
                        Explore Alumni Batches <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="hero-photo-frame">
                        <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=900&auto=format&fit=crop&q=80" alt="Batch 2025 Group Photo">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Statistics Bar -->
    <div class="stats-wrapper">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-card-box">
                    <div class="stat-icon-circle"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <div class="stat-number">136+</div>
                        <div class="stat-label">Official Alumni</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-box">
                    <div class="stat-icon-circle"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <div class="stat-number">2</div>
                        <div class="stat-label">Batches (2024–2025)</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-box">
                    <div class="stat-icon-circle"><i class="fa-solid fa-briefcase"></i></div>
                    <div>
                        <div class="stat-number">29</div>
                        <div class="stat-label">Placements</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-box">
                    <div class="stat-icon-circle"><i class="fa-solid fa-award"></i></div>
                    <div>
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Placement Record</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Our Batches Section -->
    <section class="batches-section" id="batches">
        <div class="container">
            <div class="site-section-header">
                <span class="sub-tag">GRADUATING CLASSES</span>
                <h2>OUR BATCHES</h2>
                <div class="gold-divider-line"></div>
                <p>Browse through our graduating classes. Click on any batch card to search and view complete student class rosters.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Batch 2024 Card -->
                <div class="col-lg-5 col-md-6">
                    <div class="batch-card">
                        <div class="batch-img-container">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=900&auto=format&fit=crop&q=80" alt="Batch 2024 Class">
                            <span class="batch-count-badge">69 Alumni Members</span>
                        </div>
                        <div class="batch-body">
                            <div class="batch-year-title">Batch 2024 (III/IV B.Tech CSG)</div>
                            <div class="batch-subtitle">Computer Science & Design</div>
                            <p class="batch-desc">The pioneer graduating class of Computer Science & Design. Roll numbers ranging from 21B91A6201 to 22B95A6207.</p>
                            <button class="batch-btn" onclick="openBatchModal('2024')">
                                Explore Batch Directory <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Batch 2025 Card -->
                <div class="col-lg-5 col-md-6">
                    <div class="batch-card">
                        <div class="batch-img-container">
                            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=900&auto=format&fit=crop&q=80" alt="Batch 2025 Class">
                            <span class="batch-count-badge">67 Alumni Members</span>
                        </div>
                        <div class="batch-body">
                            <div class="batch-year-title">Batch 2025 (IV/IV B.Tech CSD)</div>
                            <div class="batch-subtitle">Computer Science & Design</div>
                            <p class="batch-desc">The graduating class of 2025. Roll numbers ranging from 22B91A6201 to 23B95A6208, driving technological innovation.</p>
                            <button class="batch-btn" onclick="openBatchModal('2025')">
                                Explore Batch Directory <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Separate Placements Section -->
    <section class="placements-section" id="placements">
        <div class="container">
            <div class="site-section-header">
                <span class="sub-tag">CAREER SUCCESS</span>
                <h2>ALUMNI PLACEMENTS</h2>
                <div class="gold-divider-line"></div>
                <p>Celebrating the official placement selections of our students across top global companies and tech innovators.</p>
            </div>

            <div class="row g-4" id="placementsGrid">
                <!-- Loaded dynamically via JavaScript API -->
            </div>
        </div>
    </section>

    <!-- 5. Achievements Section -->
    <section class="achievements-section">
        <div class="container">
            <div class="site-section-header">
                <span class="sub-tag">DISTINCTIONS</span>
                <h2>ALUMNI ACHIEVEMENTS</h2>
                <div class="gold-divider-line"></div>
                <p>Recognizing outstanding accomplishments, industry honors, and research contributions made by our alumni.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-lg-6">
                    <div class="achievement-card">
                        <div class="achievement-icon"><i class="fa-solid fa-trophy"></i></div>
                        <h3 class="achievement-title">Industry Excellence Awards</h3>
                        <p class="achievement-desc">Our alumni have been recognized with top performer and leadership awards across leading MNCs, tech startups, and research labs worldwide.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="achievement-card">
                        <div class="achievement-icon"><i class="fa-solid fa-lightbulb"></i></div>
                        <h3 class="achievement-title">Research & Innovation Patents</h3>
                        <p class="achievement-desc">Multiple graduates have published research papers in IEEE & Springer conferences and authored innovation patents in AI and Human-Computer Interaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Final CTA Section -->
    <section class="site-cta-banner">
        <div class="container">
            <h2 class="cta-title">THE JOURNEY CONTINUES</h2>
            <p class="cta-desc">Stay connected with the SRKR CSD & CSIT Alumni Community. Explore our student directories, achievements, and career milestones.</p>
            <a href="#batches" class="hero-action-btn">
                Explore Our Alumni <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- 7. Interactive Fullscreen Batch Directory Modal -->
    <div class="modal fade" id="batchDirectoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content site-modal-content">
                <div class="site-modal-header">
                    <div>
                        <h4 id="modalBatchTitle">Batch Directory</h4>
                        <p id="modalBatchSubtitle">Computer Science & Design Class List</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="site-search-bar-wrapper position-relative">
                    <i class="fa-solid fa-magnifying-glass site-search-icon"></i>
                    <input type="text" id="modalSearchInput" class="site-search-input" placeholder="Search student by Name or Registration Number (e.g. 21B91A6201, VAKAPALLI)...">
                </div>
                <div class="site-modal-body">
                    <div class="row g-3" id="modalStudentGrid">
                        <!-- Student directory cards loaded via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- JavaScript & API Handler -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allAlumniData = { 2024: [], 2025: [] };
        let activeBatchKey = '2024';

        document.addEventListener('DOMContentLoaded', function() {
            loadAlumniPortalData();

            // Search Filter inside Modal
            document.getElementById('modalSearchInput').addEventListener('input', function(e) {
                filterModalStudents(e.target.value.trim().toLowerCase());
            });
        });

        function loadAlumniPortalData() {
            fetch('get_alumni.php')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        if (data.batches) {
                            allAlumniData['2024'] = data.batches['2024'] ? data.batches['2024'].alumni : [];
                            allAlumniData['2025'] = data.batches['2025'] ? data.batches['2025'].alumni : [];
                        }
                        if (data.placements) {
                            renderPlacements(data.placements);
                        }
                    }
                })
                .catch(err => console.error('Error fetching alumni data:', err));
        }

        function renderPlacements(placements) {
            const grid = document.getElementById('placementsGrid');
            grid.innerHTML = '';

            placements.forEach(item => {
                const col = document.createElement('div');
                col.className = 'col-lg-3 col-md-4 col-sm-6';
                col.innerHTML = `
                    <div class="placement-card">
                        <div class="placement-avatar-box">
                            <img src="${item.photo}" alt="${item.student_name}" class="placement-avatar" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(item.student_name)}&background=d97706&color=ffffff'">
                            <span class="placement-package-badge">${item.package}</span>
                        </div>
                        <div class="placement-student-name">${item.student_name}</div>
                        <div class="placement-role">${item.job_role}</div>
                        <div class="placement-company-tag"><i class="fa-solid fa-building me-1 text-warning"></i> ${item.company}</div>
                    </div>
                `;
                grid.appendChild(col);
            });
        }

        function openBatchModal(batchKey) {
            activeBatchKey = batchKey;
            const titleEl = document.getElementById('modalBatchTitle');
            const subTitleEl = document.getElementById('modalBatchSubtitle');
            const searchInput = document.getElementById('modalSearchInput');

            searchInput.value = '';
            titleEl.innerText = `Batch ${batchKey} Class Directory`;
            subTitleEl.innerText = batchKey === '2024' ? 'III/IV B.Tech CSG (69 Students)' : 'IV/IV B.Tech CSD (67 Students)';

            renderModalStudents(allAlumniData[batchKey] || []);
            const modal = new bootstrap.Modal(document.getElementById('batchDirectoryModal'));
            modal.show();
        }

        function renderModalStudents(list) {
            const grid = document.getElementById('modalStudentGrid');
            grid.innerHTML = '';

            if (list.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5 text-muted fw-bold">No students found matching your search query.</div>';
                return;
            }

            list.forEach(student => {
                const col = document.createElement('div');
                col.className = 'col-lg-4 col-md-6';
                col.innerHTML = `
                    <div class="directory-student-card">
                        <img src="${student.photo}" alt="${student.name}" class="directory-avatar" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&background=d97706&color=ffffff'">
                        <div>
                            <div class="directory-name">${student.name}</div>
                            <span class="directory-reg">${student.registration_number}</span>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });
        }

        function filterModalStudents(query) {
            const list = allAlumniData[activeBatchKey] || [];
            if (!query) {
                renderModalStudents(list);
                return;
            }
            const filtered = list.filter(item => {
                const haystack = `${item.name} ${item.registration_number}`.toLowerCase();
                return haystack.includes(query);
            });
            renderModalStudents(filtered);
        }
    </script>
</body>
</html>
