<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
$current_page = 'esteemed-leaders.php';
include "./head.php"; 
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --primary-amber: #d97706;
    --primary-light: #f59e0b;
    --amber-gold: #d97706;
    --bright-yellow: #f59e0b;
    --golden-champagne: #e6c280;
    --amber-badge: #b45309;
    --rich-espresso: #1a0d06;
    --cream-white: #fdfbf7;
    
    --text-primary: #1a0d06;
    --text-secondary: #6f5f54;
    --text-light: #94a3b8;
    --bg-light: #fdfbf7;
    --border-light: #f3eae1;
    --white: #ffffff;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 10px 30px rgba(180, 83, 9, 0.08);
    --shadow-lg: 0 20px 45px rgba(180, 83, 9, 0.16);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #fdfbf7;
    line-height: 1.6;
    color: #1a0d06;
}

/* Hero Section */
.leaders-hero-section {
    background: linear-gradient(135deg, #1a0d06 0%, #2a150a 50%, #3d1e0e 100%);
    color: white;
    padding: 85px 20px 65px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.leaders-hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(230, 194, 128, 0.15) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.45;
}

.hero-tag {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2.5px;
    color: #f59e0b;
    background: rgba(245, 158, 11, 0.12);
    padding: 6px 20px;
    border-radius: 999px;
    display: inline-block;
    margin-bottom: 16px;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2.4rem, 5vw, 3.6rem);
    font-weight: 900;
    line-height: 1.15;
    margin-bottom: 0.75rem;
    background: linear-gradient(135deg, #ffffff 0%, #f5ebe6 35%, #e6c280 70%, #d49b59 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.15rem;
    font-weight: 400;
    color: #e5d5c5;
    max-width: 720px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Category Filter Navigation */
.leaders-grid-section {
    padding: 50px 0 80px;
    background: #fdfbf7;
}

.filter-btn {
    background: #ffffff;
    color: #6f5f54;
    border: 1.5px solid #f3eae1;
    padding: 10px 22px;
    border-radius: 999px;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-btn:hover, .filter-btn.active {
    background: #1a0d06;
    color: #f59e0b;
    border-color: #1a0d06;
    box-shadow: 0 6px 20px rgba(26, 13, 6, 0.15);
}

/* Row Header Styling */
.row-category-header {
    margin: 45px 0 25px;
    padding-bottom: 12px;
    border-bottom: 2.5px solid #f3eae1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.row-category-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.7rem;
    font-weight: 800;
    color: #1a0d06;
    margin: 0;
}

.row-category-badge {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    background: #fffbeb;
    color: #b45309;
    padding: 5px 16px;
    border-radius: 999px;
    border: 1px solid #fde68a;
}

/* Faculty Style Leader Line Card */
.leader-line-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 24px 26px;
    box-shadow: 0 10px 28px rgba(26, 13, 6, 0.05);
    border: 1px solid #f3eae1;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.leader-line-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 7px;
    background: linear-gradient(180deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
    opacity: 0.95;
    transition: opacity 0.3s ease;
}

.leader-line-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 40px rgba(217, 119, 6, 0.16);
    border-color: rgba(217, 119, 6, 0.4);
}

.card-inner-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 22px;
    width: 100%;
    height: 100%;
}

@media (max-width: 576px) {
    .card-inner-flex {
        flex-direction: column-reverse;
        text-align: center;
    }
}

.leader-details-left {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.header-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}

.hod-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: #ffffff;
    font-family: 'Outfit', sans-serif;
    font-size: 0.74rem;
    font-weight: 800;
    padding: 5px 14px;
    border-radius: 50px;
    text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(180, 83, 9, 0.28);
}

.dept-pill {
    display: inline-flex;
    align-items: center;
    background: #fffbeb;
    color: #b45309;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 4px 12px;
    border-radius: 50px;
    border: 1px solid #fde68a;
}

.leader-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1.45rem;
    font-weight: 800;
    color: #1a0d06;
    line-height: 1.25;
    margin-bottom: 4px;
}

.leader-designation {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.88rem;
    font-weight: 800;
    color: #d97706;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 10px;
}

