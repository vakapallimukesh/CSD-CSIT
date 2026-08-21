<?php
header('Content-Type: application/json');
include './connect.php';

// Helper for avatar URL using student name
function getStudentAvatar($name) {
    return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=d97706&color=ffffff&bold=true&size=256';
}

// BATCH 2024: Official 69 Student Class List (from Class List Document)
$batch_2024_raw = [
    ["21B91A6201", "ACHANTA SREE GREESHMA"],
    ["21B91A6202", "BAIRISETTI SAI GANGADHAR"],
    ["21B91A6203", "BHOKASKEDE BHAGAVATH"],
    ["21B91A6204", "BODAPATI YASWANTH RAJ"],
    ["21B91A6205", "BYRI ROHIT"],
    ["21B91A6206", "CH RAVI KUMAR SATYA SAI"],
    ["21B91A6207", "CHENNAMSETTI ANUSHA"],
    ["21B91A6208", "CHITTURI KARTHIK"],
    ["21B91A6209", "DANDU RAMA SIVA NAGA"],
    ["21B91A6210", "D VIVEK HARI BHASKAR"],
    ["21B91A6211", "DEVADA LAVANYA"],
    ["21B91A6212", "LAVANYA DUDDUKURI"],
    ["21B91A6213", "GADIRAJU JANAKI RITHVIK"],
    ["21B91A6214", "GOLVE YASWANTH SAI"],
    ["21B91A6215", "GUDAPATI YAKSHINE"],
    ["21B91A6216", "GUNTURU UDAY KIRAN"],
    ["21B91A6217", "JALLELLA RAM SUMA"],
    ["21B91A6218", "JAVVADI BHARGAVI"],
    ["21B91A6219", "KATARI SAI GANESH"],
    ["21B91A6220", "KATURI SANJU"],
    ["21B91A6221", "KEDHARISETTI SURYA"],
    ["21B91A6222", "KOMALI LAKSHMI PURNA"],
    ["21B91A6223", "KONAKALA VASANTHI"],
    ["21B91A6224", "KOYYE SASANK"],
    ["21B91A6226", "KUNCHANAPALLI"],
    ["21B91A6227", "MALLADI TEJASRI"],
    ["21B91A6228", "MALLIPUDI RAJESH"],
    ["21B91A6229", "MANEPALLI KALKI NAGA"],
    ["21B91A6230", "VENKATA NAGA"],
    ["21B91A6231", "MARISETTI BHANU"],
    ["21B91A6232", "MUDUNURI PRUDHVI SAI"],
    ["21B91A6233", "MUNSHI ABDUL RAHEEM"],
    ["21B91A6234", "N J NAGA VENKATA SATYA"],
    ["21B91A6235", "NETALA SRUJANA SRI"],
    ["21B91A6236", "PADAVALA YOGITHA"],
    ["21B91A6237", "PAGOLLU GRACY"],
    ["21B91A6238", "PATHIWADA REVATTHI"],
    ["21B91A6239", "P SRI VENKATA SIVA"],
    ["21B91A6240", "PUDI CHAITANYA SRUJANA"],
    ["21B91A6241", "RAMAGANI MALLESWARI"],
    ["21B91A6242", "RAVURI UMA SUBHASHINI"],
    ["21B91A6243", "REDDI MADHAVI"],
    ["21B91A6244", "REDDI SAHITHI PALLAVI"],
    ["21B91A6245", "SADANALA MANASANTHI"],
    ["21B91A6246", "S N BHAGAVATI VIGHNESH"],
    ["21B91A6247", "SHAIK ABDUL AZIZ"],
    ["21B91A6248", "SHAIK ABDUL LATHIF"],
    ["21B91A6249", "SIDAGAM VINAY PRASAD"],
    ["21B91A6250", "T HARSHA KUMARI"],
    ["21B91A6251", "TANNEERU VASANTH"],
    ["21B91A6252", "THATTULOLLA SIVA"],
    ["21B91A6253", "UNNAMTLA NAVEEN RAHUL"],
    ["21B91A6254", "UTADA LAKSHMI TULASI"],
    ["21B91A6255", "V ANJANI NAGA SARANYA"],
    ["21B91A6256", "VANAPALLI SAI SIVAMANI"],
    ["21B91A6257", "VANGAPANDU ROHITH"],
    ["21B91A6258", "V H VARDHAN KRISHNA SAI"],
    ["21B91A6259", "VEERVALLI PUNEETH"],
    ["21B91A6260", "VEMULA VARUN SURYA"],
    ["21B91A6261", "V NAGA MANIKANTA RAMA"],
    ["21B91A6262", "YALAKALA GANESH"],
    ["21B91A6263", "YELETI NOGYA"],
    ["22B95A6201", "GULLAPUDI NAGA"],
    ["22B95A6202", "KOYINANA PRANATHI SREE"],
    ["22B95A6203", "M M VENKATA SATYA"],
    ["22B95A6204", "PAGOTI MADHUVARDHAN"],
    ["22B95A6205", "PANDAY TARUNO DAY"],
    ["22B95A6206", "SIVAKAVI SOMESWAR"],
    ["22B95A6207", "VASAMSETTI SATISH"]
];

