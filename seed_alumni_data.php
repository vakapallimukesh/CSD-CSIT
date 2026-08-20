<?php
// Seeder script to insert sample CSD & CSIT alumni into database `new_sem`
include './connect.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "<h3>Seeding Alumni Data into MySQL database 'new_sem'...</h3>";

// Reset alumni_employment_history table
@mysqli_query($conn, "TRUNCATE TABLE alumni_employment_history");

// Sample Alumni List
$sample_alumni = [
    [
        'student_id' => '21B91A6201',
        'name' => 'Rahul Kumar',
        'email' => 'rahul.kumar.alumni@srkrec.ac.in',
        'branch' => 'CSD',
        'batch' => '2022',
        'program' => 'B.Tech',
        'company' => 'Google',
        'designation' => 'Senior Software Engineer',
        'location' => 'Bengaluru, India',
        'industry' => 'Software & Tech',
        'description' => 'Architecting scalable cloud microservices & distributed computer vision pipelines.',
        'achievements' => 'Led 3 patents in cloud optimization, Keynote speaker at Google I/O extended.',
        'is_notable' => 1,
        'testimonial' => 'The solid foundation in algorithms and software engineering at CSD prepared me to handle large-scale global software systems at Google.'
    ],
    [
        'student_id' => '21B91A6202',
        'name' => 'Sneha Verma',
        'email' => 'sneha.verma.alumni@srkrec.ac.in',
        'branch' => 'CSIT',
        'batch' => '2023',
        'program' => 'B.Tech',
        'company' => 'Microsoft',
        'designation' => 'AI Research Engineer',
        'location' => 'Hyderabad, India',
        'industry' => 'AI & Machine Learning',
        'description' => 'Developing generative AI models and natural language processing pipelines for Azure AI services.',
        'achievements' => 'Published 2 research papers in IEEE AI conferences, Microsoft Innovator Excellence Award 2024.',
        'is_notable' => 1,
        'testimonial' => 'Mentorship from department professors and hands-on lab practicals ignited my passion for Artificial Intelligence.'
    ],
    [
        'student_id' => '20B91A6203',
        'name' => 'Vikramaditya Raju',
        'email' => 'vikram.raju@srkrec.ac.in',
        'branch' => 'CSIT',
        'batch' => '2021',
        'program' => 'B.Tech',
        'company' => 'NexGen Robotics & AI Labs',
        'designation' => 'Co-Founder & CTO',
        'location' => 'Bengaluru, India',
        'industry' => 'Entrepreneurship',
        'description' => 'Building autonomous warehouse robotics systems backed by top tech venture funds.',
        'achievements' => 'Raised $2.5M Series-A funding, Featured in Forbes 30 Under 30 Tech Entrepreneurs.',
        'is_notable' => 1,
        'testimonial' => 'Starting our project in the department Startup Club gave us the confidence to build a tech startup that now employs over 40 engineers.'
    ],
    [
        'student_id' => '21B91A6204',
        'name' => 'Pooja Varma',
        'email' => 'pooja.varma@srkrec.ac.in',
        'branch' => 'CSD',
        'batch' => '2023',
        'program' => 'B.Tech',
        'company' => 'Carnegie Mellon University',
        'designation' => 'MS / PhD Research Scholar',
        'location' => 'Pittsburgh, USA',
        'industry' => 'Higher Studies',
        'description' => 'Conducting cutting-edge research on privacy-preserving machine learning and federated intelligence.',
        'achievements' => 'Full Graduate Fellowship Scholar at CMU, Published in NeurIPS Workshop 2024.',
        'is_notable' => 1,
        'testimonial' => 'The rigorous research exposure during my final year project empowered me to secure direct admission into CMU.'
    ],
    [
        'student_id' => '22B91A6205',
        'name' => 'Aditya Sharma',
        'email' => 'aditya.sharma@srkrec.ac.in',
        'branch' => 'CSIT',
        'batch' => '2024',
        'program' => 'B.Tech',
        'company' => 'Amazon',
        'designation' => 'SDE II (AWS Cloud)',
        'location' => 'Seattle, USA',
        'industry' => 'Software & Tech',
        'description' => 'Optimizing storage layer throughput and low-latency API handlers for AWS S3 infrastructure.',
        'achievements' => 'Fast-tracked promotion at AWS within 18 months, Amazon Star Developer Award.',
        'is_notable' => 0,
        'testimonial' => 'Peer learning in department clubs and hackathons built the problem-solving mindset I use daily at Amazon AWS.'
    ],
    [
        'student_id' => '22B91A6206',
        'name' => 'Ananya Roy',
        'email' => 'ananya.roy@srkrec.ac.in',
        'branch' => 'CSD',
        'batch' => '2024',
        'program' => 'B.Tech',
        'company' => 'Qualcomm',
        'designation' => 'Embedded AI Engineer',
        'location' => 'Hyderabad, India',
        'industry' => 'Core Engineering',
        'description' => 'Designing edge AI inference kernels for Snapdragon mobile & NPU processors.',
        'achievements' => 'Co-authored Qualcomm Technical Whitepaper on low-power neural networks.',
        'is_notable' => 0,
        'testimonial' => 'The hardware-software co-design modules taught in CSIT provided me a clear edge in embedded Systems & AI chip design.'
    ],
    [
        'student_id' => '20B91A6207',
        'name' => 'Ketan Reddy',
        'email' => 'ketan.reddy@srkrec.ac.in',
        'branch' => 'CSIT',
        'batch' => '2021',
        'program' => 'B.Tech',
        'company' => 'Meta (Facebook)',
        'designation' => 'Staff Product Manager',
        'location' => 'London, UK',
        'industry' => 'Software & Tech',
        'description' => 'Leading product strategy and creator monetization tools for Instagram Reels platform.',
        'achievements' => 'Launched creator monetization features scaled to 500M+ active users globally.',
        'is_notable' => 1,
        'testimonial' => 'Department technical leadership roles helped me transition from software engineering into high-impact product management.'
    ],
    [
        'student_id' => '23B91A6208',
        'name' => 'Divya Sri Penmetsa',
        'email' => 'divyasri.p@srkrec.ac.in',
        'branch' => 'CSD',
        'batch' => '2025',
        'program' => 'B.Tech',
        'company' => 'TCS Innovation Labs',
        'designation' => 'Research Engineer',
        'location' => 'Pune, India',
        'industry' => 'AI & Machine Learning',
        'description' => 'Working on autonomous anomaly detection and predictive maintenance for industrial IoT.',
        'achievements' => 'Best B.Tech Project Award winner 2025.',
        'is_notable' => 0,
        'testimonial' => 'Faculty support for national coding competitions prepared me for high-value R&D roles straight out of campus.'
    ]
];

