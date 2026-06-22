<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Handle AJAX requests for laboratory results operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'];
    
    if ($action === 'add_result') {
        $stmt = $conn->prepare('
            INSERT INTO laboratory_results (patientId, testType, testDate, results, referenceRange, abnormalFlag, orderedBy) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        
        $stmt->bind_param('isssssi',
            $_POST['patientId'],
            $_POST['testType'],
            $_POST['testDate'],
            $_POST['results'],
            $_POST['referenceRange'],
            $_POST['abnormalFlag'],
            $_POST['orderedBy']
        );
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Laboratory result added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'delete_result') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare('DELETE FROM laboratory_results WHERE id = ?');
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $stmt->error]);
        }
        $stmt->close();
        exit;
    }
}

// Get all laboratory results for API response
if (isset($_GET['api']) && $_GET['api'] === 'get_results') {
    header('Content-Type: application/json');
    
    $result = $conn->query('
        SELECT lr.id, lr.patientId, lr.testType, lr.testDate, lr.results, lr.referenceRange, lr.abnormalFlag,
               p.firstName as patientFirstName, p.lastName as patientLastName,
               d.firstName as doctorFirstName, d.lastName as doctorLastName
        FROM laboratory_results lr 
        JOIN patients p ON lr.patientId = p.id 
        LEFT JOIN doctors d ON lr.orderedBy = d.id 
        ORDER BY lr.testDate DESC
    ');
    $results = [];
    
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    
    echo json_encode($results);
    exit;
}

// Get statistics
if (isset($_GET['api']) && $_GET['api'] === 'get_stats') {
    header('Content-Type: application/json');
    
    $total = $conn->query('SELECT COUNT(*) as count FROM laboratory_results')->fetch_assoc()['count'];
    $abnormal = $conn->query("SELECT COUNT(*) as count FROM laboratory_results WHERE abnormalFlag = 'Y'")->fetch_assoc()['count'];
    $recentMonth = $conn->query('SELECT COUNT(*) as count FROM laboratory_results WHERE testDate >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)')->fetch_assoc()['count'];
    
    echo json_encode([
        'total' => $total,
        'abnormal' => $abnormal,
        'recentMonth' => $recentMonth
    ]);
    exit;
}

function h($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$laboratoryRows = [];
$laboratoryResult = $conn->query('
    SELECT lr.id, lr.patientId, lr.testType, lr.testDate, lr.results, lr.referenceRange, lr.abnormalFlag,
           p.firstName as patientFirstName, p.lastName as patientLastName,
           d.firstName as doctorFirstName, d.lastName as doctorLastName
    FROM laboratory_results lr
    JOIN patients p ON lr.patientId = p.id
    LEFT JOIN doctors d ON lr.orderedBy = d.id
    ORDER BY lr.testDate DESC, lr.id DESC
');
if ($laboratoryResult) {
    while ($row = $laboratoryResult->fetch_assoc()) {
        $laboratoryRows[] = $row;
    }
}
?>
<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>MedLab Pro | Laboratory Results Management</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;family=Manrope:wght@600;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "on-tertiary": "#ffffff",
                      "surface-dim": "#d6dbd9",
                      "surface": "#f6faf8",
                      "surface-variant": "#dfe4e1",
                      "on-tertiary-fixed": "#171c1f",
                      "surface-bright": "#f6faf8",
                      "secondary-container": "#aed9ff",
                      "outline": "#6d7a77",
                      "on-background": "#171d1b",
                      "surface-container-lowest": "#ffffff",
                      "tertiary-fixed-dim": "#c3c7cb",
                      "surface-tint": "#006b5f",
                      "primary-fixed-dim": "#72d8c8",
                      "secondary-fixed": "#cbe6ff",
                      "secondary": "#376283",
                      "on-tertiary-fixed-variant": "#43474b",
                      "on-error-container": "#93000a",
                      "on-surface": "#171d1b",
                      "tertiary": "#585d60",
                      "secondary-fixed-dim": "#a1cbf0",
                      "on-surface-variant": "#3d4946",
                      "inverse-on-surface": "#edf2ef",
                      "inverse-primary": "#72d8c8",
                      "background": "#f6faf8",
                      "surface-container-low": "#f0f5f2",
                      "on-error": "#ffffff",
                      "surface-container": "#eaefec",
                      "on-primary-fixed": "#00201c",
                      "outline-variant": "#bdc9c5",
                      "on-tertiary-container": "#fbfcff",
                      "tertiary-container": "#707579",
                      "tertiary-fixed": "#dfe3e7",
                      "primary": "#00685d",
                      "primary-fixed": "#8ff4e3",
                      "surface-container-high": "#e4e9e7",
                      "on-secondary-fixed": "#001e30",
                      "on-primary-fixed-variant": "#005047",
                      "primary-container": "#008376",
                      "surface-container-highest": "#dfe4e1",
                      "on-secondary-fixed-variant": "#1c4a6a",
                      "on-primary": "#ffffff",
                      "on-primary-container": "#f4fffb",
                      "inverse-surface": "#2c3130",
                      "error-container": "#ffdad6",
                      "error": "#ba1a1a",
                      "on-secondary-container": "#345f80",
                      "on-secondary": "#ffffff"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "sidebar-width": "260px",
                      "card-gap": "16px",
                      "container-padding": "24px",
                      "stack-sm": "8px",
                      "stack-md": "16px",
                      "gutter": "20px"
              },
              "fontFamily": {
                      "headline-lg": ["Manrope"],
                      "label-bold": ["Inter"],
                      "body-sm": ["Inter"],
                      "headline-md": ["Manrope"],
                      "body-lg": ["Inter"],
                      "label-caps": ["Inter"],
                      "body-md": ["Inter"]
              },
              "fontSize": {
                      "headline-lg": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                      "label-bold": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                      "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                      "headline-md": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                      "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                      "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "600"}],
                      "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
              }
            },
          },
        }
      </script>
