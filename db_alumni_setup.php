<?php
include './connect.php';

echo "Updating Placement Database Records with 29 Poster Student Selections...\n";

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

// Truncate existing placement records
mysqli_query($conn, "TRUNCATE TABLE alumni_placements");

// 29 Official Full-Time Student Placement Records from Uploaded Posters
$placements_data = [
    ['2291A6243', 'P Rama Ganesh', 'AUNIX AI', 'Software Engineer', '12 LPA', '2026', '2026', 'images/placements/2291A6243.jpg'],
    ['21B91A6218', 'Javvadi Bhargavi', 'Akrivia HCM', 'Software Development Engineer', '10-12 LPA', '2025', '2025', 'images/placements/21B91A6218.jpg'],
    ['22B91A6259', 'V M M Lakshmi Manasa', 'Bluconnect Ai India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6259.jpg'],
    ['22B91A6206', 'B V Satya Tejesh', 'Bluconnect Ai India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6206.jpg'],
    ['22B91A6203', 'B Lakshman Kumar Reddy', 'Bluconnect Ai India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6203.jpg'],
    ['22B91A6255', 'V H V S Surya Swapanth', 'Bluconnect Ai India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6255.jpg'],
    ['22B91A6237', 'P Nikhil', 'Bluconnect Ai India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6237.jpg'],
    ['22B91A6212', 'G Sai Abhinay', 'Bluconnect Ai India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6212.jpg'],
    ['22B91A6234', 'M Sandilya', 'Bluconnect Ai India Pvt Ltd', 'Software Development Engineer', '7.8 LPA', '2026', '2026', 'images/placements/22B91A6234.jpg'],
    ['21B91A6257', 'V Rohit', 'Bluconnect Ai India Pvt Ltd', 'Software Developer Intern', '7.8 LPA', '2025', '2025', 'images/placements/21B91A6257.jpg'],
    ['21B91A6234', 'N Siva Sai', 'Bluconnect Ai India Pvt Ltd', 'Software Developer Intern', '7.8 LPA', '2025', '2025', 'images/placements/21B91A6234.jpg'],
    ['21B91A6258', 'V Harsha Sai', 'Bluconnect Ai India Pvt Ltd', 'Software Developer Intern', '7.8 LPA', '2025', '2025', 'images/placements/21B91A6258.jpg'],
    ['22B91A6219', 'K Rishitha Varma', 'Zemoso Technologies', 'Dev Engineer', '6.89 LPA', '2026', '2026', 'images/placements/22B91A6219.jpg'],
    ['23B95A6207', 'Tanguturi Pavansai', 'Zemoso Technologies', 'Dev Engineer', '6.89 LPA', '2026', '2026', 'images/placements/23B95A6207.jpg'],
    ['21B91A6249', 'Vinay Prasad', 'Infosys', 'Digital Specialist Engineer', '6.2 LPA', '2025', '2025', 'images/placements/21B91A6249.jpg'],
    ['21B91A6242', 'R Subhashini', 'Meeami Technologies', 'Jr AI Audio Engineer', '6 LPA', '2025', '2025', 'images/placements/21B91A6242.jpg'],
    ['21B91A6222', 'K Lakshmi Purna Sri', 'Quanteon Solutions', 'Business Development Associate', '5 LPA', '2025', '2025', 'images/placements/21B91A6222.jpg'],
    ['21B91A6262', 'Y Ganesh', 'SmartED', 'Business Growth Specialist', '5 LPA', '2025', '2025', 'images/placements/21B91A6262.jpg'],
    ['22B91A6223', 'Kola Yeswanth', 'Deloitte US-India', 'Analyst Trainee', '4.5 LPA', '2026', '2026', 'images/placements/22B91A6223.jpg'],
    ['21B91A6235', 'N. Srujana Sri', 'HCLTech', 'Associate Software Engineer', '4.5 LPA', '2025', '2025', 'images/placements/21B91A6235.jpg'],
    ['22B91A6221', 'K Teja Siddardha', 'Saptarishi Solutions', 'Junior Full Stack Developer', '4.2 LPA', '2026', '2026', 'images/placements/22B91A6221.jpg'],
    ['21B91A6244', 'Reddi Sahithi', 'Atelia Software India Pvt Ltd', 'Full Stack Developer', '4 LPA', '2025', '2025', 'images/placements/21B91A6244.jpg'],
    ['21B91A6238', 'Revathi Pathiwada', 'Atelia Software India Pvt Ltd', 'Full Stack Developer', '4 LPA', '2025', '2025', 'images/placements/21B91A6238.jpg'],
    ['21B91A6205', 'Byri Rohit', 'Atelia Software India Pvt Ltd', 'Full Stack Developer', '4 LPA', '2025', '2025', 'images/placements/21B91A6205.jpg'],
    ['22B91A6216', 'J. Sanjani', 'Achala IT Solutions', 'Associate Designer', '3.5 LPA', '2026', '2026', 'images/placements/22B91A6216.jpg'],
    ['22B91A6236', 'Nallam Hema Sai Sri', 'Syren Cloud', 'Data Engineer', '3.5 LPA', '2026', '2026', 'images/placements/22B91A6236.jpg'],
    ['21B91A6217', 'J. R. S. Sathwik', 'HCLTech', 'Graduate Engineer Trainee', '3.25 LPA', '2025', '2025', 'images/placements/21B91A6217.jpg'],
    ['22B91A6256', 'V. Gnana Sekhar', 'Achala IT Solutions', 'Product Development Associate', '3 LPA', '2026', '2026', 'images/placements/22B91A6256.jpg'],
    ['22B91A6239', 'Pepeti Ganesh', 'Achala IT Solutions', 'Associate Engineer', '3 LPA', '2026', '2026', 'images/placements/22B91A6239.jpg']
];

$stmt = mysqli_prepare($conn, "INSERT INTO alumni_placements (student_id, student_name, company, job_role, package, placement_year, batch, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($placements_data as $p) {
    mysqli_stmt_bind_param($stmt, "ssssssss", $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7]);
    mysqli_stmt_execute($stmt);
}
mysqli_stmt_close($stmt);

echo "Placements table populated with 29 full-time student records!\n";
?>
