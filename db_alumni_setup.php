<?php
include './connect.php';

echo "Updating Placement & Internship Database Records with User Poster Face Photos...\n";

if (!$conn) {
    die("Database connection failed.\n");
}

// 1. Create alumni_placements table if missing
$sql_placements = "CREATE TABLE IF NOT EXISTS `alumni_placements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `company` varchar(255) NOT NULL,
  `job_role` varchar(100) NOT NULL,
  `package` varchar(50) DEFAULT NULL,
  `placement_year` varchar(10) NOT NULL,
  `batch` varchar(10) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

mysqli_query($conn, $sql_placements);

// Truncate existing placement records so NO auto-generated mock profiles remain
mysqli_query($conn, "TRUNCATE TABLE alumni_placements");

// 18 Exact Student Placement & Internship Records from User Posters with Cropped Face Photos
$placements_data = [
    // --- Full-Time Placements & LPA Packages ---
    ['2291A6243', 'P Rama Ganesh', 'AUNIX AI', 'Software Engineer', '12 LPA', '2026', '2026', 'images/placements/2291A6243.jpg'],
    ['22B91A6203', 'B Lakshman', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6203.jpg'],
    ['22B91A6255', 'V Swapanth', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6255.jpg'],
    ['22B91A6246', 'P Vamsi', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6246.jpg'],
    ['22B91A6237', 'P Nikhil', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6237.jpg'],
    ['22B91A6259', 'V Manasa', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6259.jpg'],
    ['22B91A6206', 'B Tejesh', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6206.jpg'],
    ['23B95A6207', 'Tanguturi Pavansai', 'Zemoso Technologies', 'Dev Intern', '6.89 LPA', '2026', '2026', 'images/placements/23B95A6207.jpg'],
    
    // --- Aunix Software Pvt Ltd Software Engineering Internships ---
    ['23B91A6240', 'M Tanmay', 'Aunix Software Private Limited', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A6240.jpg'],
    ['23B91A6214', 'Ch. Shanmukha Siva', 'Aunix Software Private Limited', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A6214.jpg'],
    ['23B91A6234', 'K. Prem', 'Aunix Software Private Limited', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A6234.jpg'],
    ['23B91A6230', 'K Dolly Ganya', 'Aunix Software Private Limited', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A6230.jpg'],
    ['23B91A6248', 'N. Likhitha', 'Aunix Software Private Limited', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A6248.jpg'],
    
    // --- Zennith Digital Tech LLP Software Engineering Internships ---
    ['23B91A0738', 'N. Leela Madhav Rao', 'Zennith Digital Tech LLP', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A0738.jpg'],
    ['23B91A0727', 'K. S. Sriram Charan Teja', 'Zennith Digital Tech LLP', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A0727.jpg'],
    ['23B91A0714', 'G. Nikhila Valli', 'Zennith Digital Tech LLP', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A0714.jpg'],
    ['23B91A6219', 'G. Manoj Kumar', 'Zennith Digital Tech LLP', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/23B91A6219.jpg'],
    ['24B95A6207', 'T. Uma Sai Pavan', 'Zennith Digital Tech LLP', 'Software Engineering Intern', 'Stipend / Pre-Placement', '2026', '2026', 'images/placements/24B95A6207.jpg']
];

$stmt = mysqli_prepare($conn, "INSERT INTO alumni_placements (student_id, student_name, company, job_role, package, placement_year, batch, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($placements_data as $p) {
    mysqli_stmt_bind_param($stmt, "ssssssss", $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7]);
    mysqli_stmt_execute($stmt);
}
mysqli_stmt_close($stmt);

echo "Placements table populated with exactly 18 student records from user posters with custom face photos!\n";
?>