$batch_2024_list = [];
foreach ($batch_2024_raw as $item) {
    $batch_2024_list[] = [
        'id' => $item[0],
        'student_id' => $item[0],
        'registration_number' => $item[0],
        'name' => ucwords(strtolower($item[1])),
        'batch' => '2024',
        'department' => 'CSD',
        'branch' => 'CSD',
        'current_role' => 'Graduate Alumnus',
        'company' => 'CSD Department',
        'location' => 'Bhimavaram, India',
        'industry' => 'Software & Tech',
        'photo' => getStudentAvatar(ucwords(strtolower($item[1]))),
        'linkedin' => '#'
    ];
}

// BATCH 2025: Official 67 Student Class List (from Class List Document)
$batch_2025_raw = [
    ["22B91A6201", "ARNEPALLI MEGANA"],
    ["22B91A6202", "BAYYE JOSEPH KUMAR"],
    ["22B91A6203", "BHAVANAM LAKSHMAN KUMAR REDDY"],
    ["22B91A6204", "BORRA AVINASH"],
    ["22B91A6205", "BORRA HIMA SRI"],
    ["22B91A6206", "BUDDE VENKATA SATYA TEJESH"],
    ["22B91A6207", "CHIKILE RAJESH"],
    ["22B91A6208", "CHILAKALAPUDI ABHIRAAMA PHANINDRA"],
    ["22B91A6209", "CHIMAKURTHI TEJA RUPAK"],
    ["22B91A6210", "DAKKUMALLA VARSHA"],
    ["22B91A6211", "DONAVALLI REVATHI"],
    ["22B91A6212", "GEDELA SAI ABHINAY"],
    ["22B91A6213", "GOTTUMUKKALA BHARGAVI"],
    ["22B91A6214", "INUMARTHI SRINAVYA"],
    ["22B91A6215", "JADDU JYOTHIRMAI INDIRA PRIYADARSINI DEVI"],
    ["22B91A6216", "JAKKAMSETTI SANJANI"],
    ["22B91A6217", "JOGI PAVAN TEJA"],
    ["22B91A6218", "KAMBHAMPATI SHALANI SINDHUSRI"],
    ["22B91A6219", "KANUMURI RISHITHA VARMA"],
    ["22B91A6220", "KAPUDASI SNIGDHA"],
    ["22B91A6221", "KARUMURI TEJA SIDDARDHA PAVAN KUMAR"],
    ["22B91A6222", "KETHA SURYAPRAKASH"],
    ["22B91A6223", "KOLAYESWANTH"],
    ["22B91A6224", "KOLATI STEPHEN SOUDH"],
    ["22B91A6225", "KOLLABATHULA SHYAMBABU"],
    ["22B91A6226", "KOLLATI VISHNU TEJA"],
    ["22B91A6227", "KOPPARTI HONEY NAGASANDEEP"],
    ["22B91A6228", "LAKSHMI VENKATA NIKHITHA"],
    ["22B91A6229", "MADDI AKSHAYASRI"],
    ["22B91A6230", "MANDANGI MOUNIKA"],
    ["22B91A6231", "MANGENA JAHNAVI"],
    ["22B91A6232", "MANGINETI MOHAN SATYA SIVA ROHITH KUMAR"],
    ["22B91A6233", "MATTA BALA VEERRAJU"],
    ["22B91A6234", "MOTURI SANDILYA"],
    ["22B91A6235", "MUDUNURI MANOJ SAI ASWANTH VARMA"],
    ["22B91A6236", "NALLAM HEMASAI SRI LAKSHMI"],
    ["22B91A6237", "PAILA NIKHIL"],
    ["22B91A6238", "PANAKALA RAMA NAGESWARA RAO"],
    ["22B91A6239", "PEPETI GANESH"],
    ["22B91A6240", "PERABATHULA SOMESWARA RAO"],
    ["22B91A6241", "PIPPALLA RUSHI GUNA SHANMUKH"],
    ["22B91A6242", "POSIMSETTY SRI VISWA BHARATH"],
    ["22B91A6243", "POTHAMSETTI KODANDA RAMA NAGA GANESH"],
    ["22B91A6244", "POTTURI GAYATRI"],
    ["22B91A6245", "PULI DURGA BHAVANI"],
    ["22B91A6246", "PULLURU KRISHNA VAMSI"],
    ["22B91A6247", "PUTHINIDI JNANESWARI"],
    ["22B91A6248", "RAAVI CHARWAK"],
    ["22B91A6249", "SETTI NARENDRA KUMAR"],
    ["22B91A6250", "SHAIK AHMED"],
    ["22B91A6251", "SHAIK KARMUNNISA"],
    ["22B91A6252", "TELLAKULA VEERA RAGHAVA"],
    ["22B91A6253", "UNDAPALLI DIVYA"],
    ["22B91A6254", "UNDURTHI MANOJ"],
    ["22B91A6255", "VAKAPALLI H V SAI SURYA SWAPANTH"],
    ["22B91A6256", "VATAPALLI GNANA SEKHAR"],
    ["22B91A6257", "VEERAVALLI SATYA VENKATA SRINADH"],
    ["22B91A6258", "VEGESNA PRADEEPTHI"],
    ["22B91A6259", "VILLURI MOHINI MANGALAKSHMI MANASA"],
    ["23B95A6201", "ANDENAGA SATYA SAI VAMSIKIRAN"],
    ["23B95A6202", "GUTTULA TEJASWI"],
    ["23B95A6203", "KEELACHAKRA VAMSI"],
    ["23B95A6204", "MOHAMMED SIKINDAR KHAN"],
    ["23B95A6205", "NAKKINA GANESH"],
    ["23B95A6206", "MIDDOLLA AKASH NAGENDRA SAI PAVAN"],
    ["23B95A6207", "TANGUTURI SIVA VENKATA NAGA PAVANSAI"],
    ["23B95A6208", "THOTA SUJAY BABU"]
];