</head>
<body class="bg-background text-on-surface font-body-md overflow-hidden">
<!-- Fixed Sidebar -->
<aside class="fixed h-screen w-sidebar-width left-0 top-0 flex flex-col py-container-padding z-50" style="background-color: #00685D;">
<div class="px-6 mb-8">
<h1 class="text-headline-md font-headline-md font-bold text-surface-container-lowest">ASCLEPIUS Medical &<br> Diagnostic Group Inc.</h1>
<p class="text-label-bold text-surface-variant/60 font-label-bold">Laboratory Information System</p>
</div>
  


<nav class="flex-1 space-y-1">

<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Dashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-bold text-label-bold">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Patient.php">
<span class="material-symbols-outlined">group</span>
<span class="font-label-bold text-label-bold">Patients</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Doctor.php"> 
<span class="material-symbols-outlined">biotech</span>
<span class="font-label-bold text-label-bold">Doctors</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Appointment.php">
<span class="material-symbols-outlined">receipt_long</span>
<span class="font-label-bold text-label-bold">Appointment</span>
</a>

<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Medical Records.php">
<span class="material-symbols-outlined">science</span>
<span class="font-label-bold text-label-bold">Medical Records</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 bg-surface-variant/20 text-surface-bright rounded-lg mx-2 my-1 opacity-100 transition-colors" href="#">
<span class="material-symbols-outlined">science</span>
<span class="font-label-bold text-label-bold">Laboratory Results</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Agency Referral.php">
<span class="material-symbols-outlined">science</span>
<span class="font-label-bold text-label-bold">Agency Referral</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Prescription.php">
<span class="material-symbols-outlined">description</span>
<span class="font-label-bold text-label-bold">Prescription</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Biling.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-bold text-label-bold">Billing</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Dental.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-bold text-label-bold">Dental</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="X-ray.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-bold text-label-bold">X-Ray</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Psych.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-bold text-label-bold">Psych</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-error/80 hover:text-error hover:bg-error/10 mx-2 my-1 opacity-70 transition-colors" href="index.php" onclick="localStorage.clear();">
<span class="material-symbols-outlined">logout</span>
<span class="font-label-bold text-label-bold">Logout</span>
</a>
</nav>

