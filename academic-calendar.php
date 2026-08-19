<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'head.php'; 
?>

<style>
:root {
    --primary: #d97706;
    --primary-light: #f59e0b;
    --amber-gold: #d97706;
    --bright-yellow: #f59e0b;
    --golden-champagne: #e6c280;
    --amber-badge: #b45309;
    --rich-espresso: #1a0d06;
    --cream-white: #fdfbf7;
    --text-primary: #1a0d06;
    --text-secondary: #6f5f54;
    --border-light: #f3eae1;
}

body {
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    background: #fdfbf7;
    color: #1a0d06;
    overflow-x: hidden;
}

/* Placement Theme Hero Section */
.hero-section {
    background: linear-gradient(135deg, #1a0d06 0%, #2a150a 50%, #3d1e0e 100%);
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
    background-image: radial-gradient(rgba(230, 194, 128, 0.15) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.6;
    pointer-events: none;
}

@keyframes floatCalendar {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(3deg); }
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
    border: 1px solid rgba(230, 194, 128, 0.3);
    animation: floatCalendar 6s ease-in-out infinite;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.calendar-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 30px;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(180, 83, 9, 0.06);
    border: 1px solid #f3eae1;
    transition: all 0.35s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.calendar-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(217, 119, 6, 0.15);
    border-color: rgba(217, 119, 6, 0.3);
}

.download-btn {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: #ffffff;
    font-weight: 700;
    padding: 12px 24px;
    border-radius: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 6px 16px rgba(180, 83, 9, 0.25);
}

.download-btn:hover {
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(180, 83, 9, 0.4);
}
</style>

<body>
    <?php include 'nav.php'; ?>

    <!-- Animated Hero Section -->
    <section class="hero-section">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span style="color: #fbbf24; background: rgba(251, 191, 36, 0.15); font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.82rem; display: inline-block; padding: 6px 16px; border-radius: 99px; margin-bottom: 16px; border: 1px solid rgba(251, 191, 36, 0.3);">
                        <i class="fas fa-calendar-check" style="margin-right: 6px;"></i>Official Schedule
                    </span>
                    <h1 style="font-family: 'Outfit', sans-serif; font-size: 3.4rem; font-weight: 900; margin-bottom: 15px; color: #ffffff; line-height: 1.15;">Academic Calendar</h1>
                    <p style="font-size: 1.25rem; opacity: 0.92; color: #e5d5c5; max-width: 650px;">Official academic schedules, semester start dates, examination schedules, and holidays for 2025-26.</p>
                </div>
                <div class="col-md-4 text-center d-none d-md-block">
                    <div class="hero-icon-container">
                        <i class="fas fa-calendar-alt" style="font-size: 60px; color: #fbbf24; filter: drop-shadow(0 0 15px rgba(251, 191, 36, 0.6));"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #1a0d06; margin: 0;">Academic Calendar (2026–2027)</h2>
            <a href="academics.php" class="btn btn-warning px-4 py-2" style="border-radius: 14px; font-weight: 700; background: #d97706; border-color: #d97706; color: white;">
                <i class="fas fa-graduation-cap me-2"></i>All Academics Overview
            </a>
        </div>

        <!-- ACADEMIC CALENDAR DECK CARDS (CURRENT 2026-27 AT TOP) -->
        <div class="row g-4 mb-5">
            <!-- FEATURED DECK: Academic Calendar (2026–2027) -->
            <div class="col-12">
                <div style="background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%); border-radius: 20px; padding: 26px 30px; border: 2px solid #e6c280; box-shadow: 0 10px 30px rgba(180, 83, 9, 0.1); display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 58px; height: 58px; border-radius: 18px; background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; box-shadow: 0 6px 16px rgba(180, 83, 9, 0.25);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <span style="background: #1a0d06; color: #f59e0b; font-size: 0.75rem; font-weight: 800; padding: 3px 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-bottom: 4px;">
                                <i class="fas fa-star me-1"></i> Current Academic Year 2026–2027
                            </span>
                            <h4 style="font-family: 'Outfit', sans-serif; font-weight: 900; color: #1a0d06; margin: 0 0 2px 0; font-size: 1.4rem;">Academic Calendar (2026–2027)</h4>
                            <span style="font-size: 0.9rem; color: #6f5f54; font-weight: 500;">Official II B.Tech (CSD & CSIT) semester schedules, mid-term dates, marks deadlines & holiday list</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="#detailed-2026-calendar" class="btn btn-outline-dark" style="border-radius: 14px; font-weight: 700; padding: 10px 20px; font-size: 0.88rem; border-color: #1a0d06;" onclick="document.getElementById('detailed-2026-calendar').scrollIntoView({behavior: 'smooth'}); return false;">
                            <i class="fas fa-table me-1"></i> View Schedule
                        </a>
                        <a href="files/2_btech_ac.pdf" class="download-btn" target="_blank" style="padding: 10px 20px; font-size: 0.88rem;">
                            <i class="fas fa-download me-1"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2026-2027 II B.Tech Detailed Schedule Deck Card -->
        <div id="detailed-2026-calendar" style="background: #ffffff; border-radius: 24px; padding: 32px; border: 2px solid #e6c280; margin-bottom: 40px; box-shadow: 0 12px 35px rgba(180, 83, 9, 0.08);">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3" style="border-bottom: 2px solid #f3eae1;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 56px; height: 56px; border-radius: 18px; background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; box-shadow: 0 6px 16px rgba(180, 83, 9, 0.25);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <span style="color: #b45309; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Official Schedule Deck</span>
                        <h3 style="font-family: 'Outfit', sans-serif; font-weight: 900; color: #1a0d06; margin: 2px 0 0 0; font-size: 1.6rem;">Academic Calendar (2026–2027)</h3>
                        <span style="color: #6f5f54; font-size: 0.92rem; font-weight: 600;">S.R.K.R. Engineering College (Autonomous): Bhimavaram — II B.Tech (CSD & CSIT)</span>
                    </div>
                </div>
                <span style="background: #1a0d06; color: #f59e0b; font-size: 0.85rem; font-weight: 700; padding: 8px 18px; border-radius: 50px; border: 1px solid rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-star me-1"></i> Academic Year 2026-2027
                </span>
            </div>

            <!-- I SEMESTER & II SEMESTER TABLES -->
            <div class="row g-4 mb-4">
                <!-- I SEMESTER -->
                <div class="col-lg-6">
                    <div style="background: #fdfbf7; border-radius: 18px; padding: 20px; border: 1px solid #f3eae1; height: 100%;">
                        <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #d97706; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
                            <span><i class="fas fa-bookmark me-2"></i>I SEMESTER</span>
                            <span style="font-size: 0.82rem; background: #fffbeb; color: #b45309; padding: 4px 10px; border-radius: 8px; border: 1px solid #fde68a;">Starts: 20.07.2026</span>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" style="font-size: 0.88rem;">
                                <thead style="background: #1a0d06; color: #ffffff;">
                                    <tr>
                                        <th>Description</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="fw-bold" style="background: #fffbeb;">
                                        <td>Commencement of Class Work</td>
                                        <td colspan="3" class="text-center text-primary">20.07.2026</td>
                                    </tr>
                                    <tr>
                                        <td>I Unit of Instructions</td>
                                        <td>20.07.2026</td>
                                        <td>12.09.2026</td>
                                        <td>8 Weeks</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>I Internal Examinations</strong></td>
                                        <td>15.09.2026</td>
                                        <td>17.09.2026</td>
                                        <td>3 Days</td>
                                    </tr>
                                    <tr>
                                        <td>II Unit of Instructions (Part 1)</td>
                                        <td>18.09.2026</td>
                                        <td>17.10.2026</td>
                                        <td>4 ½ Weeks</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td><strong>Dasara Holidays</strong></td>
                                        <td><strong>19.10.2026</strong></td>
                                        <td><strong>24.10.2026</strong></td>
                                        <td><strong>1 Week</strong></td>
                                    </tr>
                                    <tr>
                                        <td>II Unit of Instructions (Part 2)</td>
                                        <td>26.10.2026</td>
                                        <td>18.11.2026</td>
                                        <td>3 ½ Weeks</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>II Internal Examinations</strong></td>
                                        <td>19.11.2026</td>
                                        <td>21.11.2026</td>
                                        <td>3 Days</td>
                                    </tr>
                                    <tr>
                                        <td>Preparation & Practicals</td>
                                        <td>23.11.2026</td>
                                        <td>28.11.2026</td>
                                        <td>1 Week</td>
                                    </tr>
                                    <tr class="table-success fw-bold">
                                        <td>End Examinations</td>
                                        <td>30.11.2026</td>
                                        <td>12.12.2026</td>
                                        <td>2 Weeks</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- II SEMESTER -->
                <div class="col-lg-6">
                    <div style="background: #fdfbf7; border-radius: 18px; padding: 20px; border: 1px solid #f3eae1; height: 100%;">
                        <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #d97706; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
                            <span><i class="fas fa-bookmark me-2"></i>II SEMESTER</span>
                            <span style="font-size: 0.82rem; background: #fffbeb; color: #b45309; padding: 4px 10px; border-radius: 8px; border: 1px solid #fde68a;">Starts: 14.12.2026</span>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" style="font-size: 0.88rem;">
                                <thead style="background: #1a0d06; color: #ffffff;">
                                    <tr>
                                        <th>Description</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="fw-bold" style="background: #fffbeb;">
                                        <td>Commencement of Class Work</td>
                                        <td colspan="3" class="text-center text-primary">14.12.2026</td>
                                    </tr>
                                    <tr>
                                        <td>I Unit of Instructions (Part 1)</td>
                                        <td>14.12.2026</td>
                                        <td>09.01.2027</td>
                                        <td>4 Weeks</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td><strong>Pongal Holidays</strong></td>
                                        <td><strong>11.01.2027</strong></td>
                                        <td><strong>16.01.2027</strong></td>
                                        <td><strong>1 Week</strong></td>
                                    </tr>
                                    <tr>
                                        <td>I Unit of Instructions (Part 2)</td>
                                        <td>18.01.2027</td>
                                        <td>13.02.2027</td>
                                        <td>4 Weeks</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>I Internal Examinations</strong></td>
                                        <td>15.02.2027</td>
                                        <td>17.02.2027</td>
                                        <td>3 Days</td>
                                    </tr>
                                    <tr>
                                        <td>II Unit of Instructions</td>
                                        <td>18.02.2027</td>
                                        <td>12.04.2027</td>
                                        <td>8 Weeks</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>II Internal Examinations</strong></td>
                                        <td>13.04.2027</td>
                                        <td>17.04.2027</td>
                                        <td>3 Days</td>
                                    </tr>
                                    <tr>
                                        <td>Preparation & Practicals</td>
                                        <td>19.04.2027</td>
                                        <td>24.04.2027</td>
                                        <td>1 Week</td>
                                    </tr>
                                    <tr class="table-success fw-bold">
                                        <td>End Examinations</td>
                                        <td>26.04.2027</td>
                                        <td>08.05.2027</td>
                                        <td>2 Weeks</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td><strong>Community Service Project (CSP)</strong></td>
                                        <td>10.05.2027</td>
                                        <td>03.07.2027</td>
                                        <td>8 Weeks</td>
                                    </tr>
                                    <tr class="table-dark text-warning fw-bold">
                                        <td>Commencement of III Year I Sem (2027-28)</td>
                                        <td colspan="3" class="text-center">05.07.2027</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MARKS SUBMISSION DEADLINES -->
            <div class="mb-4">
                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #1a0d06; margin-bottom: 15px;">
                    <i class="fas fa-clock text-warning me-2"></i>Submission of Mid / Internal Marks (II B.Tech)
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center" style="font-size: 0.9rem;">
                        <thead style="background: #2a150a; color: #ffffff;">
                            <tr>
                                <th>Year</th>
                                <th>Semester</th>
                                <th>I Mid Marks Deadline</th>
                                <th>II Mid Marks Deadline</th>
                                <th>Internal Marks Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold" rowspan="2" style="vertical-align: middle; background: #fffbeb;">II Year B.Tech</td>
                                <td class="fw-bold">I Semester</td>
                                <td><span class="badge bg-warning text-dark px-3 py-2">21.09.2026</span></td>
                                <td><span class="badge bg-warning text-dark px-3 py-2">24.11.2026</span></td>
                                <td><span class="badge bg-success px-3 py-2">26.11.2026</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">II Semester</td>
                                <td><span class="badge bg-warning text-dark px-3 py-2">20.02.2027</span></td>
                                <td><span class="badge bg-warning text-dark px-3 py-2">20.04.2027</span></td>
                                <td><span class="badge bg-success px-3 py-2">22.04.2027</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- HOLIDAY LIST & WORKING DAYS -->
            <div>
                <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #1a0d06; margin-bottom: 15px;">
                    <i class="fas fa-umbrella-beach text-warning me-2"></i>Monthly Holidays & Working Days (2026–2027)
                </h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle" style="font-size: 0.88rem;">
                        <thead style="background: #1a0d06; color: #ffffff;">
                            <tr>
                                <th style="width: 15%;">Month</th>
                                <th style="width: 70%;">List of Holidays</th>
                                <th style="width: 15%; text-align: center;">Working Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="fw-bold">July 2026</td><td>5, 12, 19 &amp; 26 Sundays.</td><td class="text-center fw-bold text-success">27</td></tr>
                            <tr><td class="fw-bold">August 2026</td><td>2, 9, 16, 23 &amp; 30 Sundays, 15<sup>th</sup> Independence Day, 26<sup>th</sup> Milad Nabi.</td><td class="text-center fw-bold text-success">24</td></tr>
                            <tr><td class="fw-bold">September 2026</td><td>6, 13, 20 &amp; 27 Sundays, 4<sup>th</sup> Janmashtami, 14<sup>th</sup> Ganesh Chaturthi.</td><td class="text-center fw-bold text-success">24</td></tr>
                            <tr><td class="fw-bold">October 2026</td><td>4, 11, 18 &amp; 25 Sundays, 2<sup>nd</sup> Gandhi Jayanthi, 19<sup>th</sup> Durgashtami, 20<sup>th</sup> Maharnavami &amp; Vijayadasami.</td><td class="text-center fw-bold text-success">24</td></tr>
                            <tr><td class="fw-bold">November 2026</td><td>1, 8, 15, 22 &amp; 29 Sundays, 8<sup>th</sup> Diwali.</td><td class="text-center fw-bold text-success">25</td></tr>
                            <tr><td class="fw-bold">December 2026</td><td>6, 13, 20 &amp; 27 Sundays, 25<sup>th</sup> Christmas.</td><td class="text-center fw-bold text-success">26</td></tr>
                            <tr><td class="fw-bold">January 2027</td><td>3, 10, 17, 24 &amp; 31 Sundays, 1<sup>st</sup> New Year Day, 14<sup>th</sup> Bhogi, 15<sup>th</sup> Makara Sankranti, 16<sup>th</sup> Kanuma, 26<sup>th</sup> Republic Day.</td><td class="text-center fw-bold text-success">21</td></tr>
                            <tr><td class="fw-bold">February 2027</td><td>7, 14, 21 &amp; 28 Sundays.</td><td class="text-center fw-bold text-success">24</td></tr>
                            <tr><td class="fw-bold">March 2027</td><td>7, 14, 21 &amp; 28 Sundays, 6<sup>th</sup> Maha Shivaratri, 10<sup>th</sup> Ramzan, 22<sup>nd</sup> Holi, 26<sup>th</sup> Good Friday.</td><td class="text-center fw-bold text-success">23</td></tr>
                            <tr><td class="fw-bold">April 2027</td><td>4, 11, 18 &amp; 25 Sundays, 5<sup>th</sup> Jagajeevan Ram Jayanthi, 7<sup>th</sup> Ugadi, 14<sup>th</sup> Dr. B.R. Ambedkar Jayanthi, 15<sup>th</sup> Sriramanavami.</td><td class="text-center fw-bold text-success">22</td></tr>
                            <tr><td class="fw-bold">May 2027</td><td>2, 9, 16, 23 &amp; 30 Sundays, 15<sup>th</sup> Bakrid.</td><td class="text-center fw-bold text-success">25</td></tr>
                            <tr><td class="fw-bold">June 2027</td><td>6, 13, 20 &amp; 27 Sundays, 16<sup>th</sup> Moharam.</td><td class="text-center fw-bold text-success">25</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <h3 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #1a0d06; margin-bottom: 20px;">PDF Schedule Downloads</h3>
        <div class="row">
            <div class="col-12">
                <div class="calendar-card">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 16px; background: rgba(217, 119, 6, 0.12); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #1a0d06; margin: 0;">2026-2027 Academic Calendar for 2nd Years</h5>
                            <span style="font-size: 0.88rem; color: #6f5f54;">Official schedule &amp; holiday list for II B.Tech (2026-27)</span>
                        </div>
                    </div>
                    <a href="files/II_B.Tech_Academic_Calendar_2026-27.pdf" class="download-btn" target="_blank">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                </div>
            </div>

            <div class="col-12">
                <div class="calendar-card">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 16px; background: rgba(217, 119, 6, 0.12); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #1a0d06; margin: 0;">III &amp; IV B.Tech Academic Calendar 2025-26</h5>
                            <span style="font-size: 0.88rem; color: #6f5f54;">Official schedule for 3rd and 4th year students</span>
                        </div>
                    </div>
                    <a href="files/IV_B.Tech_Academic_Calendar_2025-26.pdf" class="download-btn" target="_blank">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
