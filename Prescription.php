<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Handle AJAX requests for prescription operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'];
    
    if ($action === 'add_prescription') {
        $stmt = $conn->prepare('
            INSERT INTO prescriptions (patientId, doctorId, medicationName, dosage, frequency, duration, prescriptionDate, expiryDate, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        
        $stmt->bind_param('iisssssss',
            $_POST['patientId'],
            $_POST['doctorId'],
            $_POST['medicationName'],
            $_POST['dosage'],
            $_POST['frequency'],
            $_POST['duration'],
            $_POST['prescriptionDate'],
            $_POST['expiryDate'],
            $_POST['notes']
        );
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Prescription added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'delete_prescription') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare('DELETE FROM prescriptions WHERE id = ?');
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

// Get all prescriptions for API response
if (isset($_GET['api']) && $_GET['api'] === 'get_prescriptions') {
    header('Content-Type: application/json');
    
    $result = $conn->query('
        SELECT p.id, p.medicationName, p.dosage, p.frequency, p.duration, p.prescriptionDate, p.expiryDate, p.status, p.notes,
               pt.firstName as patientFirstName, pt.lastName as patientLastName,
               d.firstName as doctorFirstName, d.lastName as doctorLastName
        FROM prescriptions p 
        JOIN patients pt ON p.patientId = pt.id 
        JOIN doctors d ON p.doctorId = d.id 
        ORDER BY p.prescriptionDate DESC
    ');
    $prescriptions = [];
    
    while ($row = $result->fetch_assoc()) {
        $prescriptions[] = $row;
    }
    
    echo json_encode($prescriptions);
    exit;
}

// Get statistics
if (isset($_GET['api']) && $_GET['api'] === 'get_stats') {
    header('Content-Type: application/json');
    
    $total = $conn->query('SELECT COUNT(*) as count FROM prescriptions')->fetch_assoc()['count'];
    $active = $conn->query("SELECT COUNT(*) as count FROM prescriptions WHERE status = 'Active' AND expiryDate >= CURDATE()")->fetch_assoc()['count'];
    $expired = $conn->query("SELECT COUNT(*) as count FROM prescriptions WHERE expiryDate < CURDATE()")->fetch_assoc()['count'];
    
    echo json_encode([
        'total' => $total,
        'active' => $active,
        'expired' => $expired
    ]);
    exit;
}

function h($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$prescriptionRows = [];
$prescriptionResult = $conn->query('
    SELECT p.id, p.medicationName, p.dosage, p.frequency, p.duration, p.prescriptionDate, p.expiryDate, p.status, p.notes,
           pt.firstName as patientFirstName, pt.lastName as patientLastName,
           d.firstName as doctorFirstName, d.lastName as doctorLastName
    FROM prescriptions p
    JOIN patients pt ON p.patientId = pt.id
    JOIN doctors d ON p.doctorId = d.id
    ORDER BY p.prescriptionDate DESC, p.id DESC
');
if ($prescriptionResult) {
    while ($row = $prescriptionResult->fetch_assoc()) {
        $prescriptionRows[] = $row;
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Prescription Management - MedLab Pro</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container": "#eaefec",
                        "on-primary-fixed": "#00201c",
                        "secondary-fixed-dim": "#a1cbf0",
                        "on-error": "#ffffff",
                        "inverse-primary": "#72d8c8",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#e4e9e7",
                        "on-primary-fixed-variant": "#005047",
                        "on-primary": "#ffffff",
                        "surface-tint": "#006b5f",
                        "background": "#f6faf8",
                        "tertiary-fixed-dim": "#c3c7cb",
                        "secondary": "#376283",
                        "on-secondary-fixed": "#001e30",
                        "secondary-fixed": "#cbe6ff",
                        "secondary-container": "#aed9ff",
                        "primary-container": "#008376",
                        "surface-bright": "#f6faf8",
                        "on-secondary-container": "#345f80",
                        "surface": "#f6faf8",
                        "on-tertiary-fixed": "#171c1f",
                        "on-background": "#171d1b",
                        "surface-container-highest": "#dfe4e1",
                        "inverse-on-surface": "#edf2ef",
                        "on-tertiary-container": "#fbfcff",
                        "on-tertiary-fixed-variant": "#43474b",
                        "tertiary-fixed": "#dfe3e7",
                        "on-secondary-fixed-variant": "#1c4a6a",
                        "inverse-surface": "#2c3130",
                        "tertiary": "#585d60",
                        "surface-variant": "#dfe4e1",
                        "outline": "#6d7a77",
                        "error": "#ba1a1a",
                        "primary-fixed-dim": "#72d8c8",
                        "outline-variant": "#bdc9c5",
                        "primary-fixed": "#8ff4e3",
                        "on-surface-variant": "#3d4946",
                        "on-error-container": "#93000a",
                        "on-tertiary": "#ffffff",
                        "on-secondary": "#ffffff",
                        "tertiary-container": "#707579",
                        "surface-dim": "#d6dbd9",
                        "error-container": "#ffdad6",
                        "primary": "#00685d",
                        "on-primary-container": "#f4fffb",
                        "surface-container-low": "#f0f5f2"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "stack-md": "16px",
                        "gutter": "20px",
                        "stack-sm": "8px",
                        "container-padding": "24px",
                        "sidebar-width": "260px",
                        "card-gap": "16px"
                    },
                    "fontFamily": {
                        "body-sm": ["Inter"],
                        "label-caps": ["Inter"],
                        "label-bold": ["Inter"],
                        "body-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-lg": ["Manrope"],
                        "headline-md": ["Manrope"]
                    },
                    "fontSize": {
                        "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "600"}],
                        "label-bold": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "headline-md": ["18px", {"lineHeight": "24px", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .sidebar-active {
            opacity: 1;
        }
        body {
            background-color: #f6faf8;
        }
    </style>
</head>
<body class="font-body-md text-on-background">
<!-- SideNavBar Component -->
<aside class="fixed left-0 top-0 h-full w-[260px] flex flex-col py-6 z-50" style="background-color: #00685D;">
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
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Laboratory Result.php">
<span class="material-symbols-outlined">science</span>
<span class="font-label-bold text-label-bold">Laboratory Results</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Agency Referral.php">
<span class="material-symbols-outlined">science</span>
<span class="font-label-bold text-label-bold">Agency Referral</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 bg-surface-variant/20 text-surface-bright rounded-lg mx-2 my-1 opacity-100 transition-colors" href="#">
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
<!-- TopAppBar Component -->
<header class="fixed top-0 left-[260px] right-0 h-16 bg-surface-bright border-b border-outline-variant flex items-center justify-between px-6 z-40 shadow-sm">
<div class="flex items-center flex-1 max-w-xl">
<div class="relative w-full">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 focus:ring-2 focus:ring-primary text-body-md" placeholder="Search patient, medication, or order ID..." type="text"/>
</div>
</div>
<div class="flex items-center gap-4">
<button class="hover:bg-surface-container-low rounded-full p-2 transition-all">
<span class="material-symbols-outlined text-primary">notifications</span>
</button>


</div>
</div>
</header>
<!-- Main Content Canvas -->
<main class="ml-[260px] mt-16 p-6 min-h-screen">
<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
<div>
<nav class="flex items-center text-sm text-on-surface-variant mb-2">
<span>Patients</span>
<span class="material-symbols-outlined text-xs mx-2">chevron_right</span>
<span>Elena Rodriguez</span>
<span class="material-symbols-outlined text-xs mx-2">chevron_right</span>
<span class="text-primary font-bold">Prescriptions</span>
</nav>
<h2 class="font-headline-lg text-headline-lg text-on-surface flex items-center gap-3">
                    Prescriptions
                    <span class="px-2 py-0.5 bg-surface-container-highest text-label-bold rounded text-on-surface-variant">PID: 4492-ER</span>
</h2>
</div>
<button class="bg-primary text-on-primary px-6 py-2.5 rounded-lg font-label-bold flex items-center gap-2 hover:opacity-90 transition-all shadow-sm" onclick="openNewPrescriptionModal()">
<span class="material-symbols-outlined">add</span>
                NEW PRESCRIPTION
            </button>
</div>
<!-- Summary Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
<!-- Active Prescriptions -->
<div class="bg-white border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-4">
<div class="p-2 bg-primary/10 rounded-lg text-primary">
<span class="material-symbols-outlined">medication</span>
</div>
<span class="text-headline-md font-bold text-on-surface">0</span>
</div>
<p class="text-on-surface-variant font-label-bold">ACTIVE PRESCRIPTIONS</p>
<p class="text-xs text-on-surface-variant/70 mt-1">0 renewed this month</p>
</div>
<!-- Pending Pickup -->
<div class="bg-white border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-4">
<div class="p-2 bg-secondary-container/30 rounded-lg text-secondary">
<span class="material-symbols-outlined">local_pharmacy</span>
</div>
<span class="text-headline-md font-bold text-on-surface">0</span>
</div>
<p class="text-on-surface-variant font-label-bold">PENDING PHARMACY PICKUP</p>
<p class="text-xs text-on-surface-variant/70 mt-1">Awaiting verification at CVS #421</p>
</div>
<!-- Renewal Requests -->
<div class="bg-white border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-4">
<div class="p-2 bg-error-container/30 rounded-lg text-error">
<span class="material-symbols-outlined">autorenew</span>
</div>
<span class="text-headline-md font-bold text-on-surface">0</span>
</div>
<p class="text-on-surface-variant font-label-bold">RENEWAL REQUESTS</p>
<p class="text-xs text-error font-bold mt-1">Requires immediate review</p>
</div>
</div>
<div class="grid grid-cols-12 gap-6 items-start">
<!-- Left: Prescription Table -->
<div class="col-span-12 lg:col-span-8 space-y-6">
<div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low flex items-center justify-between">
<h3 class="font-headline-md text-headline-md">Current Medications</h3>
<div class="flex items-center gap-2">
<button class="p-2 hover:bg-surface-container text-on-surface-variant rounded-lg">
<span class="material-symbols-outlined">filter_list</span>
</button>
<button class="p-2 hover:bg-surface-container text-on-surface-variant rounded-lg" onclick="window.print()">
<span class="material-symbols-outlined">print</span>
</button>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-6 py-3 text-label-caps text-on-surface-variant border-b border-outline-variant">MEDICATION NAME</th>
<th class="px-6 py-3 text-label-caps text-on-surface-variant border-b border-outline-variant">DOSAGE</th>
<th class="px-6 py-3 text-label-caps text-on-surface-variant border-b border-outline-variant">FREQUENCY</th>
<th class="px-6 py-3 text-label-caps text-on-surface-variant border-b border-outline-variant">DURATION</th>
<th class="px-6 py-3 text-label-caps text-on-surface-variant border-b border-outline-variant">PRESCRIBED BY</th>
<th class="px-6 py-3 text-label-caps text-on-surface-variant border-b border-outline-variant text-center">STATUS</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/30">
<?php if (empty($prescriptionRows)): ?>
<tr><td colspan="6" class="px-6 py-10 text-center text-body-sm text-on-surface-variant">No prescriptions found.</td></tr>
<?php else: ?>
<?php foreach ($prescriptionRows as $prescription): ?>
<?php
$isExpired = !empty($prescription['expiryDate']) && $prescription['expiryDate'] < date('Y-m-d');
$status = $isExpired ? 'Expired' : ($prescription['status'] ?: 'Active');
$statusClass = $isExpired ? 'bg-error-container text-on-error-container' : 'bg-primary-fixed text-on-primary-fixed';
?>
<tr class="hover:bg-surface-container-low/30 transition-colors">
<td class="px-6 py-4">
<div class="font-bold text-on-surface"><?php echo h($prescription['medicationName']); ?></div>
<div class="text-xs text-on-surface-variant"><?php echo h($prescription['patientFirstName'] . ' ' . $prescription['patientLastName']); ?></div>
</td>
<td class="px-6 py-4 text-on-surface"><?php echo h($prescription['dosage']); ?></td>
<td class="px-6 py-4 text-on-surface"><?php echo h($prescription['frequency']); ?></td>
<td class="px-6 py-4 text-on-surface"><?php echo h($prescription['duration']); ?></td>
<td class="px-6 py-4 text-on-surface"><?php echo h('Dr. ' . $prescription['doctorLastName']); ?></td>
<td class="px-6 py-4 text-center">
<span class="inline-block px-3 py-1 rounded-full <?php echo $statusClass; ?> text-label-bold uppercase"><?php echo h($status); ?></span>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
<!-- Interaction Alert History -->
<div class="bg-white border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
<div class="flex items-center gap-3 mb-4">
<span class="material-symbols-outlined text-tertiary">history</span>
<h3 class="font-headline-md text-headline-md">Interaction Logs</h3>
</div>
<div class="space-y-3">
</div>
</div>
</div>
<!-- Right: Quick Prescribe Sidebar -->
<div class="col-span-12 lg:col-span-4 space-y-6">
<div class="bg-white border border-outline-variant rounded-xl p-6 shadow-sm sticky top-24 hover:shadow-md transition-shadow duration-300">
<h3 class="font-headline-md text-headline-md mb-6">Quick Prescribe</h3>
<form class="space-y-5" onsubmit="return false;">
<div>
<label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Medication Search</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full bg-white border border-outline-variant rounded-lg py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="med-search" placeholder="e.g. Ibuprofen..." type="text"/>
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Dosage</label>
<input class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" placeholder="e.g. 200mg" type="text"/>
</div>
<div>
<label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Frequency</label>
<select class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all">
<option>Daily</option>
<option>BID (2x/day)</option>
<option>TID (3x/day)</option>
<option>QID (4x/day)</option>
<option>As needed</option>
</select>
</div>
</div>
<div>
<label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Route</label>
<select class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all">
<option>Oral</option>
<option>Intravenous</option>
<option>Topical</option>
<option>Subcutaneous</option>
</select>
</div>
<!-- Interaction Alert (Hidden by default) -->
<div class="hidden animate-pulse" id="interaction-alert">
<div class="bg-error-container border border-error/20 rounded-lg p-4 flex gap-3">
<span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1;">warning</span>
<div>
<p class="text-on-error-container font-bold text-sm">HIGH RISK INTERACTION</p>
<p class="text-on-error-container text-xs mt-1">Potassium chloride may interact with Lisinopril. Risk of hyperkalemia.</p>
</div>
</div>
</div>
<div class="pt-4 space-y-3">
<button class="w-full border border-primary text-primary font-label-bold py-3 rounded-lg hover:bg-primary/5 transition-all flex items-center justify-center gap-2" onclick="checkInteraction()">
<span class="material-symbols-outlined text-lg">verified_user</span>
                                CHECK FOR INTERACTIONS
                            </button>
<button class="w-full bg-primary text-on-primary font-label-bold py-3 rounded-lg hover:opacity-90 transition-all shadow-md">
                                ADD TO PRESCRIPTION
                            </button>
</div>
</form>
<!-- Pharmacy Note -->
<div class="mt-8 pt-6 border-t border-outline-variant">
<div class="flex items-center gap-2 mb-3">
<span class="material-symbols-outlined text-secondary text-sm">store</span>
<span class="font-label-bold text-on-surface-variant">PREFERRED PHARMACY</span>
</div>
<div class="bg-surface-container-low p-3 rounded-lg border border-outline-variant/30 hover:shadow-md transition-shadow duration-300 cursor-pointer">
<p class="text-body-md font-bold">CVS Pharmacy #421</p>
<p class="text-xs text-on-surface-variant mt-0.5">852 Clinical Way, San Francisco, CA</p>
<a class="text-primary text-xs font-bold mt-2 inline-block" href="#">Change Pharmacy</a>
</div>
</div>
</div>
</div>
</div>
</main>

<!-- New Prescription Modal -->
<div class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center" id="new-prescription-modal">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
        <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between bg-surface-container-low">
            <h3 class="font-headline-md text-headline-md">Create New Prescription</h3>
            <button onclick="closeNewPrescriptionModal()" class="hover:bg-surface-container rounded-full p-2 transition-all">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>
        <form id="new-prescription-form" class="p-6 space-y-5" onsubmit="submitNewPrescription(event)">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Patient Name</label>
                    <input class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="patient-name" placeholder="Enter patient name" type="text" required/>
                </div>
                <div>
                    <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Patient ID</label>
                    <input class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="patient-id" placeholder="Enter patient ID" type="text" required/>
                </div>
            </div>
            <div>
                <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Medication Name</label>
                <input class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="medication-name" placeholder="e.g. Amoxicillin" type="text" required/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Dosage</label>
                    <input class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="dosage" placeholder="e.g. 250mg" type="text" required/>
                </div>
                <div>
                    <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Frequency</label>
                    <select class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="frequency" required>
                        <option value="">Select frequency</option>
                        <option>Daily</option>
                        <option>BID (2x/day)</option>
                        <option>TID (3x/day)</option>
                        <option>QID (4x/day)</option>
                        <option>As needed</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Duration</label>
                    <input class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="duration" placeholder="e.g. 10 Days" type="text" required/>
                </div>
                <div>
                    <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Route</label>
                    <select class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="route" required>
                        <option value="">Select route</option>
                        <option>Oral</option>
                        <option>Intravenous</option>
                        <option>Topical</option>
                        <option>Subcutaneous</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Prescribed By</label>
                <input class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="prescribed-by" placeholder="Dr. Name" type="text" required/>
            </div>
            <div>
                <label class="block text-label-bold text-on-surface-variant mb-1.5 uppercase">Notes</label>
                <textarea class="w-full bg-white border border-outline-variant rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-primary focus:border-primary text-body-md transition-all" id="notes" placeholder="Additional instructions..." rows="3"></textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeNewPrescriptionModal()" class="flex-1 border border-outline-variant text-on-surface font-label-bold py-3 rounded-lg hover:bg-surface-container transition-all">
                    CANCEL
                </button>
                <button type="submit" class="flex-1 bg-primary text-on-primary font-label-bold py-3 rounded-lg hover:opacity-90 transition-all shadow-md">
                    CREATE PRESCRIPTION
                </button>
            </div>
        </form>
    </div>
</div>

<script>
        function openNewPrescriptionModal() {
            const modal = document.getElementById('new-prescription-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeNewPrescriptionModal() {
            const modal = document.getElementById('new-prescription-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('new-prescription-form').reset();
        }

        function submitNewPrescription(event) {
            event.preventDefault();
            
            const medicationName = document.getElementById('medication-name').value;
            const dosage = document.getElementById('dosage').value;
            const frequency = document.getElementById('frequency').value;
            const duration = document.getElementById('duration').value;
            const prescribedBy = document.getElementById('prescribed-by').value;
            
            // Add to table
            const tbody = document.querySelector('tbody');
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-surface-container-low/30 transition-colors';
            newRow.innerHTML = `
                <td class="px-6 py-4">
                    <div class="font-bold text-on-surface">${medicationName}</div>
                    <div class="text-xs text-on-surface-variant">Prescription</div>
                </td>
                <td class="px-6 py-4 text-on-surface">${dosage}</td>
                <td class="px-6 py-4 text-on-surface">${frequency}</td>
                <td class="px-6 py-4 text-on-surface">${duration}</td>
                <td class="px-6 py-4 text-on-surface">${prescribedBy}</td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-block px-3 py-1 rounded-full bg-primary/10 text-primary text-label-bold">ACTIVE</span>
                </td>
            `;
            
            // Insert after the header row
            tbody.insertBefore(newRow, tbody.firstChild);
            
            // Update active prescriptions count
            const countElement = document.querySelector('.text-headline-md.font-bold.text-on-surface');
            if (countElement) {
                countElement.textContent = parseInt(countElement.textContent) + 1;
            }
            
            closeNewPrescriptionModal();
            alert('Prescription created successfully!');
        }

        function checkInteraction() {
            const medInput = document.getElementById('med-search').value.toLowerCase();
            const alertBox = document.getElementById('interaction-alert');
            
            // Simulating an interaction check for demonstration
            if (medInput.includes('potassium') || medInput.includes('kcl')) {
                alertBox.classList.remove('hidden');
                alertBox.classList.add('flex');
            } else {
                alertBox.classList.add('hidden');
                alertBox.classList.remove('flex');
                alert('Interaction Check: No major contraindications found for this medication with the current patient profile.');
            }
        }

        // Make Quick Prescribe form functional
        document.querySelector('.sticky.top-24 form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const medSearch = document.getElementById('med-search').value;
            const dosage = this.querySelector('input[placeholder="e.g. 200mg"]').value;
            const frequency = this.querySelector('select:nth-of-type(1)').value;
            const route = this.querySelector('select:nth-of-type(2)').value;
            
            if (!medSearch || !dosage) {
                alert('Please fill in medication name and dosage.');
                return;
            }
            
            // Add to table
            const tbody = document.querySelector('tbody');
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-surface-container-low/30 transition-colors';
            newRow.innerHTML = `
                <td class="px-6 py-4">
                    <div class="font-bold text-on-surface">${medSearch}</div>
                    <div class="text-xs text-on-surface-variant">Quick Prescribe</div>
                </td>
                <td class="px-6 py-4 text-on-surface">${dosage}</td>
                <td class="px-6 py-4 text-on-surface">${frequency}</td>
                <td class="px-6 py-4 text-on-surface">As prescribed</td>
                <td class="px-6 py-4 text-on-surface">Dr. Current</td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-block px-3 py-1 rounded-full bg-primary/10 text-primary text-label-bold">ACTIVE</span>
                </td>
            `;
            
            tbody.insertBefore(newRow, tbody.firstChild);
            
            // Update active prescriptions count
            const countElement = document.querySelector('.text-headline-md.font-bold.text-on-surface');
            if (countElement) {
                countElement.textContent = parseInt(countElement.textContent) + 1;
            }
            
            // Reset form
            this.reset();
            alert('Medication added to prescription successfully!');
        });

        // Add visual micro-interactions to table rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.cursor = 'pointer';
            });
        });

        // Close modal when clicking outside
        document.getElementById('new-prescription-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeNewPrescriptionModal();
            }
        });
    </script>
</body></html>