$batch_2025_list = [];
foreach ($batch_2025_raw as $item) {
    // Check if custom face photo exists from extracted posters
    $custom_photo = 'images/placements/' . $item[0] . '.jpg';
    $photo_url = file_exists('./' . $custom_photo) ? $custom_photo : getStudentAvatar(ucwords(strtolower($item[1])));

    $batch_2025_list[] = [
        'id' => $item[0],
        'student_id' => $item[0],
        'registration_number' => $item[0],
        'name' => ucwords(strtolower($item[1])),
        'batch' => '2025',
        'department' => 'CSD',
        'branch' => 'CSD',
        'current_role' => 'Graduate Alumnus',
        'company' => 'CSD Department',
        'location' => 'Bhimavaram, India',
        'industry' => 'Software & Tech',
        'photo' => $photo_url,
        'linkedin' => '#'
    ];
}

// 18 Student Placements & Internships from Posters
$placements = [];
if ($conn) {
    $res_p = @mysqli_query($conn, "SELECT * FROM alumni_placements ORDER BY id ASC");
    if ($res_p && mysqli_num_rows($res_p) > 0) {
        while ($row = mysqli_fetch_assoc($res_p)) {
            $placements[] = [
                'id' => $row['id'],
                'student_id' => $row['student_id'],
                'student_name' => $row['student_name'],
                'company' => $row['company'],
                'job_role' => $row['job_role'],
                'package' => $row['package'],
                'placement_year' => $row['placement_year'],
                'batch' => $row['batch'],
                'photo' => !empty($row['photo']) ? $row['photo'] : 'images/placements/' . $row['student_id'] . '.jpg'
            ];
        }
    }
}

// Query parameters
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$batch_param = isset($_GET['batch']) ? trim($_GET['batch']) : '';

if (!empty($batch_param) && $batch_param === '2024') {
    $filtered_alumni = $batch_2024_list;
} elseif (!empty($batch_param) && $batch_param === '2025') {
    $filtered_alumni = $batch_2025_list;
} else {
    $filtered_alumni = array_merge($batch_2024_list, $batch_2025_list);
}

if (!empty($search)) {
    $filtered_alumni = array_values(array_filter($filtered_alumni, function($item) use ($search) {
        $haystack = strtolower($item['name'] . ' ' . $item['registration_number']);
        return strpos($haystack, $search) !== false;
    }));
}

$response = [
    'status' => 'success',
    'stats' => [
        'total_alumni' => count($batch_2024_list) + count($batch_2025_list),
        'total_batches' => 2,
        'batch_2024_count' => count($batch_2024_list),
        'batch_2025_count' => count($batch_2025_list),
        'total_placements' => count($placements)
    ],
    'batches' => [
        '2024' => [
            'count' => count($batch_2024_list),
            'alumni' => $batch_2024_list
        ],
        '2025' => [
            'count' => count($batch_2025_list),
            'alumni' => $batch_2025_list
        ]
    ],
    'placements' => $placements,
    'achievements' => [],
    'alumni' => $filtered_alumni
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