.leader-about-text {
    color: #475569;
    font-size: 0.88rem;
    line-height: 1.58;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.actions-group {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: auto;
}

.area-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fffbeb;
    color: #92400e;
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 5px 14px;
    border-radius: 50px;
    border: 1px solid #fde68a;
}

.cv-details-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: #ffffff !important;
    border: none;
    padding: 7px 18px;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer !important;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(180, 83, 9, 0.28);
}

.cv-details-btn:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(180, 83, 9, 0.38);
}

/* Right Photo Container */
.leader-photo-right-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    width: 175px;
}

.leader-grid-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    height: 100%;
    cursor: pointer;
    display: flex;
    flex-direction: column;
}

.leader-grid-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.1);
    border-color: #d97706;
}

.leader-photo-box {
    width: 100%;
    height: 255px;
    background: #f1f5f9;
    overflow: hidden;
    position: relative;
}

.leader-photo-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    transition: transform 0.4s ease;
}

.leader-grid-card:hover .leader-photo-box img {
    transform: scale(1.05);
}

.leader-info-box {
    padding: 16px 14px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
}

.leader-name-title {
    font-size: 1.02rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
    line-height: 1.3;
}

.leader-designation-subtitle {
    font-size: 0.84rem;
    color: #64748b;
    margin-bottom: 0;
    line-height: 1.4;
    font-weight: 500;
}

.statement-btn-right {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    color: #ffffff !important;
    border: none;
    padding: 7px 12px;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.28);
    cursor: pointer;
}

.statement-btn-right:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(30, 64, 175, 0.38);
}

/* Leadership Profile Modal */
.modal-leader-header {
    background: linear-gradient(135deg, #1a0d06 0%, #2a150a 50%, #3d1e0e 100%);
    color: white;
    padding: 30px;
    border-bottom: 1px solid rgba(245, 158, 11, 0.2);
    position: relative;
}

.modal-leader-img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    object-position: center top;
    border: 3.5px solid #d97706;
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

.modal-leader-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1.7rem;
    font-weight: 800;
    color: #ffffff;
}

.modal-leader-designation {
    color: #f59e0b;
    font-weight: 700;
    font-size: 1rem;
}

.modal-leader-role {
    color: #e5d5c5;
    font-size: 0.88rem;
}

.modal-quote-box {
    background: #fdfbf7;
    border-left: 4px solid #d97706;
    border-radius: 12px;
    padding: 18px 22px;
    margin-bottom: 20px;
    border: 1px solid #f3eae1;
}

.modal-quote-box blockquote {
    font-style: italic;
    color: #1a0d06;
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.6;
}

.achievement-pill {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
}
</style>

<?php include "nav.php"; ?>

<!-- Hero Section -->
<section class="leaders-hero-section">
    <div class="container position-relative z-1">
        <span class="hero-tag"><i class="fas fa-award me-2"></i> Department Leadership</span>
        <h1 class="hero-title">Our <span>Esteemed Leaders</span></h1>
        <p class="hero-subtitle">
            Meet the distinguished leaders who guide our college and department towards academic excellence, innovation, and growth.
        </p>
    </div>
</section>

