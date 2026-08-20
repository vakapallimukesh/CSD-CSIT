/**
 * CSD & CSIT AI Assistant Bot Engine
 * Comprehensive Website Knowledge Search & Chatbot
 */

(function () {
    'use strict';

    // Comprehensive Knowledge Base for CSD & CSIT Departments
    const knowledgeBase = [
        {
            id: "overview_programs",
            keywords: ["csd", "csit", "department", "about", "btech", "course", "programs", "degree", "intake", "computer science"],
            title: "🎓 CSD & CSIT Programs Overview",
            stats: [
                { val: "2 Programs", lbl: "B.Tech CSE & CSIT" },
                { val: "90%+", lbl: "Placement Rate" }
            ],
            content: `
                <p>The <strong>CSD (Computer Science & Design)</strong> and <strong>CSIT (Computer Science & Information Technology)</strong> departments at SRKR Engineering College deliver world-class technical education focused on innovation and industry readiness.</p>
                <ul>
                    <li><strong>B.Tech in CSD:</strong> Deep focus on Software Systems, Data Science, AI/ML, Cloud Architecture, and Software Engineering.</li>
                    <li><strong>B.Tech in CSIT:</strong> Specialized in Full-Stack Web Technologies, Cybersecurity, Networks, and Information Systems.</li>
                    <li><strong>Key Highlights:</strong> Modern curriculum, hands-on GPU lab sessions, active technical clubs, and top tier corporate placements.</li>
                </ul>
            `,
            links: [
                { text: "Explore Academics", url: "academics.php" },
                { text: "View Syllabus", url: "syllabus.php" },
                { text: "Students Info", url: "students_overview.php" }
            ]
        },
        {
            id: "alumni_network",
            keywords: ["alumni", "graduates", "alumnus", "google", "microsoft", "amazon", "meta", "carnegie", "cmu", "rahul", "sneha", "vikram", "pooja", "aditya", "ananya", "ketan", "divya", "career"],
            title: "🎓 Department Alumni Network",
            stats: [
                { val: "500+", lbl: "Total Alumni" },
                { val: "15+", lbl: "Global Sectors" }
            ],
            content: `
                <p>Our alumni excel across leading multinational tech corporations, elite research universities, and successful ventures:</p>
                <ul>
                    <li><strong>Industry Leaders:</strong> Engineers & managers at Google, Microsoft, Amazon AWS, Meta (Facebook), Qualcomm, and TCS Innovation Labs.</li>
                    <li><strong>Higher Education:</strong> Scholars at Carnegie Mellon University (CMU) and top international research institutes.</li>
                    <li><strong>Entrepreneurs:</strong> Co-founders leading Series-A funded robotics and AI startups.</li>
                </ul>
            `,
            links: [
                { text: "Alumni Directory", url: "alumni.php" },
                { text: "Placements Overview", url: "placements.php" }
            ]
        },
        {
            id: "faculty_leadership",
            keywords: ["faculty", "hod", "head", "professors", "teachers", "staff", "guide", "appreciation", "mentors", "suresh", "babu"],
            title: "👨‍🏫 Faculty & Department Leadership",
            stats: [
                { val: "Dr. M. Suresh Babu", lbl: "HOD CSD & CSIT" },
                { val: "30+", lbl: "Expert Faculty" }
            ],
            content: `
                <p>Our departments are guided by highly qualified professors and industry-experienced faculty members dedicated to teaching and research excellence:</p>
                <ul>
                    <li><strong>Head of Department (HOD):</strong> Dr. M. Suresh Babu leads academic strategy, research grants, and industry partnerships.</li>
                    <li><strong>Specializations:</strong> AI/ML, Data Science, Cybersecurity, Cloud Infrastructure, IoT, and Software Engineering.</li>
                    <li><strong>Faculty Appreciations:</strong> Awards for research publications, patents, and exceptional teaching quality.</li>
                </ul>
            `,
            links: [
                { text: "Faculty Directory", url: "faculty.php" },
                { text: "HOD Dashboard", url: "hod_dashboard.php" }
            ]
        },
        {
            id: "academic_calendar",
            keywords: ["calendar", "academic calendar", "mid exam", "internal exam", "end exam", "holidays", "dasara", "pongal", "schedule", "timetable"],
            title: "📅 Academic Calendar & Semester Schedules",
            stats: [
                { val: "2026–2027", lbl: "Academic Year" },
                { val: "Official PDF", lbl: "Schedule Available" }
            ],
            content: `
                <p>Official II B.Tech (CSD & CSIT) semester schedules, mid-term dates, and holiday lists:</p>
                <ul>
                    <li><strong>Semester I Commencement:</strong> 20.07.2026 | <strong>I Mid:</strong> 15.09.2026 – 17.09.2026</li>
                    <li><strong>Dasara Holidays:</strong> 19.10.2026 – 24.10.2026 | <strong>End Exams:</strong> 30.11.2026 – 12.12.2026</li>
                    <li><strong>Semester II Commencement:</strong> 14.12.2026 | <strong>Pongal Holidays:</strong> 11.01.2027 – 16.01.2027</li>
                </ul>
            `,
            links: [
                { text: "Academic Calendar", url: "academic-calendar.php" },
                { text: "Syllabus & Model Papers", url: "syllabus.php" }
            ]
        },
        {
            id: "aiml_labs",
            keywords: ["lab", "labs", "ai lab", "ml lab", "gpu", "infrastructure", "ai & ml", "computers", "equipment", "ai-ml-lab"],
            title: "🔬 Advanced AI & Machine Learning Lab",
            stats: [
                { val: "NVIDIA GPUs", lbl: "High Performance" },
                { val: "100+ Workstations", lbl: "Lab Capacity" }
            ],
            content: `
                <p>The state-of-the-art <strong>AI & ML Research Lab</strong> is equipped for deep learning model training, computer vision pipelines, and NLP tasks:</p>
                <ul>
                    <li><strong>Hardware:</strong> High-end NVIDIA RTX GPU Workstations with 64GB+ RAM.</li>
                    <li><strong>Software Stack:</strong> PyTorch, TensorFlow, CUDA toolkit, Anaconda, Jupyter Hub, OpenCV, and ROS.</li>
                    <li><strong>Student Projects:</strong> Autonomous systems, medical image diagnostics, LLM fine-tuning, and smart surveillance.</li>
                </ul>
            `,
            links: [
                { text: "AI & ML Lab Details", url: "ai-ml-lab.php" },
                { text: "Explore All Labs", url: "explore.php" }
            ]
        },
        {
            id: "house_system",
            keywords: ["house", "houses", "aakash", "agni", "jal", "prithvi", "prudhvi", "points", "leaderboard", "shield", "least house points"],
            title: "🛡️ Department House System",
            stats: [
                { val: "4 Houses", lbl: "Aakash, Agni, Jal, Prithvi" },
                { val: "Annual Shield", lbl: "Championship Trophy" }
            ],
            content: `
                <p>The student body is divided into 4 prestigious Houses to foster healthy competition, teamwork, and leadership:</p>
                <ul>
                    <li><strong style="color:#0284c7;">Aakash (Sky Blue):</strong> Vision, ambition, and cloud innovation.</li>
                    <li><strong style="color:#ef4444;">Agni (Fire Red):</strong> Passion, competitive coding, and energy.</li>
                    <li><strong style="color:#06b6d4;">Jal (Ocean Blue):</strong> Depth of knowledge, fluidity, and teamwork.</li>
                    <li><strong style="color:#10b981;">Prithvi (Earth Green):</strong> Strength, stability, and open-source contributions.</li>
                </ul>
                <p>Points are awarded for attendance, coding contests, sports, hackathons, and cultural competitions.</p>
            `,
            links: [
                { text: "House Leaderboard", url: "houses_dashboard.php" },
                { text: "Section House Points", url: "section_house_points_detail.php" }
            ]
        },
        {
            id: "student_clubs",
            keywords: ["club", "clubs", "sdc", "startup", "swecha", "activities", "events", "coding club"],
            title: "🚀 Student Technical & Innovation Clubs",
            stats: [
                { val: "3 Active Clubs", lbl: "Student-Led" },
                { val: "30+ Events", lbl: "Hosted Yearly" }
            ],
            content: `
                <p>CSD & CSIT feature vibrant student-led clubs encouraging practical development and innovation:</p>
                <ul>
                    <li><strong>SDC (Software Dev Club):</strong> Building real-world web/mobile applications for campus automation.</li>
                    <li><strong>Startup & Innovation Club:</strong> Entrepreneurship, venture ideation, and hackathons.</li>
                    <li><strong>Swecha Club:</strong> Promoting Free and Open Source Software (FOSS), Linux kernel workshops, and open technology.</li>
                </ul>
            `,
            links: [
                { text: "SDC Club", url: "sdc_club.php" },
                { text: "Startup Club", url: "startup_club.php" },
                { text: "Swecha Club", url: "swecha_club.php" }
            ]
        },
        {
            id: "placements_careers",
            keywords: ["placement", "placements", "jobs", "package", "salary", "companies", "recruiters", "amazon", "tcs", "hiring", "offers"],
            title: "🏆 Placements & Career Success",
            stats: [
                { val: "44+ LPA", lbl: "Highest Package" },
                { val: "6.5 LPA", lbl: "Average Package" }
            ],
            content: `
                <p>Our students achieve outstanding placements across leading global tech enterprises and high-growth startups:</p>
                <ul>
                    <li><strong>Top Recruiters:</strong> Amazon AWS, TCS Digital, Microsoft, Infosys, Wipro, Accenture, Cognizant, Virtusa, Hexaware, and Tech Mahindra.</li>
                    <li><strong>Preparation Program:</strong> Dedicated mock technical interviews, aptitude training, and coding bootcamps starting from 3rd year.</li>
                </ul>
            `,
            links: [
                { text: "Placement Overview", url: "placements.php" },
                { text: "Internships Details", url: "internships.php" }
            ]
        },
        {
            id: "attendance_portals",
            keywords: ["attendance", "portal", "login", "marks", "leave", "student login", "calendar", "timetable", "check attendance"],
            title: "📊 Attendance & Student Portals",
            stats: [
                { val: "Real-Time", lbl: "Attendance Tracking" },
                { val: "24/7", lbl: "Portal Availability" }
            ],
            content: `
                <p>Students and faculty can seamlessly track attendance, manage leave applications, and view section timetables:</p>
                <ul>
                    <li><strong>Attendance Entry & Tracking:</strong> Real-time subject-wise and monthly attendance reports.</li>
                    <li><strong>Leave Management:</strong> Online leave application submission with instant faculty/HOD approvals.</li>
                    <li><strong>Academic Calendar:</strong> Timetables, exam schedules, and holiday notifications.</li>
                </ul>
            `,
            links: [
                { text: "Check Attendance", url: "check_attendance.php" },
                { text: "Student Login", url: "login.php" },
                { text: "Academic Calendar", url: "academic-calendar.php" }
            ]
        }
    ];

    // UI Controller Class
    class AIBotController {
        constructor() {
            this.triggerBtn = document.getElementById('aiBotTrigger');
            this.modal = document.getElementById('aiBotModal');
            this.closeBtn = document.getElementById('aiBotClose');
            this.clearBtn = document.getElementById('aiBotClear');
            this.chatBody = document.getElementById('aiBotBody');
            this.searchForm = document.getElementById('aiSearchForm');
            this.searchInput = document.getElementById('aiSearchInput');

            this.isOpen = false;
            this.initEvents();
        }

        initEvents() {
            if (!this.triggerBtn || !this.modal) return;

            // Toggle modal
            this.triggerBtn.addEventListener('click', () => this.toggleModal());
            if (this.closeBtn) this.closeBtn.addEventListener('click', () => this.closeModal());
            if (this.clearBtn) this.clearBtn.addEventListener('click', () => this.clearChat());

            // Handle search submit
            if (this.searchForm) {
                this.searchForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleUserQuery();
                });
            }

            // Quick chip clicks delegate
            document.addEventListener('click', (e) => {
                const chip = e.target.closest('.ai-chip');
                if (chip && chip.dataset.query) {
                    this.searchInput.value = chip.dataset.query;
                    this.handleUserQuery();
                }
            });
        }

        toggleModal() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.modal.classList.add('active');
                this.searchInput.focus();
            } else {
                this.modal.classList.remove('active');
            }
        }

        closeModal() {
            this.isOpen = false;
            this.modal.classList.remove('active');
        }

        clearChat() {
            const welcome = this.chatBody.querySelector('.ai-msg-welcome');
            this.chatBody.innerHTML = '';
            if (welcome) {
                this.chatBody.appendChild(welcome);
            } else {
                this.addWelcomeMessage();
            }
        }

        addWelcomeMessage() {
            const welcomeHTML = `
                <div class="ai-msg bot ai-msg-welcome">
                    <div class="ai-msg-bubble">
                        <h6>🤖 Welcome to CSD & CSIT AI Search Assistant!</h6>
                        <p>Ask me anything about CSD & CSIT programs, faculty, alumni, placement stats, house points, academic calendar, or student clubs!</p>
                        <div class="ai-quick-prompts">
                            <span class="ai-quick-title">Quick Topics:</span>
                            <div class="ai-chips-wrapper">
                                <button class="ai-chip" data-query="CSD CSIT BTech Programs">🎓 Programs Offered</button>
                                <button class="ai-chip" data-query="Department Alumni Network">🎓 Alumni Network</button>
                                <button class="ai-chip" data-query="Faculty HOD Details">👨‍🏫 Faculty & HOD</button>
                                <button class="ai-chip" data-query="Placements Packages Recruiters">🏆 Placement Stats</button>
                                <button class="ai-chip" data-query="Academic Calendar Exam Dates">📅 Academic Calendar</button>
                                <button class="ai-chip" data-query="House System Points Leaderboard">🛡️ House System</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            this.chatBody.innerHTML = welcomeHTML;
        }

        async handleUserQuery() {
            const queryText = this.searchInput.value.trim();
            if (!queryText) return;

            // Render User Message
            this.appendUserMessage(queryText);
            this.searchInput.value = '';

            // Show Typing Indicator
            const typingId = this.showTypingIndicator();

            try {
                // Fetch live database search from PHP API
                const res = await fetch('api/ai_search.php?q=' + encodeURIComponent(queryText));
                const dbData = await res.json();

                this.removeTypingIndicator(typingId);

                if (dbData && dbData.success) {
                    this.appendBotResponse(dbData);
                } else {
                    // Fallback to local knowledge base matching
                    const fallbackMatch = this.findBestMatch(queryText);
                    this.appendBotResponse(fallbackMatch);
                }
            } catch (err) {
                console.warn("Live DB Search error, using fallback matching:", err);
                this.removeTypingIndicator(typingId);
                const fallbackMatch = this.findBestMatch(queryText);
                this.appendBotResponse(fallbackMatch);
            }
        }

        appendUserMessage(text) {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'ai-msg user';
            msgDiv.innerHTML = `
                <div class="ai-msg-bubble">
                    ${this.escapeHTML(text)}
                </div>
            `;
            this.chatBody.appendChild(msgDiv);
            this.scrollToBottom();
        }

        showTypingIndicator() {
            const typingId = 'typing_' + Date.now();
            const typingDiv = document.createElement('div');
            typingDiv.className = 'ai-msg bot';
            typingDiv.id = typingId;
            typingDiv.innerHTML = `
                <div class="ai-msg-bubble">
                    <div class="ai-typing-indicator">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            `;
            this.chatBody.appendChild(typingDiv);
            this.scrollToBottom();
            return typingId;
        }

        removeTypingIndicator(id) {
            const elem = document.getElementById(id);
            if (elem) elem.remove();
        }

        findBestMatch(query) {
            const lowerQuery = query.toLowerCase();
            const tokens = lowerQuery.split(/\s+/).filter(t => t.length > 2);

            let bestMatch = null;
            let highestScore = 0;

            knowledgeBase.forEach(item => {
                let score = 0;
                item.keywords.forEach(kw => {
                    if (lowerQuery.includes(kw)) {
                        score += 3;
                    }
                    tokens.forEach(tok => {
                        if (kw.includes(tok)) score += 1;
                    });
                });

                if (score > highestScore) {
                    highestScore = score;
                    bestMatch = item;
                }
            });

            if (bestMatch && highestScore > 0) {
                return bestMatch;
            }

            // Standard Fallback Response
            return {
                title: "🔍 Database & Knowledge Search",
                stats: [
                    { val: "Search Assistant", lbl: "SRKREC CSD & CSIT" }
                ],
                content: `
                    <p>No direct matching record found for <em>"${this.escapeHTML(query)}"</em>.</p>
                    <p>You can search for any of the following topics:</p>
                    <ul>
                        <li><strong>Students & House Info:</strong> Roll numbers (e.g. <em>"24B91A0749"</em>), student names, or <em>"least house points"</em></li>
                        <li><strong>Alumni & Careers:</strong> e.g., <em>"alumni"</em>, <em>"Google"</em>, <em>"Rahul Kumar"</em>, <em>"placements"</em></li>
                        <li><strong>Faculty & HOD:</strong> e.g., <em>"Suresh Babu"</em>, <em>"HOD"</em>, <em>"CSD Faculty"</em></li>
                        <li><strong>Academic Calendar:</strong> e.g., <em>"academic calendar"</em>, <em>"mid exam dates"</em>, <em>"syllabus"</em></li>
                        <li><strong>Clubs & Events:</strong> e.g., <em>"SDC Club"</em>, <em>"Startup Club"</em>, <em>"Jaitra 2k26"</em></li>
                    </ul>
                `,
                links: [
                    { text: "Explore Academics", url: "academics.php" },
                    { text: "Alumni Directory", url: "alumni.php" },
                    { text: "Faculty Directory", url: "faculty.php" },
                    { text: "Students Info", url: "students_overview.php" }
                ]
            };
        }

        appendBotResponse(item) {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'ai-msg bot';

            let statsHTML = '';
            if (item.stats && item.stats.length > 0) {
                statsHTML = `
                    <div class="ai-stat-grid">
                        ${item.stats.map(s => `
                            <div class="ai-stat-card">
                                <div class="val">${s.val}</div>
                                <div class="lbl">${s.lbl}</div>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            let linksHTML = '';
            if (item.links && item.links.length > 0) {
                linksHTML = `
                    <div class="ai-links-container">
                        ${item.links.map(l => `
                            <a href="${l.url}" class="ai-action-btn">
                                <i class="fas fa-arrow-right"></i> ${l.text}
                            </a>
                        `).join('')}
                    </div>
                `;
            }

            let dbBadgeHTML = (item.source === 'live_db') ? `<span style="font-size: 10px; background: rgba(16,185,129,0.2); color: #10b981; border: 1px solid rgba(16,185,129,0.4); padding: 2px 8px; border-radius: 10px; margin-left: 6px; font-weight: 600; vertical-align: middle;">Live Database</span>` : '';

            msgDiv.innerHTML = `
                <div class="ai-msg-bubble">
                    <h6>${item.title} ${dbBadgeHTML}</h6>
                    ${statsHTML}
                    ${item.content}
                    ${linksHTML}
                </div>
            `;

            this.chatBody.appendChild(msgDiv);
            this.scrollToBottom();
        }

        scrollToBottom() {
            this.chatBody.scrollTop = this.chatBody.scrollHeight;
        }

        escapeHTML(str) {
            return str.replace(/[&<>'"]/g, 
                tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                }[tag] || tag)
            );
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => new AIBotController());
    } else {
        new AIBotController();
    }

})();