</aside>
<!-- Main Content Area -->
<main class="ml-sidebar-width flex flex-col h-screen overflow-hidden">
<!-- TopAppBar -->
<header class="h-16 bg-surface border-b border-outline-variant flex items-center justify-between px-container-padding shrink-0 z-40">
<div class="flex items-center flex-1 max-w-xl">
<div class="relative w-full">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
<input class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-body-md outline-none" placeholder="Search Patient Name, MRN, or Order ID..." type="text"/>
</div>
</div>
<div class="flex items-center space-x-4">
<div class="flex items-center space-x-2 border-l border-outline-variant pl-4">
<button class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full"></span>
</button>
</div>
</div>
</header>
<!-- Scrollable Dashboard Body -->
<div class="flex-1 overflow-y-auto bg-background p-container-padding scrollbar-hide">
<!-- Page Header & Global Controls -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
<div>
<h2 class="text-headline-lg font-headline-lg text-on-surface">Laboratory Results</h2>
<p class="text-body-md text-on-surface-variant mt-1">Review and verify clinical diagnostic findings across all departments.</p>
</div>
<div class="flex items-center gap-3">
<div class="flex items-center bg-white border border-outline-variant rounded-lg p-1">
<button class="px-4 py-1.5 text-label-bold rounded-md bg-surface-container-high text-primary font-bold">Hematology</button>
<button class="px-4 py-1.5 text-label-bold rounded-md text-on-surface-variant hover:bg-surface-container-low transition-colors">Biochemistry</button>
<button class="px-4 py-1.5 text-label-bold rounded-md text-on-surface-variant hover:bg-surface-container-low transition-colors">Microbiology</button>
</div>
<button class="flex items-center space-x-2 bg-white border border-outline-variant px-4 py-2 rounded-lg text-label-bold text-on-surface-variant hover:bg-surface-container-low transition-colors">
<span class="material-symbols-outlined text-[18px]">calendar_today</span>
<span>Today</span>
</button>
<button class="bg-primary text-on-primary px-6 py-2 rounded-lg font-label-bold text-label-bold hover:bg-primary-container shadow-sm flex items-center space-x-2">
<span class="material-symbols-outlined text-[20px]">verified</span>
<span>Batch Verify</span>
</button>
</div>
</div>
<!-- Bento Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-8">
<div class="bg-white border border-outline-variant p-container-padding rounded-xl shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-2">
<span class="text-label-bold text-on-surface-variant uppercase">Results Pending</span>
<div class="w-8 h-8 rounded-lg bg-secondary-container/30 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[20px]">pending_actions</span>
</div>
</div>
<div class="text-[28px] font-bold text-on-surface">0</div>
<div class="flex items-center text-label-bold text-error mt-1">
<span class="material-symbols-outlined text-[14px] mr-1">trending_up</span>
<span>0% from yesterday</span>
</div>
</div>
<div class="bg-white border border-outline-variant p-container-padding rounded-xl shadow-sm border-l-4 border-l-error hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-2">
<span class="text-label-bold text-error uppercase">Critical Flags</span>
<div class="w-8 h-8 rounded-lg bg-error-container/30 flex items-center justify-center text-error">
<span class="material-symbols-outlined text-[20px]">warning</span>
</div>
</div>
<div class="text-[28px] font-bold text-on-surface">0</div>
<div class="text-body-sm text-on-surface-variant mt-1">Requiring immediate review</div>
</div>
<div class="bg-white border border-outline-variant p-container-padding rounded-xl shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-2">
<span class="text-label-bold text-on-surface-variant uppercase">Avg Turnaround</span>
<div class="w-8 h-8 rounded-lg bg-primary-container/20 flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[20px]">timer</span>
</div>
</div>
<div class="text-[28px] font-bold text-on-surface">0</div>
</div>
<div class="bg-white border border-outline-variant p-container-padding rounded-xl shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-2">
<span class="text-label-bold text-on-surface-variant uppercase">Verification Rate</span>
<div class="w-8 h-8 rounded-lg bg-tertiary-fixed-dim/30 flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined text-[20px]">fact_check</span>
</div>
</div>
<div class="text-[28px] font-bold text-on-surface">0<span class="text-body-lg ml-1 font-normal text-on-surface-variant">%</span></div>
<div class="flex items-center text-label-bold text-primary mt-1">
<span class="material-symbols-outlined text-[14px] mr-1">check_circle</span>
<span>Above target (0%)</span>
</div>
</div>
</div>
<!-- Results Data Table Area -->
<div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col lg:flex-row hover:shadow-md transition-shadow duration-300">
<!-- Main Table Section -->
<div class="flex-1 overflow-x-auto min-h-[600px]">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-container-low border-b border-outline-variant">
<tr>
<th class="px-6 py-4"><input class="rounded border-outline-variant text-primary focus:ring-primary" type="checkbox"/></th>
<th class="px-4 py-4 text-label-bold text-on-surface-variant uppercase whitespace-nowrap">Order ID</th>
<th class="px-4 py-4 text-label-bold text-on-surface-variant uppercase whitespace-nowrap">Patient Details</th>
<th class="px-4 py-4 text-label-bold text-on-surface-variant uppercase whitespace-nowrap">Test Description</th>
<th class="px-4 py-4 text-label-bold text-on-surface-variant uppercase whitespace-nowrap text-center">Result / Units</th>
<th class="px-4 py-4 text-label-bold text-on-surface-variant uppercase whitespace-nowrap">Flag</th>
<th class="px-4 py-4 text-label-bold text-on-surface-variant uppercase whitespace-nowrap">Status</th>
<th class="px-4 py-4 text-label-bold text-on-surface-variant uppercase whitespace-nowrap text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-surface-container">
<?php if (empty($laboratoryRows)): ?>
<tr><td colspan="8" class="px-6 py-12 text-center text-body-sm text-on-surface-variant">No laboratory results found.</td></tr>
<?php else: ?>
<?php foreach ($laboratoryRows as $result): ?>
<?php
$isAbnormal = strtoupper((string)$result['abnormalFlag']) === 'Y';
$flagText = $isAbnormal ? 'Abnormal' : 'Normal';
$flagClass = $isAbnormal ? 'bg-error-container text-on-error-container' : 'bg-primary-fixed text-on-primary-fixed';
?>
<tr class="hover:bg-surface-container-low transition-colors">
<td class="px-6 py-4"><input class="rounded border-outline-variant text-primary focus:ring-primary" type="checkbox"/></td>
<td class="px-4 py-4 font-bold text-on-surface">LAB-<?php echo str_pad((string)$result['id'], 4, '0', STR_PAD_LEFT); ?></td>
<td class="px-4 py-4">
<div class="font-bold text-on-surface"><?php echo h($result['patientFirstName'] . ' ' . $result['patientLastName']); ?></div>
<div class="text-xs text-on-surface-variant">MRN: MR-<?php echo h($result['patientId']); ?> | <?php echo h($result['testDate']); ?></div>
</td>
<td class="px-4 py-4 text-body-sm text-on-surface-variant"><?php echo h($result['testType'] ?: 'Laboratory test'); ?></td>
<td class="px-4 py-4 text-center">
<div class="font-bold text-on-surface"><?php echo h($result['results'] ?: 'Pending'); ?></div>
<div class="text-xs text-on-surface-variant"><?php echo h($result['referenceRange'] ?: 'No reference range'); ?></div>
</td>
<td class="px-4 py-4"><span class="px-3 py-1 rounded-full <?php echo $flagClass; ?> text-label-bold uppercase"><?php echo h($flagText); ?></span></td>
<td class="px-4 py-4"><span class="px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-label-bold uppercase">Released</span></td>
<td class="px-4 py-4 text-right">
<button class="p-2 rounded hover:bg-surface-container-high text-on-surface-variant" title="<?php echo h('Ordered by Dr. ' . trim(($result['doctorFirstName'] ?? '') . ' ' . ($result['doctorLastName'] ?? ''))); ?>">
<span class="material-symbols-outlined">visibility</span>
</button>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</main>
<script>
        // Simple toggle for the detail sidebar to demonstrate interaction
        function toggleSidebar() {
            const sidebar = document.getElementById('details-sidebar');
            if(sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
            }
        }

        // Add some micro-interactions to table rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                // In a real app, this would load data into the sidebar
                document.querySelectorAll('tbody tr').forEach(r => r.classList.remove('bg-primary-container/5'));
                row.classList.add('bg-primary-container/5');
            });
        });
    </script>
</body></html>