<!-- Leaders Grid Section -->
<section class="leaders-grid-section">
    <div class="container">

        <?php 
        // 3 Structured Governing Body Rows
        $leader_rows = [
            'row1' => [
                'title' => 'Executive & Governing Body Leadership',
                'badge' => 'Executive Board',
                'icon' => 'fas fa-crown',
                'color' => '#d97706',
                'leaders' => [
                    [
                        'id' => 's_prasada_raju',
                        'category' => 'row1',
                        'badge' => 'President',
                        'name' => 'Sri. S. Prasad Raju',
                        'designation' => 'President',
                        'role' => 'SRKREC Governing Body',
                        'photo' => 'assets/images/leaders/s_prasada_raju.jpg',
                        'bio' => 'Leading strategic institutional vision, governing principles, and overall campus development at SRKREC.',
                        'full_statement' => 'Our vision is to build an environment where innovative technical education empowers students to solve complex real-world challenges.',
                        'achievements' => [
                            'President of SRKR Engineering College Governing Body',
                            'Institutional Expansion & Academic Quality Leadership',
                            'Promoting Research Excellence & Student Infrastructure'
                        ]
                    ],
                    [
                        'id' => 'p_krishnam_raju',
                        'category' => 'row1',
                        'badge' => 'Honorary Chairman',
                        'name' => 'Sri. P. Krishnam Raju',
                        'designation' => 'Honorary Chairman, Member Governing Body',
                        'role' => 'SRKREC Governing Body',
                        'photo' => 'assets/images/leaders/p_krishnam_raju.jpg',
                        'bio' => 'Steering long-term strategic direction, ethics, and academic excellence across all college departments.',
                        'full_statement' => 'Dedicated to maintaining the highest values of integrity, academic rigor, and holistic development for all engineering disciplines.',
                        'achievements' => [
                            'Honorary Chairman of SRKREC Governing Body',
                            'Decades of Strategic Educational Leadership',
                            'Pioneer in Regional Technical & Higher Education Growth'
                        ]
                    ],
                    [
                        'id' => 'g_rama_raju',
                        'category' => 'row1',
                        'badge' => 'Vice President-I',
                        'name' => 'Sri. G. Rama Raju',
                        'designation' => 'Vice President-I',
                        'role' => 'SRKREC Governing Body',
                        'photo' => 'assets/images/leaders/g_rama_raju.jpg',
                        'bio' => 'Guiding campus growth, technological infrastructure, and institutional administrative strategies.',
                        'full_statement' => 'We strive to continuously modernize our laboratories, research suites, and academic ecosystems for student success.',
                        'achievements' => [
                            'Vice President-I of Governing Body',
                            'Infrastructure & Technology Modernization Leader',
                            'Fostering Industry Connections & Campus Development'
                        ]
                    ],
                    [
                        'id' => 'sv_ranga_raju',
                        'category' => 'row1',
                        'badge' => 'Vice President-II',
                        'name' => 'Sri. S. V. Ranga Raju',
                        'designation' => 'Vice President-II',
                        'role' => 'SRKREC Governing Body',
                        'photo' => 'assets/images/leaders/sv_ranga_raju.jpg',
                        'bio' => 'Supporting operational governance, student welfare initiatives, and educational advancement.',
                        'full_statement' => 'Nurturing student potential through practical knowledge and strong ethical foundations is our primary mission.',
                        'achievements' => [
                            'Vice President-II of Governing Body',
                            'Supported Student Welfare & Skill Enhancement Initiatives',
                            'Advocated Quality Systems & Academic Standards'
                        ]
                    ]
                ]
            ],
            'row2' => [
                'title' => 'Executive & Campus Administration',
                'badge' => 'Administration',
                'icon' => 'fas fa-university',
                'color' => '#b45309',
                'leaders' => [
                    [
                        'id' => 'secretary_nishant_varma',
                        'category' => 'row2',
                        'badge' => 'Secretary cum Correspondent',
                        'name' => 'Sri. S. R. K. Nishant Varma',
                        'designation' => 'Secretary cum Correspondent',
                        'role' => 'SRKREC Executive Association',
                        'photo' => 'assets/images/leaders/secretary_nishant_varma.jpg',
                        'bio' => 'Fostering institutional development, administrative excellence, and innovative student welfare programs at SRKREC.',
                        'full_statement' => 'Our commitment is to provide students with state-of-the-art infrastructure, world-class technical education, and vibrant opportunities to excel in global careers.',
                        'achievements' => [
                            'Executive Leadership of SRKR Engineering College Association',
                            'Expanded Modern Campus Infrastructure & Tech Suites',
                            'Promoted Industry Collaboration & Student Welfare Funds'
                        ]
                    ],
                    [
                        'id' => 'director_jagapathi_raju',
                        'category' => 'row2',
                        'badge' => 'Director',
                        'name' => 'DR. M. Jagapathi Raju',
                        'designation' => 'Director',
                        'role' => 'S.R.K.R. Engineering College',
                        'photo' => 'assets/images/leaders/director_jagapathi_raju.jpg',
                        'bio' => 'Steering strategic institutional expansion, high-impact research endeavors, and global academic affiliations.',
                        'full_statement' => 'My vision is to continuously elevate our academic standards, foster world-class research facilities, and establish global industry collaborations.',
                        'achievements' => [
                            'Over 35 Years of Academic & Administrative Leadership',
                            'Spearheaded Major Research & Infrastructure Initiatives',
                            'Fostered Global University & Corporate Partnerships'
                        ]
                    ],
                    [
                        'id' => 'principal_murali_krishnam_raju',
                        'category' => 'row2',
                        'badge' => 'Principal',
                        'name' => 'Dr. K. V. Murali Krishnam Raju',
                        'designation' => 'Principal, Ex-Officio member',
                        'role' => 'S.R.K.R. Engineering College',
                        'photo' => 'assets/images/leaders/principal_murali_krishnam_raju.jpg',
                        'bio' => 'Championing academic excellence, outcome-based education, accreditation quality, and holistic student development.',
                        'full_statement' => 'We are dedicated to cultivating an environment of intellectual curiosity, rigorous academic discipline, and hands-on technical learning.',
                        'achievements' => [
                            'Driving NAAC \'A+\' Grade Quality & Accreditation Standards',
                            'Published Numerous International Research Papers',
                            'Mentored Generations of Successful Engineering Graduates'
                        ]
                    ],
                    [
                        'id' => 'cao_dileep_chakravarthy',
                        'category' => 'row2',
                        'badge' => 'Chief Administrative Officer',
                        'name' => 'Mr. Ch. Dileep Chakravarthy',
                        'designation' => 'Chief Administrative Officer',
                        'role' => 'Campus Administration',
                        'photo' => 'assets/images/leaders/cao_dileep_chakravarthy.png',
                        'bio' => 'Managing administrative operations, student infrastructure, campus governance, and institutional efficiency.',
                        'full_statement' => 'We ensure state-of-the-art campus infrastructure, robust support services, and efficient resource allocation.',
                        'achievements' => [
                            'Overseeing 30+ Acre Campus Infrastructure & Modern Amenities',
                            'Streamlined Student Support & Administrative Operations',
                            'Fostered Campus Security, Wellness & Student Facilities'
                        ]
                    ]
                ]
            ],
            'row3' => [
                'title' => 'Governing Body Members & External Nominees',
                'badge' => 'Governing Members & Nominees',
                'icon' => 'fas fa-award',
                'color' => '#0284c7',
                'leaders' => [
                    [
                        'id' => 'gb_vijaya_narasimha_raju',
                        'category' => 'row3',
                        'badge' => 'Member, Governing Body',
                        'name' => 'Dr. K. S. Vijaya Narasimha Raju',
                        'designation' => 'Member, Governing Body',
                        'role' => 'Governing Body Representative',
                        'photo' => 'assets/images/leaders/gb_vijaya_narasimha_raju.jpg',
                        'bio' => 'Guiding institutional policy, academic governance, quality assurance, and long-term strategic initiatives.',
                        'full_statement' => 'Strong governance ensures academic rigor and continuous institutional innovation.',
                        'achievements' => [
                            'Governing Body Representative for Academic Quality Assurance',
                            'Guided Curriculum Innovations & Institutional Policies',
                            'Promoted Interdisciplinary Research & Faculty Development'
                        ]
                    ],
                    [
                        'id' => 'gb_satya_pratik_varma',
                        'category' => 'row3',
                        'badge' => 'Member, Governing Body',
                        'name' => 'Sri. S. Satya Pratik Varma',
                        'designation' => 'Member, Governing Body',
                        'role' => 'Governing Body Representative',
                        'photo' => 'assets/images/leaders/gb_satya_pratik_varma.jpg',
                        'bio' => 'Promoting technological innovation, student entrepreneurship, and campus infrastructure development.',
                        'full_statement' => 'Empowering the next generation of engineers with modern facilities and startup incubation is central to our vision.',
                        'achievements' => [
                            'Active Contributor to Governing Body Strategic Planning',
                            'Supported Startup Incubation & Tech Club Initiatives',
                            'Advocated Modern Campus Amenities & Learning Spaces'
                        ]
                    ],
                    [
                        'id' => 'gvr_prasada_raju',
                        'category' => 'row3',
                        'badge' => 'JNTUK Nominee',
                        'name' => 'Prof. G. V. R. Prasada Raju',
                        'designation' => 'JNTUK Nominee',
                        'role' => 'University Representative',
                        'photo' => 'assets/images/leaders/gvr_prasada_raju.jpg',
                        'bio' => 'Representing JNTUK on the Governing Body, driving curriculum standards and university compliance.',
                        'full_statement' => 'Ensuring alignment with university academic regulations and promoting high academic research standards.',
                        'achievements' => [
                            'JNTUK Nominee to SRKREC Governing Body',
                            'Eminent Professor & Academic Administrator',
                            'Guided University Curriculum Alignment & Standards'
                        ]
                    ],
                    [
                        'id' => 'kp_singh',
                        'category' => 'row3',
                        'badge' => 'UGC Nominee',
                        'name' => 'Dr. K. P. Singh',
                        'designation' => 'UGC Nominee',
                        'role' => 'UGC Representative',
                        'photo' => 'assets/images/leaders/kp_singh.jpg',
                        'bio' => 'UGC Nominee on the Governing Body, supporting higher education quality and research grant policy.',
                        'full_statement' => 'Fostering excellence in higher education, institutional autonomy, and university grant compliance.',
                        'achievements' => [
                            'UGC Nominee to Governing Body',
                            'Advocated Research Grants & Autonomous Accreditation',
                            'Promoted National Educational Policy Integration'
                        ]
                    ],
                    [
                        'id' => 'rjd_tech_education',
                        'category' => 'row3',
                        'badge' => 'AP Govt. Nominee',
                        'name' => 'The Regional Joint Director of Tech. Education',
                        'designation' => 'AP Govt. Nominee',
                        'role' => 'State Govt. Representative',
                        'photo' => 'assets/images/leaders/rjd_tech_education.jpg',
                        'bio' => 'AP Government Representative on the Governing Body, ensuring state technical education compliance.',
                        'full_statement' => 'Collaborating with technical institutions to promote government welfare schemes and quality technical education.',
                        'achievements' => [
                            'AP Government Technical Education Representative',
                            'Guided Statutory Regulatory Compliance & Quality Checks',
                            'Facilitated Government Student Scholarship & Welfare Schemes'
                        ]
                    ]
                ]
            ]
        ];

        function getLeaderPhotoSrc($photoPath) {
            if (!empty($photoPath)) {
                $localPath = __DIR__ . '/' . ltrim($photoPath, '/');
                if (file_exists($localPath)) {
                    $ext = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));
                    $mime = ($ext === 'png') ? 'image/png' : (($ext === 'jpg' || $ext === 'jpeg') ? 'image/jpeg' : 'image/jpeg');
                    $content = @file_get_contents($localPath);
                    if ($content !== false) {
                        return 'data:' . $mime . ';base64,' . base64_encode($content);
                    }
                }
            }
            return $photoPath;
        }

        // Flat Array & Base64 Photo Processing for JS modal and 100% reliable InfinityFree image loading
        $all_leaders_flat = [];
        foreach ($leader_rows as $row_key => &$row_data_ref) {
            foreach ($row_data_ref['leaders'] as &$leader_ref) {
                $leader_ref['photo_src'] = getLeaderPhotoSrc($leader_ref['photo']);
                $all_leaders_flat[] = $leader_ref;
            }
        }
        unset($row_data_ref, $leader_ref);
        ?>

        <!-- Render Governing Body Sections -->
        <?php foreach ($leader_rows as $row_key => $row_data): ?>
            <div class="leader-row-wrapper mb-5" data-row-category="<?php echo $row_key; ?>">
                <div class="row-category-header mb-4">
                    <h3 class="row-category-title"><i class="<?php echo $row_data['icon']; ?> me-2" style="color: <?php echo $row_data['color']; ?>;"></i> <?php echo htmlspecialchars($row_data['title']); ?></h3>
                    <span class="row-category-badge"><?php echo htmlspecialchars($row_data['badge']); ?></span>
                </div>
                <div class="row g-4 justify-content-center">
                    <?php foreach ($row_data['leaders'] as $leader): ?>
                        <div class="col-12 leader-item" data-category="<?php echo $leader['category']; ?>">
                            <div class="leader-line-card" onclick="openLeaderModal('<?php echo $leader['id']; ?>')" style="cursor: pointer;">
                                <div class="card-inner-flex">
                                    <!-- Left Details -->
                                    <div class="leader-details-left">
                                        <div class="header-meta">
                                            <span class="hod-badge"><i class="fas fa-award"></i> <?php echo htmlspecialchars($leader['badge']); ?></span>
                                        </div>
                                        <h3 class="leader-name"><?php echo htmlspecialchars($leader['name']); ?></h3>
                                        <div class="leader-designation"><?php echo htmlspecialchars($leader['designation']); ?></div>
                                        <p class="leader-about-text"><?php echo htmlspecialchars($leader['bio']); ?></p>
                                    </div>

                                    <!-- Right Photo Container -->
                                    <div class="leader-photo-right-container">
                                        <div class="leader-photo-right">
                                            <img src="<?php echo htmlspecialchars($leader['photo_src'] ?? $leader['photo']); ?>" 
                                                 alt="<?php echo htmlspecialchars($leader['name']); ?>" 
                                                 onerror="this.onerror=null; this.src='logo2.png';"
                                                 loading="lazy">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</section>

