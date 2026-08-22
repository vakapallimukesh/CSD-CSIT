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

// 8 Full-Time Student Placement Records with LPA Packages
$placements_data = [
    ['2291A6243', 'P Rama Ganesh', 'AUNIX AI', 'Software Engineer', '12 LPA', '2026', '2026', 'images/placements/2291A6243.jpg'],
    ['22B91A6203', 'B Lakshman', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6203.jpg'],
    ['22B91A6255', 'V Swapanth', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6255.jpg'],
    ['22B91A6246', 'P Vamsi', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6246.jpg'],
    ['22B91A6237', 'P Nikhil', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6237.jpg'],
    ['22B91A6259', 'V Manasa', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6259.jpg'],
    ['22B91A6206', 'B Tejesh', 'Bluconnect AI India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6206.jpg'],
    ['23B95A6207', 'Tanguturi Pavansai', 'Zemoso Technologies', 'Dev Intern', '6.89 LPA', '2026', '2026', 'images/placements/23B95A6207.jpg']
];

$stmt = mysqli_prepare($conn, "INSERT INTO alumni_placements (student_id, student_name, company, job_role, package, placement_year, batch, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($placements_data as $p) {
    mysqli_stmt_bind_param($stmt, "ssssssss", $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7]);
    mysqli_stmt_execute($stmt);
}
mysqli_stmt_close($stmt);

echo "Placements table populated with full-time student records!\n";
?>
