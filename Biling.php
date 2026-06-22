<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Handle AJAX requests for billing operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'];
    
    if ($action === 'add_bill') {
        $stmt = $conn->prepare('
            INSERT INTO billing (patientId, appointmentId, description, amount, status, billingDate, paymentMethod, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        
        $appointmentId = !empty($_POST['appointmentId']) ? (int)$_POST['appointmentId'] : null;
        
        $stmt->bind_param('iisdssss',
            $_POST['patientId'],
            $appointmentId,
            $_POST['description'],
            $_POST['amount'],
            $_POST['status'],
            $_POST['billingDate'],
            $_POST['paymentMethod'],
            $_POST['notes']
        );
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Bill created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'delete_bill') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare('DELETE FROM billing WHERE id = ?');
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $stmt->error]);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'update_payment_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $paymentDate = $_POST['paymentDate'];
        $paymentMethod = $_POST['paymentMethod'];
        
        $stmt = $conn->prepare('UPDATE billing SET status = ?, paymentDate = ?, paymentMethod = ? WHERE id = ?');
        $stmt->bind_param('sssi', $status, $paymentDate, $paymentMethod, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $stmt->error]);
        }
        $stmt->close();
        exit;
    }
}

// Get all bills for API response
if (isset($_GET['api']) && $_GET['api'] === 'get_bills') {
    header('Content-Type: application/json');
    
    $result = $conn->query('
        SELECT b.id, b.patientId, b.description, b.amount, b.status, b.billingDate, b.paymentDate, b.paymentMethod, b.notes,
               p.firstName as patientFirstName, p.lastName as patientLastName
        FROM billing b 
        JOIN patients p ON b.patientId = p.id 
        ORDER BY b.billingDate DESC
    ');
    $bills = [];
    
    while ($row = $result->fetch_assoc()) {
        $bills[] = $row;
    }
    
    echo json_encode($bills);
    exit;
}

// Get statistics
if (isset($_GET['api']) && $_GET['api'] === 'get_stats') {
    header('Content-Type: application/json');
    
    $total = $conn->query('SELECT COUNT(*) as count FROM billing')->fetch_assoc()['count'];
    $pending = $conn->query("SELECT COUNT(*) as count FROM billing WHERE status = 'Pending'")->fetch_assoc()['count'];
    $paid = $conn->query("SELECT COUNT(*) as count FROM billing WHERE status = 'Paid'")->fetch_assoc()['count'];
    
    $totalAmount = $conn->query('SELECT COALESCE(SUM(amount), 0) as total FROM billing')->fetch_assoc()['total'];
    $paidAmount = $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM billing WHERE status = 'Paid'")->fetch_assoc()['total'];
    
    echo json_encode([
        'total' => $total,
        'pending' => $pending,
        'paid' => $paid,
        'totalAmount' => $totalAmount,
        'paidAmount' => $paidAmount
    ]);
    exit;
}

function h($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$billingRows = [];
$billingResult = $conn->query('
    SELECT b.id, b.patientId, b.description, b.amount, b.status, b.billingDate, b.paymentMethod, b.notes,
           p.firstName as patientFirstName, p.lastName as patientLastName,
           p.insurance_provider
    FROM billing b
    JOIN patients p ON b.patientId = p.id
    ORDER BY COALESCE(b.billingDate, DATE(b.created_at)) DESC, b.id DESC
');
if ($billingResult) {
    while ($row = $billingResult->fetch_assoc()) {
        $billingRows[] = $row;
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Billing &amp; Invoicing | MedLab Pro</title>
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
                        "surface-container-low": "#f0f5f2",
                        "on-error-container": "#93000a",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-fixed-variant": "#43474b",
                        "on-secondary-fixed-variant": "#1c4a6a",
                        "tertiary": "#585d60",
                        "outline": "#6d7a77",
                        "outline-variant": "#bdc9c5",
                        "on-background": "#171d1b",
                        "tertiary-container": "#707579",
                        "surface-container-high": "#e4e9e7",
                        "primary-fixed": "#8ff4e3",
                        "on-surface": "#171d1b",
                        "background": "#f6faf8",
                        "on-primary-fixed": "#00201c",
                        "surface-container": "#eaefec",
                        "on-tertiary-container": "#fbfcff",
                        "surface-dim": "#d6dbd9",
                        "surface": "#f6faf8",
                        "surface-variant": "#dfe4e1",
                        "inverse-on-surface": "#edf2ef",
                        "primary": "#00685d",
                        "on-primary-container": "#f4fffb",
                        "secondary": "#376283",
                        "secondary-container": "#aed9ff",
                        "on-error": "#ffffff",
                        "on-secondary": "#ffffff",
                        "surface-container-highest": "#dfe4e1",
                        "surface-bright": "#f6faf8",
                        "secondary-fixed": "#cbe6ff",
                        "on-secondary-container": "#345f80",
                        "primary-container": "#008376",
                        "surface-tint": "#006b5f",
                        "error": "#ba1a1a",
                        "on-primary-fixed-variant": "#005047",
                        "on-primary": "#ffffff",
                        "tertiary-fixed": "#dfe3e7",
                        "primary-fixed-dim": "#72d8c8",
                        "inverse-surface": "#2c3130",
                        "inverse-primary": "#72d8c8",
                        "on-tertiary-fixed": "#171c1f",
                        "on-surface-variant": "#3d4946",
                        "secondary-fixed-dim": "#a1cbf0",
                        "tertiary-fixed-dim": "#c3c7cb",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-fixed": "#001e30",
                        "error-container": "#ffdad6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "container-padding": "24px",
                        "sidebar-width": "260px",
                        "stack-md": "16px",
                        "stack-sm": "8px",
                        "gutter": "20px",
                        "card-gap": "16px"
                    },
                    "fontFamily": {
                        "headline-md": ["Manrope"],
                        "body-md": ["Inter"],
                        "label-caps": ["Inter"],
                        "label-bold": ["Inter"],
                        "body-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-lg": ["Manrope"]
                    },
                    "fontSize": {
                        "headline-md": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "600"}],
                        "label-bold": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                        "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg": ["24px", {"lineHeight": "32px", "fontWeight": "700"}]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #f6faf8;
            color: #171d1b;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .bento-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .bento-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="font-body-md overflow-x-hidden">
<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-screen w-[260px] border-r border-outline-variant/10 flex flex-col py-6 z-50" style="background-color: #00685D;">
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
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Prescription.php">
<span class="material-symbols-outlined">description</span>
<span class="font-label-bold text-label-bold">Prescription</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 bg-surface-variant/20 text-surface-bright rounded-lg mx-2 my-1 opacity-100 transition-colors" href="#">
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


</div>
</aside>
<!-- Main Content Shell -->
<main class="ml-[260px] min-h-screen flex flex-col">
<!-- TopAppBar -->
<header class="sticky top-0 z-40 bg-surface border-b border-outline-variant flex justify-between items-center h-16 px-container-padding">
<div class="flex items-center gap-6 flex-1">
<div class="relative w-full max-w-md group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
<input class="w-full bg-surface-container border border-outline-variant rounded-full py-2 pl-10 pr-4 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" placeholder="Search Invoices, Patients, or IDs..." type="text"/>
</div>
<div class="flex items-center gap-4">
<button class="flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors">
<span class="font-label-bold text-label-bold">Filter By</span>
<span class="material-symbols-outlined text-sm">keyboard_arrow_down</span>
</button>
</div>
</div>
<div class="flex items-center gap-4">
<button class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-surface"></span>
</button>
</div>
</header>
<!-- Content Canvas -->
<div class="p-container-padding space-y-gutter">
<!-- Summary Bento Grid -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-card-gap">
<div class="bento-card p-6 rounded-xl flex flex-col justify-between">
<div class="flex justify-between items-start">
<div class="w-12 h-12 rounded-xl bg-primary-fixed/30 flex items-center justify-center">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
</div>
<span class="text-[10px] bg-primary-fixed text-on-primary-fixed px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">+12.5%</span>
</div>
<div class="mt-4">
<p class="text-on-surface-variant font-label-bold text-label-bold uppercase tracking-widest">Outstanding Invoices</p>
<h2 class="text-[32px] font-bold text-on-surface mt-1 leading-none">₱0</h2>
<p class="text-body-sm text-on-surface-variant mt-2">Across 0 active patient records</p>
</div>
</div>
<div class="bento-card p-6 rounded-xl flex flex-col justify-between">
<div class="flex justify-between items-start">
<div class="w-12 h-12 rounded-xl bg-secondary-fixed/30 flex items-center justify-center">
<span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">security</span>
</div>
<span class="text-[10px] bg-secondary-fixed text-on-secondary-fixed px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Pending</span>
</div>
<div class="mt-4">
<p class="text-on-surface-variant font-label-bold text-label-bold uppercase tracking-widest">Pending Insurance Claims</p>
<h2 class="text-[32px] font-bold text-on-surface mt-1 leading-none">0</h2>
<div class="w-full bg-surface-container h-2 rounded-full mt-4 overflow-hidden">
<div class="bg-primary h-full w-[65%]"></div>
</div>
<p class="text-body-sm text-on-surface-variant mt-2">0% processing rate this week</p>
</div>
</div>
<div class="bento-card p-6 rounded-xl bg-inverse-surface border-none flex flex-col justify-between overflow-hidden relative">
<!-- Subtle background decoration -->
<div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
<div class="flex justify-between items-start relative z-10">
<div class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center">
<span class="material-symbols-outlined text-primary-fixed-dim" style="font-variation-settings: 'FILL' 1;">payments</span>
</div>
<button class="text-surface-variant hover:text-surface-bright transition-colors">
<span class="material-symbols-outlined">more_horiz</span>
</button>
</div>
<h2 class="text-[32px] font-bold text-surface-bright mt-1 leading-none">₱0</h2>
<div class="mt-4 relative z-10">
<p class="text-surface-variant font-label-bold text-label-bold uppercase tracking-widest">Total Revenue (Monthly)</p>
<p class="text-body-sm text-surface-variant opacity-80 mt-2">Fiscal cycle ends in days</p>
</div>
</div>
</section>
<!-- Main Workspace Grid -->
<div class="grid grid-cols-12 gap-gutter">
<!-- Table Area -->
<section class="col-span-12 lg:col-span-9 space-y-gutter">
<!-- Filters & Search -->
<div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-surface-container rounded-xl border border-outline-variant hover:shadow-md transition-shadow duration-300">
<div class="flex items-center gap-3">
<div class="flex items-center gap-2 bg-surface-container-lowest border border-outline-variant px-3 py-1.5 rounded-lg">
<span class="material-symbols-outlined text-sm text-on-surface-variant">calendar_today</span>
<span class="text-body-sm">Last 30 Days</span>
</div>
<div class="flex items-center gap-2 bg-surface-container-lowest border border-outline-variant px-3 py-1.5 rounded-lg">
<span class="material-symbols-outlined text-sm text-on-surface-variant">filter_list</span>
<span class="text-body-sm">All Statuses</span>
</div>
<div class="flex items-center gap-2 bg-surface-container-lowest border border-outline-variant px-3 py-1.5 rounded-lg">
<span class="text-body-sm">Aetna, BlueShield...</span>
</div>
</div>
<div class="flex items-center gap-2">
<button class="text-primary hover:bg-primary/10 px-3 py-1.5 rounded-lg transition-colors font-label-bold text-label-bold">Clear Filters</button>
<button class="bg-surface-container-high p-1.5 rounded-lg border border-outline-variant">
<span class="material-symbols-outlined text-sm">download</span>
</button>
</div>
</div>
<!-- Invoices Table -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
<table class="w-full text-left border-collapse">
<thead class="bg-[#F1F5F9] border-b border-outline-variant">
<tr>
<th class="px-6 py-4 text-label-caps font-label-caps uppercase text-on-surface-variant">Invoice ID</th>
<th class="px-6 py-4 text-label-caps font-label-caps uppercase text-on-surface-variant">Patient Name</th>
<th class="px-6 py-4 text-label-caps font-label-caps uppercase text-on-surface-variant">Service / Test</th>
<th class="px-6 py-4 text-label-caps font-label-caps uppercase text-on-surface-variant text-right">Amount</th>
<th class="px-6 py-4 text-label-caps font-label-caps uppercase text-on-surface-variant">Insurance</th>
<th class="px-6 py-4 text-label-caps font-label-caps uppercase text-on-surface-variant text-center">Status</th>
<th class="px-6 py-4"></th>
</tr>
</thead>
<tbody class="divide-y divide-[#F1F5F9]">
<?php if (empty($billingRows)): ?>
<tr><td colspan="7" class="px-6 py-10 text-center text-body-sm text-on-surface-variant">No billing records found.</td></tr>
<?php else: ?>
<?php foreach ($billingRows as $bill): ?>
<?php $statusClass = strtolower($bill['status']) === 'paid' ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-surface-container-high text-on-surface-variant'; ?>
<tr class="hover:bg-surface-container-low transition-colors">
<td class="px-6 py-4"><span class="text-label-bold font-label-bold text-on-surface">#INV-<?php echo str_pad((string)$bill['id'], 4, '0', STR_PAD_LEFT); ?></span></td>
<td class="px-6 py-4">
<div class="flex flex-col">
<span class="text-body-md font-bold text-on-surface"><?php echo h($bill['patientFirstName'] . ' ' . $bill['patientLastName']); ?></span>
<span class="text-[11px] text-on-surface-variant">ID: PR-<?php echo h($bill['patientId']); ?></span>
</div>
</td>
<td class="px-6 py-4"><span class="text-body-sm text-on-surface-variant"><?php echo h($bill['description'] ?: 'General service'); ?></span></td>
<td class="px-6 py-4 text-right"><span class="text-body-md font-bold">PHP <?php echo number_format((float)$bill['amount'], 2); ?></span></td>
<td class="px-6 py-4"><span class="px-2 py-1 rounded bg-surface-variant text-on-surface-variant text-[10px] font-bold uppercase"><?php echo h($bill['insurance_provider'] ?: 'Self Pay'); ?></span></td>
<td class="px-6 py-4 text-center"><span class="px-2 py-1 rounded <?php echo $statusClass; ?> text-[10px] font-bold uppercase"><?php echo h($bill['status'] ?: 'Pending'); ?></span></td>
<td class="px-6 py-4 text-right">
<button class="text-on-surface-variant hover:text-primary transition-colors" title="<?php echo h($bill['notes'] ?: 'View bill'); ?>">
<span class="material-symbols-outlined text-lg">visibility</span>
</button>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
<div class="px-6 py-4 bg-surface flex items-center justify-between border-t border-outline-variant">
<span class="text-body-sm text-on-surface-variant">Showing <?php echo count($billingRows); ?> billing records</span>
<div class="flex items-center gap-2">
<button class="p-2 rounded hover:bg-surface-container transition-colors disabled:opacity-30" disabled="">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="w-8 h-8 rounded bg-primary text-on-primary font-label-bold text-label-bold">1</button>
<button class="w-8 h-8 rounded hover:bg-surface-container transition-colors font-label-bold text-label-bold">2</button>
<button class="w-8 h-8 rounded hover:bg-surface-container transition-colors font-label-bold text-label-bold">3</button>
<button class="p-2 rounded hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</div>
</div>
</div>
</section>
<!-- Sidebar Area -->
<aside class="col-span-12 lg:col-span-3 space-y-gutter">
<!-- Quick Bill Panel -->
<section class="bento-card p-6 rounded-xl">
<div class="flex items-center gap-2 mb-6">
<span class="material-symbols-outlined text-primary">bolt</span>
<h3 class="font-headline-md text-headline-md text-on-surface">Quick Bill</h3>
</div>
<form class="space-y-4">
<div>
<label class="block text-label-bold font-label-bold text-on-surface-variant mb-1">Patient Search</label>
<div class="relative">
<span class="material-symbols-outlined absolute right-3 top-2 text-sm text-outline">search</span>
<input class="w-full bg-surface border border-outline-variant rounded-lg p-2 text-body-sm focus:ring-1 focus:ring-primary outline-none" placeholder="Name or Patient ID" type="text"/>
</div>
</div>
<div>
<label class="block text-label-bold font-label-bold text-on-surface-variant mb-1">Select Procedure</label>
<select class="w-full bg-surface border border-outline-variant rounded-lg p-2 text-body-sm focus:ring-1 focus:ring-primary outline-none">
<option>Lipid Panel - $45.00</option>
<option>Glucose Test - $15.00</option>
<option>Blood Typing - $30.00</option>
<option>Custom Charge...</option>
</select>
</div>
<div class="pt-2">
<button class="w-full bg-primary text-on-primary py-2.5 rounded-lg font-label-bold text-label-bold hover:bg-primary/90 transition-all flex items-center justify-center gap-2" type="button">
<span class="material-symbols-outlined text-sm">receipt</span>
                                    Generate Draft
                                </button>
</div>
</form>
</section>
<!-- Recent Payments Activity -->
<section class="bento-card p-6 rounded-xl">
<div class="flex justify-between items-center mb-6">
<h3 class="font-headline-md text-headline-md text-on-surface">Recent Activity</h3>
<button class="text-primary text-[11px] font-bold uppercase tracking-wider hover:underline">View All</button>
</div>
<div class="space-y-6">
<div class="flex gap-3">
<div class="w-8 h-8 rounded-full bg-[#DEF7EC] flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[#03543F] text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<div>
<p class="text-body-sm text-on-surface leading-tight">Payment received from <strong>Elena Rodriguez</strong> via Stripe.</p>
<div class="flex items-center gap-2 mt-1">
<span class="text-[10px] text-on-surface-variant">2 mins ago</span>
<span class="w-1 h-1 bg-outline-variant rounded-full"></span>
<span class="text-[10px] font-bold text-primary">$245.00</span>
</div>
</div>
</div>
<div class="flex gap-3">
<div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-on-secondary-container text-sm">mail</span>
</div>
<div>
<p class="text-body-sm text-on-surface leading-tight">Claim #CLA-7712 approved by <strong>Aetna Health</strong>.</p>
<div class="flex items-center gap-2 mt-1">
<span class="text-[10px] text-on-surface-variant">45 mins ago</span>
<span class="w-1 h-1 bg-outline-variant rounded-full"></span>
<span class="text-[10px] font-bold text-secondary">$1,120.00</span>
</div>
</div>
</div>
<div class="flex gap-3">
<div class="w-8 h-8 rounded-full bg-error-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-on-error-container text-sm">warning</span>
</div>
<div>
<p class="text-body-sm text-on-surface leading-tight">Invoice #INV-9822 for <strong>Marcus Thorne</strong> became overdue.</p>
<div class="flex items-center gap-2 mt-1">
<span class="text-[10px] text-on-surface-variant">2 hours ago</span>
<span class="w-1 h-1 bg-outline-variant rounded-full"></span>
<span class="text-[10px] font-bold text-error">Reminder Sent</span>
</div>
</div>
</div>
<div class="flex gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-on-surface-variant text-sm">receipt_long</span>
</div>
<div>
<p class="text-body-sm text-on-surface leading-tight">New recurring billing cycle started for <strong>Lab Partner B</strong>.</p>
<div class="flex items-center gap-2 mt-1">
<span class="text-[10px] text-on-surface-variant">4 hours ago</span>
</div>
</div>
</div>
</div>
</section>
<!-- Revenue Breakdown Card -->
<section class="bento-card overflow-hidden rounded-xl bg-gradient-to-br from-surface to-surface-container">
<div class="p-6">
<h3 class="font-headline-md text-headline-md text-on-surface mb-4">Revenue Breakdown</h3>
<div class="space-y-4">
<div class="flex justify-between items-end">
<span class="text-body-sm text-on-surface-variant">Direct Payment</span>
<span class="text-body-sm font-bold">42%</span>
</div>
<div class="w-full h-1.5 bg-surface-container-high rounded-full overflow-hidden">
<div class="bg-primary h-full w-[42%]"></div>
</div>
<div class="flex justify-between items-end">
<span class="text-body-sm text-on-surface-variant">Insurance Reimbursement</span>
<span class="text-body-sm font-bold">58%</span>
</div>
<div class="w-full h-1.5 bg-surface-container-high rounded-full overflow-hidden">
<div class="bg-secondary h-full w-[58%]"></div>
</div>
</div>
</div>
<img alt="Abstract Data Visualization" class="w-full h-24 object-cover opacity-20 grayscale" data-alt="A clean, minimalist abstract background representing medical data visualization with subtle teal and blue gradients. The image features thin, elegant lines and glowing data points that evoke a sense of high-tech clinical precision and financial clarity. The lighting is bright and modern, fitting a professional medical software interface." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBCB5W_jIHigUsIAvak5sU3GyOnAGZZd0C7LeYBuBfU2U1vEn719E2SRZX2Oq942LNtVhNlrbQsyH6wasB0Uly376PV7JaoUG4BOZ93LHl2yxk1RcFJOSn_D7MjbX4LWacdiGGFbqVXPuuS2Zk23-SrtIf99YLKS3zrZwI0ipX41xiDVigqv3IIX_Mytl2Km8qwm_xm5C4CIq1-ArcPxYccRUtXhr-f29JTVWoa37_tKnd9tQGDeazo6PC-P-tebw3VjgxizUDMYc0"/>
</section>
</aside>
</div>
</div>
<!-- FAB for Mobile (Contextual suppression applied but added for UI demo if screen was smaller) -->
<!-- Hidden on large screens as per instructions to suppress navigation/FAB on desktop main pages if sidebar is present -->
</main>
<script>
        // Micro-interactions
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('mousedown', () => {
                button.classList.add('scale-95');
            });
            button.addEventListener('mouseup', () => {
                button.classList.remove('scale-95');
            });
            button.addEventListener('mouseleave', () => {
                button.classList.remove('scale-95');
            });
        });

        // Search highlight effect
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-primary/20');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-primary/20');
        });
    </script>
</body></html>