<!-- Leader Profile Modal -->
<div class="modal fade" id="leaderProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="modal-leader-header">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-flex align-items-center gap-3">
                    <img id="modalLeaderImg" src="" alt="Leader Photo" class="modal-leader-img">
                    <div>
                        <span id="modalLeaderBadge" class="badge bg-warning text-dark font-outfit fw-bold px-3 py-1 mb-1">Badge</span>
                        <h3 id="modalLeaderName" class="modal-leader-name mb-0">Leader Name</h3>
                        <div id="modalLeaderDesig" class="modal-leader-designation">Designation</div>
                        <div id="modalLeaderRole" class="modal-leader-role">Role</div>
                    </div>
                </div>
            </div>
            <div class="modal-body p-4" style="background: #ffffff;">
                <h5 class="font-outfit fw-bold text-dark mb-2"><i class="fas fa-quote-left text-warning me-2"></i> Leadership Statement</h5>
                <div class="modal-quote-box">
                    <blockquote id="modalLeaderStatement">Leadership message...</blockquote>
                </div>

                <h5 class="font-outfit fw-bold text-dark mb-3"><i class="fas fa-trophy text-warning me-2"></i> Key Contributions &amp; Achievements</h5>
                <div id="modalLeaderAchievements" class="d-flex flex-wrap gap-2">
                    <!-- Dynamic Achievement Pills -->
                </div>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-between">
                <small class="text-muted"><i class="fas fa-university me-1"></i> SRKREC CSD &amp; CSIT Department</small>
                <button type="button" class="btn btn-dark rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Leader Data Store for Modals
