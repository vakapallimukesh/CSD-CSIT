<?php
// Self-healing database migration for 3/4 CSIT-B (class_id = 6)
if (isset($conn) && $conn) {
    // Check if sync is needed (ensure class_id 6 has exactly the 72 new students)
    $check_cnt = @mysqli_query($conn, "SELECT COUNT(*) as total FROM students WHERE class_id = 6 AND (student_id LIKE '24B91A07%' OR student_id LIKE '25B95A07%')");
    $total_new = 0;
    if ($check_cnt) {
        $row = mysqli_fetch_assoc($check_cnt);
        $total_new = (int)($row['total'] ?? 0);
    }

    $check_total = @mysqli_query($conn, "SELECT COUNT(*) as total FROM students WHERE class_id = 6");
    $total_class = 0;
    if ($check_total) {
        $row = mysqli_fetch_assoc($check_total);
        $total_class = (int)($row['total'] ?? 0);
    }

    $sync_needed = ($total_new !== 72 || $total_class !== 72);

    if ($sync_needed) {
        // Ensure class_id = 6 exists
        @mysqli_query($conn, "INSERT IGNORE INTO classes (class_id, academic_year, year, semester, branch, section) VALUES (6, '2024-2028', 3, 1, 'CSIT', 'B')");

        $csit_b_students = [
            ["24B91A0773", "MEDISETTI SRINIJA"],
            ["24B91A0774", "MULAGALA PRANATI SANDHYA"],
            ["24B91A0775", "MURIKITHA ARCHANA SAI SRI"],
            ["24B91A0776", "NALAMALA KEVIN RISHITH"],
            ["24B91A0777", "NAMALA THANUSHA"],
            ["24B91A0778", "NELLURI CHAITRIKA SRI NIDHI"],
            ["24B91A0779", "NETHALA HEMA DURGA SAI KUMAR"],
            ["24B91A0780", "NETHULA MAHESH"],
            ["24B91A0781", "NIMMALA BHANU SRI HARSHA"],
            ["24B91A0782", "NIMMALA BHUVANA LAKSHMI"],
            ["24B91A0783", "NULI LAKSHMI SAI LIKITH"],
            ["24B91A0784", "OGURI LAKSHMI NARAYANA"],
            ["24B91A0785", "PAKA RENITA JESSIE"],
            ["24B91A0786", "PALANI BHUVANA SAI KRUTHI"],
            ["24B91A0787", "PALAPARTHI SANTHOSH KUMAR"],
            ["24B91A0788", "PALLAPU HARITHA"],
            ["24B91A0789", "PANJA SOMARANGA SAI"],
            ["24B91A0790", "PARAVASTU VENKATA RAMA SURI"],
            ["24B91A0791", "PENMETSA HARSHINI"],
            ["24B91A0792", "PONNAGANTI JYOTHIKA SAI"],
            ["24B91A0793", "POTLA RAVI"],
            ["24B91A0794", "PULAPARTHI KALYAN VENKATA SAI"],
            ["24B91A0795", "PULI MYTHILI"],
            ["24B91A0796", "PUVVALA SANJANA GAYATHRI"],
            ["24B91A0797", "RANGISETTI SAI PAVAN KUMAR"],
            ["24B91A0798", "REDDEM LEELA MEGHANA"],
            ["24B91A0799", "REDDY VENKATA SATYA SRAVANI"],
            ["24B91A07A0", "ROMPILLI SATEESH"],
            ["24B91A07A1", "RONGALA SRINIVAS"],
            ["24B91A07A2", "ROTTE SUSHANTH"],
            ["24B91A07A3", "SAKHINETIPALLI CHAKRI ADITYA PAVAN KUMAR"],
            ["24B91A07A4", "SAMUDRALA JESRAVAN MANIKANTA"],
            ["24B91A07A5", "SANA SHANMUKHA DURGA"],
            ["24B91A07A6", "SEELABOYINA JEEVANA"],
            ["24B91A07A7", "SEELABOYINA JEEVIKA"],
            ["24B91A07A8", "SHAIK ABDUL GAFOOR"],
            ["24B91A07A9", "SHAIK AMEENA"],
            ["24B91A07B0", "SHAIK NAGUR MADEENA BEGAM"],
            ["24B91A07B1", "SIDAGAM ABHIRAM"],
            ["24B91A07B2", "SIDDAMSETTI VIVEK SAI"],
            ["24B91A07B3", "SIRRA DURGA RANI"],
            ["24B91A07B4", "SWARNA GOWTHAMI"],
            ["24B91A07B5", "SWARNA SAHITHI"],
            ["24B91A07B6", "TALARI JYOTHI"],
            ["24B91A07B7", "THOTA JOHAN BENEDICT"],
            ["24B91A07B8", "TIRUMALASETTY SIDDARDHA"],
            ["24B91A07B9", "UPPALA ABHINAYA SREE"],
            ["24B91A07C0", "VADREVU LAHARI DEVI"],
            ["24B91A07C1", "VALAVALA RAMA LAKSHMI ANJANA"],
            ["24B91A07C2", "VANAPARTHI ASMITHA VYSHNAVI"],
            ["24B91A07C3", "VASE ASHITHA"],
            ["24B91A07C4", "VASKA JYOTHI"],
            ["24B91A07C5", "VATHADI NAGAVINAY"],
            ["24B91A07C6", "VATTIVELLA RAMKI"],
            ["24B91A07C7", "VENKATA NISHITHA REDDY DATLA"],
            ["24B91A07C8", "YALLAPU TANUJA"],
            ["24B91A07C9", "YARLAGADDA TAMOGHNA"],
            ["24B91A07D0", "YENDA RASHMIKA"],
            ["24B91A07D1", "YERRA YASVASI SATYA KAVERI"],
            ["25B95A0701", "BOLEM PRAVALIKA"],
            ["25B95A0702", "CHEYYETI VENKATA SINDHU"],
            ["25B95A0703", "DONGA MAHESH"],
            ["25B95A0704", "GANJI JYOTHSNA"],
            ["25B95A0705", "MUTHYALAPALLI"],
            ["25B95A0706", "NIMMANA NARENDRA"],
            ["25B95A0707", "PANDA SUJAN PRASAD"],
            ["25B95A0708", "PATAN ABDUL RASHEED KHAN"],
            ["25B95A0709", "REBBA RAJESH"],
            ["25B95A0710", "SARIPALLI GNANESWAR"],
            ["25B95A0711", "TUMMA NAGA DURGA"],
            ["25B95A0712", "TUMMALAGUNTA SAHITHI LAKSHMI"],
            ["25B95A0713", "UNDRAJAVARAPU NAGA VENKATA RAGHU"]
        ];

        $allowed_ids = [];
        $house_ids = [1, 2, 3, 4, 5];
        $idx = 0;

        foreach ($csit_b_students as $st) {
            $sid = mysqli_real_escape_string($conn, $st[0]);
            $sname = mysqli_real_escape_string($conn, $st[1]);
            $semail = strtolower($st[0]) . "@srkr.ac.in";
            $hid = $house_ids[$idx % 5];
            $idx++;
            $allowed_ids[] = "'$sid'";

            $default_pass = '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFj5kFpP5W5P5W5P5W5P5W5P5W5P5W5';

            // Insert or update into students table
            $sql_student = "INSERT INTO students (student_id, name, email, password, branch, section, class_id, is_alumni, hid) 
                            VALUES ('$sid', '$sname', '$semail', '$default_pass', 'CSIT', 'B', 6, 0, $hid) 
                            ON DUPLICATE KEY UPDATE 
                                name = '$sname', 
                                branch = 'CSIT', 
                                section = 'B', 
                                class_id = 6, 
                                is_alumni = 0";
            @mysqli_query($conn, $sql_student);

            // Legacy table update if exists
            $sql_legacy = "INSERT INTO house_points (regd_no, name, year_section, house_name, total_points) 
                           VALUES ('$sid', '$sname', 'CSIT - B', 'AGNI', 0) 
                           ON DUPLICATE KEY UPDATE name = '$sname', year_section = 'CSIT - B'";
            @mysqli_query($conn, $sql_legacy);
        }

        // Delete any old students from class_id = 6 who are not in the new 72-student list
        if (!empty($allowed_ids)) {
            $allowed_str = implode(',', $allowed_ids);
            @mysqli_query($conn, "DELETE FROM students WHERE class_id = 6 AND student_id NOT IN ($allowed_str)");
        }
    }
}