$id_counter = 1;
foreach ($sample_alumni as $alumni) {
    // 1. Insert/Update in students table
    $check_student = mysqli_query($conn, "SELECT student_id FROM students WHERE student_id = '{$alumni['student_id']}' OR email = '{$alumni['email']}'");
    if (mysqli_num_rows($check_student) == 0) {
        $insert_student = "INSERT INTO students (student_id, name, email, password, branch, section, class_id, is_alumni)
                           VALUES ('{$alumni['student_id']}', '{$alumni['name']}', '{$alumni['email']}', '', '{$alumni['branch']}', 'A', 1, 1)";
        mysqli_query($conn, $insert_student);
    } else {
        $update_student = "UPDATE students SET is_alumni = 1 WHERE student_id = '{$alumni['student_id']}' OR email = '{$alumni['email']}'";
        mysqli_query($conn, $update_student);
    }

    // 2. Insert into alumni_employment_history table
    $desc = mysqli_real_escape_string($conn, $alumni['description']);
    $insert_emp = "INSERT INTO alumni_employment_history (id, student_id, company_name, designation, location, industry, description)
                   VALUES ($id_counter, '{$alumni['student_id']}', '{$alumni['company']}', '{$alumni['designation']}', '{$alumni['location']}', '{$alumni['industry']}', '$desc')";
    mysqli_query($conn, $insert_emp);
    $id_counter++;
}

echo "<p style='color:green; font-weight:bold;'>Alumni data seeded successfully into MySQL database!</p>";
?>