const leaderDataStore = <?php echo json_encode($all_leaders_flat, JSON_PRETTY_PRINT); ?>;

function openLeaderModal(leaderId) {
    const leader = leaderDataStore.find(l => l.id === leaderId);
    if (!leader) return;

    document.getElementById('modalLeaderName').textContent = leader.name;
    document.getElementById('modalLeaderDesig').textContent = leader.designation;
    document.getElementById('modalLeaderRole').textContent = leader.role;
    document.getElementById('modalLeaderBadge').textContent = leader.badge;
    document.getElementById('modalLeaderStatement').textContent = leader.full_statement;

    const modalImg = document.getElementById('modalLeaderImg');
    modalImg.src = leader.photo_src || leader.photo;

    const achievementsContainer = document.getElementById('modalLeaderAchievements');
    achievementsContainer.innerHTML = '';
    leader.achievements.forEach(ach => {
        const pill = document.createElement('div');
        pill.className = 'achievement-pill';
        pill.innerHTML = `<i class="fas fa-check-circle text-warning"></i> ${ach}`;
        achievementsContainer.appendChild(pill);
    });

    const modal = new bootstrap.Modal(document.getElementById('leaderProfileModal'));
    modal.show();
}
</script>

<?php include "footer.php"; ?>
</body>
</html>
