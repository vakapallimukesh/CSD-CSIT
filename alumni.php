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
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scoped Light Theme Styles strictly for Alumni Page -->
    <style>
        .alumni-page-scope {
            --alumni-bg-main: #F8F7F3;
            --alumni-bg-soft: #F3F7FC;
            --alumni-card-bg: #FFFFFF;
            --alumni-primary: #315C8C;
            --alumni-navy: #172B4D;
            --alumni-gold: #C9A45C;
            --alumni-body: #5F6B7A;
            --alumni-border: #E5EAF0;

            font-family: 'Inter', sans-serif;
            background-color: var(--alumni-bg-main);
            color: var(--alumni-body);
            overflow-x: hidden;
        }

        .alumni-page-scope h1, 
        .alumni-page-scope h2, 
        .alumni-page-scope h3, 
        .alumni-page-scope h4, 
        .alumni-page-scope h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--alumni-navy);
        }

        /* ── Standardized Section Header ── */
        .alumni-section-header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .alumni-section-header .sub-tag {
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--alumni-primary);
            margin-bottom: 8px;
            display: block;
        }

        .alumni-section-header h2 {
            font-size: 2.3rem;
            font-weight: 800;
            color: var(--alumni-navy);
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .gold-accent-line {
            width: 60px;
            height: 3.5px;
            background: var(--alumni-gold);
            margin: 0 auto 16px auto;
            border-radius: 4px;
        }

        .alumni-section-header p {
            color: var(--alumni-body);
            font-size: 1.05rem;
            max-width: 620px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* ── 2. Hero Section ── */
        .alumni-hero-section {
            background: linear-gradient(135deg, #F8F7F3 0%, #EEF5FC 100%);
            padding: 90px 0 70px 0;
            border-bottom: 1px solid var(--alumni-border);
            position: relative;
        }

        .hero-tag-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(49, 92, 140, 0.08);
            border: 1px solid rgba(49, 92, 140, 0.18);
            color: var(--alumni-primary);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .hero-heading {
            font-size: 3.2rem;
            font-weight: 800;
            color: var(--alumni-navy);
            line-height: 1.2;
            margin-bottom: 18px;
            letter-spacing: -1px;
            opacity: 0;
            transform: translateY(20px);
            animation: lightFadeUp 0.8s ease forwards 0.1s;
        }

        .hero-heading span {
            color: var(--alumni-gold);
        }

        .hero-desc {
            font-size: 1.15rem;
            color: var(--alumni-body);
            line-height: 1.75;
            margin-bottom: 30px;
            max-width: 640px;
            opacity: 0;
            transform: translateY(20px);
            animation: lightFadeUp 0.8s ease forwards 0.3s;
        }

        .hero-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--alumni-primary);
            color: #FFFFFF;
            padding: 13px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.96rem;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(49, 92, 140, 0.25);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            animation: lightFadeUp 0.8s ease forwards 0.5s;
        }

        .hero-action-btn:hover {
            background: var(--alumni-navy);
            color: #FFFFFF;
            box-shadow: 0 15px 35px rgba(23, 43, 77, 0.3);
            transform: translateY(-2px);
        }

        .hero-img-box {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(23, 43, 77, 0.12);
            border: 4px solid #FFFFFF;
            opacity: 0;
            transform: scale(0.96);
            animation: lightScaleIn 0.9s ease forwards 0.4s;
        }

        .hero-img-box img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            display: block;
        }

        @keyframes lightFadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes lightScaleIn {
            to { opacity: 1; transform: scale(1); }
        }

        /* ── 3. Alumni Statistics Cards ── */
        .stats-cards-wrapper {
            margin-top: 50px;
        }

        .stat-card-white {
            background: #FFFFFF;
            border: 1px solid var(--alumni-border);
            border-radius: 20px;
            padding: 26px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(23, 43, 77, 0.04);
            transition: transform 0.3s ease;
        }

        .stat-card-white:hover {
            transform: translateY(-4px);
        }

        .stat-num-navy {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.3rem;
            font-weight: 800;
            color: var(--alumni-navy);
            line-height: 1.1;
        }

        .stat-num-navy span {
            color: var(--alumni-gold);
        }

        .stat-lbl-gray {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--alumni-body);
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* ── 4. Our Batches Section & Group Photo Cards ── */
        .batches-light-section {
            background-color: var(--alumni-bg-main);
            padding: 90px 0;
        }

        .batch-photo-card {
            background: #FFFFFF;
            border: 1px solid var(--alumni-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(23, 43, 77, 0.06);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            cursor: pointer;
            position: relative;
        }

        .batch-photo-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 45px rgba(23, 43, 77, 0.12);
            border-color: var(--alumni-gold);
        }

        .batch-img-wrapper {
            position: relative;
            height: 260px;
            overflow: hidden;
        }

        .batch-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .batch-photo-card:hover .batch-img-wrapper img {
            transform: scale(1.05);
        }

        .batch-card-body {
            padding: 28px;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .batch-tag-name {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--alumni-gold);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .batch-card-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--alumni-navy);
            margin: 0;
        }

        .batch-count-pill {
            background: var(--alumni-bg-soft);
            color: var(--alumni-primary);
            font-weight: 700;
            font-size: 0.88rem;
            padding: 6px 14px;
            border-radius: 30px;
            margin-top: 6px;
            display: inline-block;
        }

        .explore-batch-link {
            font-weight: 700;
            font-size: 0.94rem;
            color: var(--alumni-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.3s ease, color 0.3s ease;
        }

        .batch-photo-card:hover .explore-batch-link {
            color: var(--alumni-gold);
            gap: 12px;
        }

        /* ── 5. Directory Modal & Profile Grid Cards ── */
        .light-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(23, 43, 77, 0.6);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .light-modal.active {
            opacity: 1;
            pointer-events: auto;
        }

        .light-modal-dialog {
            background: #FFFFFF;
            border: 1px solid var(--alumni-border);
            border-radius: 24px;
            width: 100%;
            max-width: 1100px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 60px rgba(23, 43, 77, 0.2);
            transform: scale(0.95);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .light-modal.active .light-modal-dialog {
            transform: scale(1);
        }

        .light-modal-header {
            padding: 24px 32px;
            background: var(--alumni-bg-soft);
            border-bottom: 1px solid var(--alumni-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .light-modal-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--alumni-navy);
            margin: 0;
        }

        .light-modal-close {
            background: #FFFFFF;
            border: 1px solid var(--alumni-border);
            color: var(--alumni-body);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .light-modal-close:hover {
            background: #Fee2e2;
            color: #ef4444;
            border-color: #fca5a5;
        }

        .light-search-container {
            padding: 18px 32px;
            background: #FFFFFF;
            border-bottom: 1px solid var(--alumni-border);
        }

        .light-search-box {
            position: relative;
        }

        .light-search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .light-search-input {
            width: 100%;
            background: var(--alumni-bg-main);
            border: 1px solid var(--alumni-border);
            padding: 12px 20px 12px 48px;
            border-radius: 12px;
            color: var(--alumni-navy);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .light-search-input:focus {
            border-color: var(--alumni-primary);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(49, 92, 140, 0.12);
        }

        .light-modal-body {
            padding: 30px 32px;
            overflow-y: auto;
            flex: 1;
            background: var(--alumni-bg-main);
        }

        /* Profile Cards Grid */
        .profile-card-item {
            background: #FFFFFF;
            border: 1px solid var(--alumni-border);
            border-radius: 18px;
            padding: 22px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(23, 43, 77, 0.04);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .profile-card-item:hover {
            transform: translateY(-4px);
            border-color: var(--alumni-gold);
            box-shadow: 0 12px 30px rgba(23, 43, 77, 0.09);
        }

        .profile-card-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 14px auto;
            border: 2px solid var(--alumni-gold);
        }

        .profile-card-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--alumni-navy);
            margin-bottom: 4px;
        }

        .profile-card-reg {
            font-family: monospace;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--alumni-primary);
            background: var(--alumni-bg-soft);
            padding: 2px 10px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 12px;
        }

        .profile-card-view-btn {
            background: transparent;
            border: 1px solid var(--alumni-border);
            color: var(--alumni-primary);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 18px;
            border-radius: 30px;
            transition: all 0.25s ease;
            width: 100%;
            margin-top: 10px;
        }

        .profile-card-item:hover .profile-card-view-btn {
            background: var(--alumni-primary);
            color: #FFFFFF;
            border-color: var(--alumni-primary);
        }

        /* ── 6. Separate Placements Section ── */
        .placements-light-section {
            background-color: var(--alumni-bg-soft);
            padding: 90px 0;
            border-top: 1px solid var(--alumni-border);
            border-bottom: 1px solid var(--alumni-border);
        }

        .placement-pill-filters {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .light-pill {
            background: #FFFFFF;
            border: 1px solid var(--alumni-border);
            color: var(--alumni-body);
            padding: 8px 24px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .light-pill.active, .light-pill:hover {
            background: var(--alumni-primary);
            color: #FFFFFF;
            border-color: var(--alumni-primary);
            box-shadow: 0 4px 15px rgba(49, 92, 140, 0.2);
        }

        .placement-white-card {
            background: #FFFFFF;
            border: 1px solid var(--alumni-border);
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 8px 25px rgba(23, 43, 77, 0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .placement-white-card:hover {
            transform: translateY(-5px);
            border-color: var(--alumni-gold);
            box-shadow: 0 15px 35px rgba(23, 43, 77, 0.09);
        }

        .placement-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .comp-tag {
            background: var(--alumni-bg-soft);
            border: 1px solid var(--alumni-border);
            color: var(--alumni-navy);
            font-weight: 700;
            font-size: 0.85rem;
            padding: 5px 12px;
            border-radius: 8px;
        }

        .pkg-tag {
            background: rgba(201, 164, 92, 0.15);
            color: #927027;
            border: 1px solid rgba(201, 164, 92, 0.4);
            font-weight: 800;
            font-size: 0.85rem;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .placement-student-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 15px;
        }

        .placement-student-img {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--alumni-gold);
        }

        .placement-student-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--alumni-navy);
            margin: 0;
        }

        .placement-student-roll {
            font-size: 0.82rem;
            color: var(--alumni-body);
            margin: 0;
        }

        /* ── 7. Where Are They Now Section ── */
        .where-light-section {
            background-color: var(--alumni-bg-main);
            padding: 90px 0;
        }

        .where-white-card {
            background: #FFFFFF;
            border: 1px solid var(--alumni-border);
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 6px 20px rgba(23, 43, 77, 0.04);
            height: 100%;
            transition: transform 0.3s ease;
        }

        .where-white-card:hover {
            transform: translateY(-4px);
            border-color: var(--alumni-primary);
        }

        .where-icon-box {
            width: 48px;
            height: 48px;
            background: var(--alumni-bg-soft);
            color: var(--alumni-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 16px;
        }

        .where-white-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--alumni-navy);
            margin-bottom: 8px;
        }

        .where-white-card p {
            font-size: 0.9rem;
            color: var(--alumni-body);
            line-height: 1.55;
            margin: 0;
        }

        /* ── 8. Alumni Achievements ── */
        .achievements-light-section {
            background-color: var(--alumni-bg-soft);
            padding: 90px 0;
            border-top: 1px solid var(--alumni-border);
        }

        /* ── 9. Final CTA Section ── */
        .cta-light-section {
            background: linear-gradient(135deg, #EEF5FC 0%, #F8F7F3 100%);
            padding: 80px 0;
            text-align: center;
            border-top: 1px solid var(--alumni-border);
        }

        .cta-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--alumni-navy);
            margin-bottom: 12px;
        }

        .cta-subtitle {
            font-size: 1.05rem;
            color: var(--alumni-body);
            margin-bottom: 28px;
        }

        /* ── Individual Profile Modal ── */
        .profile-light-body {
            padding: 35px;
            text-align: center;
        }

        .profile-light-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--alumni-gold);
            margin: 0 auto 16px auto;
            box-shadow: 0 8px 25px rgba(23, 43, 77, 0.12);
        }

        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            text-align: left;
            margin-top: 22px;
            background: var(--alumni-bg-soft);
            padding: 18px;
            border-radius: 14px;
            border: 1px solid var(--alumni-border);
        }

        .profile-info-box label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            display: block;
            margin-bottom: 2px;
        }

        .profile-info-box span {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--alumni-navy);
        }

        @media (max-width: 768px) {
            .hero-heading { font-size: 2.3rem; }
            .batch-img-wrapper { height: 200px; }
            .light-modal-dialog { max-height: 95vh; }
            .light-modal-header, .light-search-container, .light-modal-body { padding: 18px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, ::before, ::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="alumni-page-scope">

    <?php include 'nav.php'; ?>

    <!-- 2. Alumni Hero Section -->
    <section class="alumni-hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hero-tag-badge"><i class="fa-solid fa-graduation-cap"></i> SRKR CSD & CSIT</div>
                    <h1 class="hero-heading">OUR <span>ALUMNI</span></h1>
                    <p class="hero-desc">Celebrating the journey, achievements, and success of our graduates. Explore our alumni community, their careers, achievements, and the journeys they continue to build beyond graduation.</p>
                    <a href="#batches" class="hero-action-btn">Explore Alumni <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="col-lg-6">
                    <div class="hero-img-box">
                        <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=900&auto=format&fit=crop&q=80" alt="Batch 2025 Group Photo">
                    </div>
                </div>
            </div>

            <!-- 3. Alumni Statistics -->
            <div class="row g-4 stats-cards-wrapper">
                <div class="col-md-4">
                    <div class="stat-card-white">
                        <div class="stat-num-navy">136<span>+</span></div>
                        <div class="stat-lbl-gray">Official Alumni</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-white">
                        <div class="stat-num-navy">2</div>
                        <div class="stat-lbl-gray">Graduating Batches</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-white">
                        <div class="stat-num-navy">2024–2025</div>
                        <div class="stat-lbl-gray">Batches</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Our Batches Section -->
    <section class="batches-light-section" id="batches">
        <div class="container">
            <div class="alumni-section-header">
                <span class="sub-tag">COMMUNITY</span>
                <h2>OUR BATCHES</h2>
                <div class="gold-accent-line"></div>
                <p>Two batches, one shared legacy.</p>
            </div>

            <!-- 5. Two Separate Group Photo Cards -->
            <div class="row g-4 justify-content-center">
                <!-- BATCH 2024 CARD -->
                <div class="col-md-6 col-lg-5">
                    <div class="batch-photo-card" onclick="openBatchModal('2024')">
                        <div class="batch-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&auto=format&fit=crop&q=80" alt="Batch 2024 Group Photo">
                        </div>
                        <div class="batch-card-body">
                            <div>
                                <div class="batch-tag-name">CLASS OF 2024</div>
                                <h3 class="batch-card-title">BATCH 2024</h3>
                                <div class="batch-count-pill"><i class="fa-solid fa-users me-1"></i> 69 Alumni</div>
                            </div>
                            <span class="explore-batch-link">Explore Batch <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>

                <!-- BATCH 2025 CARD -->
                <div class="col-md-6 col-lg-5">
                    <div class="batch-photo-card" onclick="openBatchModal('2025')">
                        <div class="batch-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800&auto=format&fit=crop&q=80" alt="Batch 2025 Group Photo">
                        </div>
                        <div class="batch-card-body">
                            <div>
                                <div class="batch-tag-name">CLASS OF 2025</div>
                                <h3 class="batch-card-title">BATCH 2025</h3>
                                <div class="batch-count-pill"><i class="fa-solid fa-users me-1"></i> 67 Alumni</div>
                            </div>
                            <span class="explore-batch-link">Explore Batch <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7 & 8. Batch Directory Modal & Alumni Cards Grid -->
    <div class="light-modal" id="batchModal">
        <div class="light-modal-dialog">
            <div class="light-modal-header">
                <div>
                    <h3 class="light-modal-title" id="modalBatchTitle">BATCH 2024</h3>
                    <div style="font-size: 0.88rem; color: var(--alumni-body); margin-top: 2px;" id="modalBatchSubtitle">Meet the alumni of the 2024 graduating batch.</div>
                </div>
                <button class="light-modal-close" onclick="closeBatchModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <!-- 10. Search Box -->
            <div class="light-search-container">
                <div class="light-search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="directorySearchInput" class="light-search-input" placeholder="Search by Name or Registration Number..." onkeyup="filterDirectoryCards()">
                </div>
            </div>

            <!-- 9. Alumni Directory UI (Grid Cards) -->
            <div class="light-modal-body">
                <div class="row g-3" id="profileCardsGrid">
                    <!-- Profile cards rendered via JavaScript -->
                </div>
                <div class="text-center py-5" id="emptyStateMsg" style="display: none;">
                    <i class="fa-solid fa-user-slash text-muted mb-3" style="font-size: 2.5rem;"></i>
                    <h5 style="color: var(--alumni-navy); font-weight: 700;">No alumni found</h5>
                    <p style="color: var(--alumni-body);">Try another name or registration number.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 12. Separate Placements Section -->
    <section class="placements-light-section" id="placements">
        <div class="container">
            <div class="alumni-section-header">
                <span class="sub-tag">CAREER OFFERS</span>
                <h2>ALUMNI PLACEMENTS</h2>
                <div class="gold-accent-line"></div>
                <p>Our alumni are building successful careers across diverse organizations and industries.</p>
            </div>


            <div class="row g-4" id="placementsGrid">
                <!-- Placements rendered via JS -->
            </div>
        </div>
    </section>

    <!-- 14. Alumni Achievements -->
    <section class="achievements-light-section">
        <div class="container">
            <div class="alumni-section-header">
                <span class="sub-tag">HONORS</span>
                <h2>ALUMNI ACHIEVEMENTS</h2>
                <div class="gold-accent-line"></div>
                <p>Celebrating awards, publications, and entrepreneurship milestones achieved by our graduates.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="where-white-card" style="border-top: 3px solid var(--alumni-gold);">
                        <div class="where-icon-box" style="color: var(--alumni-gold);"><i class="fa-solid fa-trophy"></i></div>
                        <h3>Industry Excellence Awards</h3>
                        <p>Recognized for outstanding technical contributions and fast-track engineering promotions.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="where-white-card" style="border-top: 3px solid var(--alumni-gold);">
                        <div class="where-icon-box" style="color: var(--alumni-gold);"><i class="fa-solid fa-file-contract"></i></div>
                        <h3>Research & Patents</h3>
                        <p>Co-authoring international technical whitepapers and patented algorithms in cloud co-design.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 15. Final Alumni CTA -->
    <section class="cta-light-section">
        <div class="container">
            <h2 class="cta-title">THE JOURNEY CONTINUES</h2>
            <p class="cta-subtitle">Once a student, always a part of our community.</p>
            <a href="#batches" class="hero-action-btn">Explore Our Alumni <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </section>

    <!-- 11. Individual Alumni Profile Modal -->
    <div class="light-modal" id="individualProfileModal">
        <div class="light-modal-dialog" style="max-width: 580px;">
            <div class="light-modal-header">
                <h4 style="margin:0; font-weight:800; color: var(--alumni-navy);">Alumni Profile</h4>
                <button class="light-modal-close" onclick="closeIndividualModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="profile-light-body">
                <img src="" id="indivAvatar" class="profile-light-avatar" alt="Student Photo">
                <h3 id="indivName" style="font-size: 1.4rem; font-weight: 800; color: var(--alumni-navy); margin-bottom: 2px;">Student Name</h3>
                <div id="indivRole" style="color: var(--alumni-gold); font-weight: 700; font-size: 0.92rem; margin-bottom: 15px;">CSD Alumnus</div>

                <div class="profile-info-grid">
                    <div class="profile-info-box"><label>Registration No</label><span id="indivReg">21B91A6201</span></div>
                    <div class="profile-info-box"><label>Batch</label><span id="indivBatch">Batch 2024</span></div>
                    <div class="profile-info-box"><label>Department</label><span id="indivDept">CSD</span></div>
                    <div class="profile-info-box"><label>Location</label><span id="indivLoc">Bhimavaram, India</span></div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Scoped JS Operations -->
    <script>
        let batchDataStore = { '2024': [], '2025': [] };
        let placementsStore = [];
        let activeBatchModal = '2024';

        document.addEventListener("DOMContentLoaded", () => {
            fetch('get_alumni.php')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        batchDataStore['2024'] = data.batches['2024'].alumni;
                        batchDataStore['2025'] = data.batches['2025'].alumni;
                        placementsStore = data.placements;

                        renderPlacements('all');
                    }
                })
                .catch(err => console.error("Error fetching alumni:", err));
        });

        // Open Batch Modal
        function openBatchModal(batchYear) {
            activeBatchModal = batchYear;
            document.getElementById('modalBatchTitle').innerText = `BATCH ${batchYear}`;
            document.getElementById('modalBatchSubtitle').innerText = `Meet the alumni of the ${batchYear} graduating batch.`;
            document.getElementById('directorySearchInput').value = '';

            const list = batchDataStore[batchYear] || [];
            renderProfileCards(list);
            document.getElementById('batchModal').classList.add('active');
        }

        function closeBatchModal() {
            document.getElementById('batchModal').classList.remove('active');
        }

        // Render Profile Cards Grid (3-4 columns desktop, 2 tablet, 1 mobile)
        function renderProfileCards(list) {
            const grid = document.getElementById('profileCardsGrid');
            const emptyState = document.getElementById('emptyStateMsg');
            grid.innerHTML = '';

            if (list.length === 0) {
                emptyState.style.display = 'block';
                return;
            }

            emptyState.style.display = 'none';

            list.forEach(item => {
                const col = document.createElement('div');
                col.className = 'col-sm-6 col-md-4 col-lg-3';
                col.innerHTML = `
                    <div class="profile-card-item">
                        <div>
                            <img src="${item.photo}" class="profile-card-avatar" alt="${item.name}">
                            <div class="profile-card-name">${item.name}</div>
                            <div class="profile-card-reg">${item.registration_number}</div>
                            <div style="font-size: 0.8rem; color: var(--alumni-body); font-weight: 600;">${item.department}</div>
                        </div>
                        <button class="profile-card-view-btn" onclick='openIndividualModal(${JSON.stringify(item)})'>View Profile <i class="fa-solid fa-arrow-right ms-1"></i></button>
                    </div>
                `;
                grid.appendChild(col);
            });
        }

        // Live Search Filter for Active Batch Only
        function filterDirectoryCards() {
            const query = document.getElementById('directorySearchInput').value.toLowerCase().trim();
            const list = batchDataStore[activeBatchModal] || [];

            const filtered = list.filter(item => {
                const searchStr = `${item.name} ${item.registration_number}`.toLowerCase();
                return searchStr.includes(query);
            });

            renderProfileCards(filtered);
        }

        // Render Placements
        function renderPlacements(filterBatch) {
            const grid = document.getElementById('placementsGrid');
            grid.innerHTML = '';

            let list = placementsStore;
            if (filterBatch !== 'all') {
                list = placementsStore.filter(p => p.batch === filterBatch || p.placement_year === filterBatch);
            }

            list.forEach(p => {
                const col = document.createElement('div');
                col.className = 'col-md-6 col-lg-4';
                col.innerHTML = `
                    <div class="placement-white-card">
                        <div>
                            <div class="placement-card-top">
                                <span class="comp-tag"><i class="fa-solid fa-building me-1"></i> ${p.company}</span>
                                ${p.package ? `<span class="pkg-tag">${p.package}</span>` : ''}
                            </div>
                            <div style="font-size: 0.95rem; font-weight: 700; color: var(--alumni-primary); margin-top: 10px;">${p.job_role}</div>
                        </div>

                        <div class="placement-student-row">
                            <img src="${p.photo}" class="placement-student-img" alt="${p.student_name}" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(p.student_name)}&background=d97706&color=ffffff&bold=true'">
                            <div>
                                <h4 class="placement-student-name">${p.student_name}</h4>
                                <p class="placement-student-roll">Roll No: ${p.student_id}</p>
                            </div>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });
        }

        function filterPlacements(batch, btn) {
            document.querySelectorAll('.placement-pill-filters .light-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderPlacements(batch);
        }

        // Open Individual Profile Modal
        function openIndividualModal(item) {
            document.getElementById('indivAvatar').src = item.photo;
            document.getElementById('indivName').innerText = item.name;
            document.getElementById('indivRole').innerText = `${item.current_role} @ ${item.company}`;
            document.getElementById('indivReg').innerText = item.registration_number;
            document.getElementById('indivBatch').innerText = `Batch ${item.batch}`;
            document.getElementById('indivDept').innerText = item.department;
            document.getElementById('indivLoc').innerText = item.location || 'Bhimavaram, India';

            document.getElementById('individualProfileModal').classList.add('active');
        }

        function closeIndividualModal() {
            document.getElementById('individualProfileModal').classList.remove('active');
        }
    </script>
</body>
</html>
