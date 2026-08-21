<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
include './connect.php';
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <?php include "./head.php"; ?>
    <title>Our Alumni - Department of CSD & CSIT | SRKR Engineering College</title>
    
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --alumni-primary: #1a0d06;
            --alumni-accent: #d97706;
            --alumni-accent-hover: #b45309;
            --alumni-gold: #f59e0b;
            --alumni-bg-soft: #fffbf5;
            --alumni-card-border: rgba(217, 119, 6, 0.18);
            --alumni-text-main: #1e293b;
            --alumni-text-muted: #64748b;
            --font-display: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: #f8fafc;
            color: var(--alumni-text-main);
            overflow-x: hidden;
        }

        /* Hero Section */
        .alumni-hero {
            background: linear-gradient(135deg, #1a0d06 0%, #2d160a 60%, #421e0d 100%);
            position: relative;
            padding: 85px 0 70px;
            color: #ffffff;
            overflow: hidden;
            border-bottom: 3px solid var(--alumni-accent);
        }

        .alumni-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(217, 119, 6, 0.18) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .alumni-hero .hero-badge {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.35);
            color: #fcd34d;
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            backdrop-filter: blur(10px);
        }

        .alumni-hero h1 {
            font-family: var(--font-display);
            font-weight: 900;
            font-size: 3.2rem;
            line-height: 1.15;
            color: #ffffff;
            margin-bottom: 16px;
        }

        .alumni-hero h1 span {
            color: var(--alumni-gold);
            background: linear-gradient(120deg, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .alumni-hero p.subtitle {
            font-size: 1.18rem;
            color: #e2e8f0;
            max-width: 720px;
            font-weight: 400;
            line-height: 1.6;
        }

        /* Introduction Box */
        .intro-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px 35px;
            border: 1px solid var(--alumni-card-border);
            box-shadow: 0 10px 30px rgba(180, 83, 9, 0.06);
            margin-top: -35px;
            position: relative;
            z-index: 10;
        }

        .intro-card p {
            font-size: 1.05rem;
            color: #334155;
            line-height: 1.7;
            margin: 0;
        }

        /* Stat Cards */
        .alumni-stat-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 24px 20px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-align: center;
            height: 100%;
        }

        .alumni-stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--alumni-accent);
            box-shadow: 0 12px 30px rgba(217, 119, 6, 0.12);
        }

        .alumni-stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(217, 119, 6, 0.12);
            color: var(--alumni-accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 12px;
        }

        .alumni-stat-number {
            font-family: var(--font-display);
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--alumni-primary);
            line-height: 1;
            margin-bottom: 4px;
        }

        .alumni-stat-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--alumni-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Search & Filter Bar */
        .filter-wrapper {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
            margin-bottom: 40px;
        }

        .filter-wrapper .form-control,
        .filter-wrapper .form-select {
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            padding: 11px 16px;
            font-size: 0.92rem;
            color: #1e293b;
            transition: all 0.25s ease;
        }

        .filter-wrapper .form-control:focus,
        .filter-wrapper .form-select:focus {
            border-color: var(--alumni-accent);
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
        }

        /* Section Titles */
        .section-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--alumni-accent);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .section-main-title {
            font-family: var(--font-display);
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--alumni-primary);
            margin-bottom: 25px;
        }

        /* Alumni Cards */
        .alumni-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .alumni-card:hover {
            transform: translateY(-7px);
            border-color: rgba(217, 119, 6, 0.4);
            box-shadow: 0 16px 36px rgba(217, 119, 6, 0.12);
        }

        .alumni-card-header {
            padding: 22px 22px 12px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .alumni-avatar-box {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            overflow: hidden;
            background: linear-gradient(135deg, #1a0d06, #d97706);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-family: var(--font-display);
            font-size: 1.6rem;
            font-weight: 800;
            flex-shrink: 0;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .alumni-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .alumni-meta-info h5 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.18rem;
            color: var(--alumni-primary);
            margin: 0 0 4px 0;
        }

        .alumni-meta-info .role-text {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--alumni-accent);
            margin-bottom: 4px;
        }

        .alumni-meta-info .company-text {
            font-size: 0.85rem;
            color: var(--alumni-text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .alumni-card-body {
            padding: 12px 22px 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .alumni-tag-group {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 14px;
        }

        .alumni-tag {
            background: #f1f5f9;
            color: #475569;
            font-size: 0.76rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
        }

        .alumni-tag.tag-batch {
            background: #fffbeb;
            color: #b45309;
            border-color: #fde68a;
        }

        .alumni-tag.tag-branch {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .alumni-desc {
            font-size: 0.88rem;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .alumni-card-footer {
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-view-profile {
            background: rgba(217, 119, 6, 0.1);
            color: var(--alumni-accent);
            border: none;
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.25s ease;
        }

        .btn-view-profile:hover {
            background: var(--alumni-accent);
            color: #ffffff;
        }

        .social-link-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #f1f5f9;
            color: #0077b5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .social-link-btn:hover {
            background: #0077b5;
            color: #ffffff;
        }

        /* Notable Alumni Spotlight Cards */
        .notable-card {
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            border: 2px solid #fde68a;
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 10px 28px rgba(217, 119, 6, 0.08);
            position: relative;
        }

        .notable-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(180, 83, 9, 0.25);
        }

        /* Testimonials / Success Stories */
        .story-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 26px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .story-quote {
            font-size: 0.95rem;
            color: #334155;
            font-style: italic;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .story-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* CTA Section */
        .alumni-cta {
            background: linear-gradient(135deg, #1a0d06 0%, #2a150a 100%);
            border-radius: 26px;
            padding: 55px 40px;
            color: #ffffff;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--alumni-accent);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            margin-top: 50px;
            margin-bottom: 60px;
        }

        .alumni-cta h2 {
            font-family: var(--font-display);
            font-weight: 900;
            font-size: 2.3rem;
            color: #ffffff;
            margin-bottom: 12px;
        }

        .alumni-cta p {
            color: #e2e8f0;
            font-size: 1.08rem;
            max-width: 650px;
            margin: 0 auto 28px;
        }

        .btn-cta-primary {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 12px 32px;
            font-size: 0.95rem;
            font-weight: 700;
            box-shadow: 0 6px 20px rgba(217, 119, 6, 0.4);
            transition: all 0.3s ease;
        }

        .btn-cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(217, 119, 6, 0.5);
            color: #ffffff;
        }

        .btn-cta-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 50px;
            padding: 12px 28px;
            font-size: 0.95rem;
            font-weight: 700;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .btn-cta-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        /* Profile Modal */
        .modal-header-custom {
            background: linear-gradient(135deg, #1a0d06 0%, #2d160a 100%);
            color: #ffffff;
            padding: 25px 30px;
            border-radius: 20px 20px 0 0;
        }

        .modal-body-custom {
            padding: 30px;
        }
    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include "./nav.php"; ?>

    <!-- HERO SECTION -->
    <section class="alumni-hero text-center text-lg-start">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-badge">
                        <i class="fas fa-graduation-cap"></i> Department Alumni Network
                    </div>
                    <h1>Our <span>Alumni</span></h1>
                    <p class="subtitle">
                        Celebrating our graduates and their inspiring journeys across global technology leaders, research institutions, and innovative startups.
                    </p>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <div style="width: 160px; height: 160px; border-radius: 36px; background: rgba(245, 158, 11, 0.12); border: 2px dashed rgba(245, 158, 11, 0.4); display: inline-flex; align-items: center; justify-content: center; color: var(--alumni-gold); font-size: 4.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                        <i class="fas fa-award"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mb-5">
        <!-- INTRODUCTION CARD -->
        <div class="intro-card mb-5">
            <div class="d-flex align-items-start gap-3">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(217, 119, 6, 0.15); color: var(--alumni-accent); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; margin-top: 2px;">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p>
                    Our alumni are an integral part of our department community. They continue to make meaningful contributions across technology, research, industry, entrepreneurship, and higher education worldwide. Through mentorship, industry collaborations, and technical innovation, our graduates inspire current batches to reach peak professional excellence.
                </p>
            </div>
        </div>

        <!-- ALUMNI STATISTICS GRID -->
        <div class="row g-4 mb-5" id="statsGrid">
            <div class="col-6 col-md-3">
                <div class="alumni-stat-card">
                    <div class="alumni-stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="alumni-stat-number" id="statTotalAlumni">500+</div>
                    <div class="alumni-stat-label">Total Alumni</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="alumni-stat-card">
                    <div class="alumni-stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="alumni-stat-number" id="statIndustries">15+</div>
                    <div class="alumni-stat-label">Industry Sectors</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="alumni-stat-card">
                    <div class="alumni-stat-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="alumni-stat-number" id="statHigherStudies">45+</div>
                    <div class="alumni-stat-label">Higher Studies</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="alumni-stat-card">
                    <div class="alumni-stat-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="alumni-stat-number" id="statEntrepreneurs">12+</div>
                    <div class="alumni-stat-label">Entrepreneurs</div>
                </div>
            </div>
        </div>

        <!-- SEARCH AND FILTER BAR -->
        <div class="filter-wrapper">
            <div class="row g-3 align-items-center">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fas fa-search me-1"></i> Search Alumni</label>
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by name, company, role...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fas fa-calendar-alt me-1"></i> Graduation Batch</label>
                    <select id="batchFilter" class="form-select">
                        <option value="all">All Batches</option>
                        <option value="2025">Batch 2025</option>
                        <option value="2024">Batch 2024</option>
                        <option value="2023">Batch 2023</option>
                        <option value="2022">Batch 2022</option>
                        <option value="2021">Batch 2021</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fas fa-code-branch me-1"></i> Branch</label>
                    <select id="branchFilter" class="form-select">
                        <option value="ALL">All Branches</option>
                        <option value="CSD">CSD</option>
                        <option value="CSIT">CSIT</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fas fa-industry me-1"></i> Industry Sector</label>
                    <select id="industryFilter" class="form-select">
                        <option value="all">All Industries</option>
                        <option value="Software & Tech">Software & Tech</option>
                        <option value="AI & Machine Learning">AI & Machine Learning</option>
                        <option value="Higher Studies">Higher Studies</option>
                        <option value="Entrepreneurship">Entrepreneurship</option>
                        <option value="Core Engineering">Core Engineering</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- NOTABLE ALUMNI SECTION -->
        <div class="mb-5" id="notableSection">
            <div class="section-header-badge"><i class="fas fa-star text-warning"></i> Hall of Fame</div>
            <h2 class="section-main-title">Notable Alumni</h2>
            <div class="row g-4" id="notableContainer">
                <!-- Dynamically populated -->
            </div>
        </div>

        <!-- ALL ALUMNI DIRECTORY SECTION -->
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div>
                    <div class="section-header-badge"><i class="fas fa-users"></i> Directory</div>
                    <h2 class="section-main-title mb-0">Alumni Directory</h2>
                </div>
                <span class="badge bg-dark text-warning fs-6 px-3 py-2 rounded-pill" id="resultCountBadge">Showing Alumni</span>
            </div>

            <div class="row g-4" id="alumniGrid">
                <!-- Dynamically populated -->
            </div>

            <div id="noResultsState" class="text-center py-5 d-none">
                <i class="fas fa-user-slash text-muted" style="font-size: 3.5rem;"></i>
                <h4 class="mt-3 fw-bold">No Matching Alumni Found</h4>
                <p class="text-muted">Try broadening your search keyword or clearing the filters.</p>
                <button class="btn btn-outline-warning rounded-pill px-4" onclick="clearAllFilters()">Reset Filters</button>
            </div>
        </div>

        <!-- ALUMNI SUCCESS STORIES SECTION -->
        <div class="mb-5">
            <div class="section-header-badge"><i class="fas fa-comment-dots"></i> Testimonials</div>
            <h2 class="section-main-title">Alumni Success Stories</h2>
            <div class="row g-4" id="testimonialsContainer">
                <!-- Dynamically populated -->
            </div>
        </div>

        <!-- STAY CONNECTED CTA -->
        <div class="alumni-cta">
            <div class="badge bg-warning text-dark font-weight-bold px-3 py-1 rounded-pill mb-3" style="font-size: 0.8rem; letter-spacing: 0.5px; display: inline-block;">
                <i class="fas fa-handshake me-1"></i> Join Our Community
            </div>
            <h2>Stay Connected With Your Department</h2>
            <p>
                Whether you are building the future of technology, pursuing research, or launching startups, we welcome you to stay connected and mentor the next generation.
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-3">
                <button class="btn btn-cta-primary" onclick="openNetworkModal('join')">
                    <i class="fas fa-user-plus me-2"></i> Join Alumni Network
                </button>
                <button class="btn btn-cta-secondary" onclick="openNetworkModal('update')">
                    <i class="fas fa-sync me-2"></i> Update Your Details
                </button>
                <a href="mailto:csd.csit@srkrec.ac.in" class="btn btn-cta-secondary">
                    <i class="fas fa-envelope me-2"></i> Contact Department
                </a>
            </div>
        </div>
    </div>

    <!-- ALUMNI PROFILE DETAILS MODAL -->
    <div class="modal fade" id="alumniProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header-custom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.2); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-white" id="modalName">Alumni Details</h5>
                            <small class="text-warning fw-semibold" id="modalCompanyRole">Role @ Company</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body-custom">
                    <div class="row g-4 align-items-center mb-4 pb-3 border-bottom">
                        <div class="col-md-3 text-center">
                            <div id="modalAvatarBox" class="alumni-avatar-box mx-auto" style="width: 100px; height: 100px; font-size: 2.2rem; border-radius: 24px;">
                                <!-- Image or Initial -->
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h3 class="fw-bold mb-1" id="modalFullName">Full Name</h3>
                            <p class="text-primary fw-bold mb-2" id="modalDesignation">Designation</p>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill" id="modalBatchTag">Batch 2022</span>
                                <span class="badge bg-primary px-3 py-1 rounded-pill" id="modalBranchTag">CSD</span>
                                <span class="badge bg-secondary px-3 py-1 rounded-pill" id="modalIndustryTag">Software</span>
                            </div>
                            <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt text-danger me-1"></i> <span id="modalLocation">Location</span></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark text-uppercase small letter-spacing-1 mb-2"><i class="fas fa-briefcase text-warning me-2"></i> Professional Journey</h6>
                        <p class="text-secondary" id="modalDescription">Description details...</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark text-uppercase small letter-spacing-1 mb-2"><i class="fas fa-trophy text-warning me-2"></i> Key Achievements & Highlights</h6>
                        <div class="p-3 bg-light rounded-3 text-dark font-weight-500" id="modalAchievements">Achievements details...</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                        <a id="modalLinkedInBtn" href="#" target="_blank" class="btn btn-primary rounded-pill px-4">
                            <i class="fab fa-linkedin me-1"></i> Connect on LinkedIn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NETWORK / UPDATE MODAL -->
    <div class="modal fade" id="networkActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-dark text-white rounded-top-4">
                    <h5 class="modal-title fw-bold text-warning" id="networkModalTitle"><i class="fas fa-user-plus me-2"></i> Join Alumni Network</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="networkForm" onsubmit="handleNetworkSubmit(event)">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" class="form-control" required placeholder="Enter full name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" class="form-control" required placeholder="name@domain.com">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Graduation Year</label>
                                <input type="number" class="form-control" required placeholder="e.g. 2023">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Branch</label>
                                <select class="form-select" required>
                                    <option value="CSD">CSD</option>
                                    <option value="CSIT">CSIT</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Current Company & Designation</label>
                            <input type="text" class="form-control" required placeholder="e.g. SDE II @ Amazon">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">LinkedIn Profile URL</label>
                            <input type="url" class="form-control" placeholder="https://linkedin.com/in/yourprofile">
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill py-2">Submit Details</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include "./footer.php"; ?>

    <!-- JavaScript logic -->
    <script>
        let globalAlumniData = [];

        document.addEventListener('DOMContentLoaded', function() {
            fetchAlumniData();

            // Bind live filters
            document.getElementById('searchInput').addEventListener('input', applyFilters);
            document.getElementById('batchFilter').addEventListener('change', applyFilters);
            document.getElementById('branchFilter').addEventListener('change', applyFilters);
            document.getElementById('industryFilter').addEventListener('change', applyFilters);
        });

        function fetchAlumniData() {
            fetch('get_alumni.php')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        globalAlumniData = data.alumni || [];
                        updateStats(data.stats);
                        renderNotableAlumni(data.notable_alumni || []);
                        renderTestimonials(data.testimonials || []);
                        applyFilters();
                    }
                })
                .catch(err => {
                    console.error('Error fetching alumni:', err);
                });
        }

        function updateStats(stats) {
            if (!stats) return;
            if (document.getElementById('statTotalAlumni')) document.getElementById('statTotalAlumni').textContent = stats.total_alumni + '+';
            if (document.getElementById('statIndustries')) document.getElementById('statIndustries').textContent = stats.total_industries + '+';
            if (document.getElementById('statHigherStudies')) document.getElementById('statHigherStudies').textContent = stats.higher_studies + '+';
            if (document.getElementById('statEntrepreneurs')) document.getElementById('statEntrepreneurs').textContent = stats.entrepreneurs + '+';
        }

        function renderNotableAlumni(list) {
            const container = document.getElementById('notableContainer');
            if (!container) return;
            if (!list || list.length === 0) {
                document.getElementById('notableSection').classList.add('d-none');
                return;
            }

            container.innerHTML = list.map(item => `
                <div class="col-md-6 col-lg-4">
                    <div class="notable-card h-100">
                        <span class="notable-badge"><i class="fas fa-star me-1"></i> Notable</span>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="alumni-avatar-box">
                                ${item.photo ? `<img src="${item.photo}" alt="${item.name}">` : `<span>${getInitials(item.name)}</span>`}
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">${item.name}</h5>
                                <div class="text-warning fw-bold small">${item.designation}</div>
                                <div class="text-muted small fw-semibold"><i class="fas fa-building me-1"></i> ${item.company}</div>
                            </div>
                        </div>
                        <p class="alumni-desc mb-3">${item.description}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-warning text-dark rounded-pill">Batch ${item.batch} | ${item.branch}</span>
                            <button class="btn btn-view-profile" onclick="openProfileModal('${item.id}')">View Profile</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderTestimonials(list) {
            const container = document.getElementById('testimonialsContainer');
            if (!container || !list) return;

            container.innerHTML = list.map(item => `
                <div class="col-md-6 col-lg-3">
                    <div class="story-card">
                        <div class="story-quote">
                            "${item.quote}"
                        </div>
                        <div class="story-author">
                            <div class="alumni-avatar-box" style="width: 46px; height: 46px; font-size: 1.1rem; border-radius: 12px;">
                                ${item.photo ? `<img src="${item.photo}" alt="${item.name}">` : `<span>${getInitials(item.name)}</span>`}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">${item.name}</h6>
                                <small class="text-muted d-block" style="font-size: 0.78rem;">${item.role}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function applyFilters() {
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            const batchVal = document.getElementById('batchFilter').value;
            const branchVal = document.getElementById('branchFilter').value;
            const industryVal = document.getElementById('industryFilter').value.toLowerCase();

            const filtered = globalAlumniData.filter(item => {
                if (searchVal) {
                    const haystack = (item.name + ' ' + item.company + ' ' + item.designation + ' ' + item.branch).toLowerCase();
                    if (!haystack.includes(searchVal)) return false;
                }
                if (batchVal !== 'all' && item.batch !== batchVal) return false;
                if (branchVal !== 'ALL' && item.branch !== branchVal) return false;
                if (industryVal !== 'all' && item.industry.toLowerCase() !== industryVal) return false;
                return true;
            });

            renderAlumniGrid(filtered);
        }

        function renderAlumniGrid(list) {
            const grid = document.getElementById('alumniGrid');
            const emptyState = document.getElementById('noResultsState');
            const badge = document.getElementById('resultCountBadge');

            if (badge) badge.textContent = `Showing ${list.length} Alumni`;

            if (!list || list.length === 0) {
                grid.innerHTML = '';
                emptyState.classList.remove('d-none');
                return;
            }

            emptyState.classList.add('d-none');
            grid.innerHTML = list.map(item => `
                <div class="col-md-6 col-lg-3">
                    <div class="alumni-card">
                        <div class="alumni-card-header">
                            <div class="alumni-avatar-box">
                                ${item.photo ? `<img src="${item.photo}" alt="${item.name}">` : `<span>${getInitials(item.name)}</span>`}
                            </div>
                            <div class="alumni-meta-info">
                                <h5>${item.name}</h5>
                                <div class="role-text">${item.designation}</div>
                                <div class="company-text"><i class="fas fa-building"></i> ${item.company}</div>
                            </div>
                        </div>
                        <div class="alumni-card-body">
                            <div class="alumni-tag-group">
                                <span class="alumni-tag tag-batch">Batch ${item.batch}</span>
                                <span class="alumni-tag tag-branch">${item.branch}</span>
                                <span class="alumni-tag"><i class="fas fa-map-marker-alt me-1"></i> ${item.location}</span>
                            </div>
                            <p class="alumni-desc">${item.description}</p>
                            <div class="alumni-card-footer">
                                <button class="btn-view-profile" onclick="openProfileModal('${item.id}')">
                                    <i class="fas fa-user-circle me-1"></i> Details
                                </button>
                                ${item.linkedin ? `<a href="${item.linkedin}" target="_blank" class="social-link-btn" title="LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function openProfileModal(id) {
            const item = globalAlumniData.find(a => a.id === id || a.student_id === id);
            if (!item) return;

            document.getElementById('modalName').textContent = item.name;
            document.getElementById('modalCompanyRole').textContent = item.designation + ' @ ' + item.company;
            document.getElementById('modalFullName').textContent = item.name;
            document.getElementById('modalDesignation').textContent = item.designation + ' — ' + item.company;
            document.getElementById('modalBatchTag').textContent = 'Batch ' + item.batch;
            document.getElementById('modalBranchTag').textContent = 'Department of ' + item.branch;
            document.getElementById('modalIndustryTag').textContent = item.industry;
            document.getElementById('modalLocation').textContent = item.location;
            document.getElementById('modalDescription').textContent = item.description;
            document.getElementById('modalAchievements').textContent = item.achievements || 'Distinguished CSD & CSIT Department Graduate.';

            const avatarBox = document.getElementById('modalAvatarBox');
            if (item.photo) {
                avatarBox.innerHTML = `<img src="${item.photo}" alt="${item.name}">`;
            } else {
                avatarBox.innerHTML = `<span>${getInitials(item.name)}</span>`;
            }

            const linkedinBtn = document.getElementById('modalLinkedInBtn');
            if (item.linkedin) {
                linkedinBtn.href = item.linkedin;
                linkedinBtn.classList.remove('d-none');
            } else {
                linkedinBtn.classList.add('d-none');
            }

            const modal = new bootstrap.Modal(document.getElementById('alumniProfileModal'));
            modal.show();
        }

        function openNetworkModal(type) {
            const title = document.getElementById('networkModalTitle');
            if (type === 'update') {
                title.innerHTML = '<i class="fas fa-sync me-2"></i> Update Your Details';
            } else {
                title.innerHTML = '<i class="fas fa-user-plus me-2"></i> Join Alumni Network';
            }
            const modal = new bootstrap.Modal(document.getElementById('networkActionModal'));
            modal.show();
        }

        function handleNetworkSubmit(e) {
            e.preventDefault();
            alert('Thank you! Your details have been submitted successfully. The department network team will connect with you soon.');
            const modalEl = document.getElementById('networkActionModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }

        function clearAllFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('batchFilter').value = 'all';
            document.getElementById('branchFilter').value = 'ALL';
            document.getElementById('industryFilter').value = 'all';
            applyFilters();
        }

        function getInitials(name) {
            if (!name) return 'A';
            const parts = name.trim().split(' ');
            if (parts.length >= 2) {
                return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
            }
            return parts[0].substring(0, 2).toUpperCase();
        }
    </script>
</body>
</html>
