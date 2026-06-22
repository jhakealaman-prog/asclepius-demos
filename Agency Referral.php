<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Agency Referrals - MedLab Pro</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet"/>
<!-- Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "tertiary-container": "#707579",
                    "error": "#ba1a1a",
                    "on-primary-container": "#f4fffb",
                    "on-primary-fixed-variant": "#005047",
                    "outline": "#6d7a77",
                    "on-surface": "#171d1b",
                    "primary-fixed-dim": "#72d8c8",
                    "on-tertiary": "#ffffff",
                    "on-error": "#ffffff",
                    "error-container": "#ffdad6",
                    "inverse-surface": "#2c3130",
                    "on-tertiary-container": "#fbfcff",
                    "outline-variant": "#bdc9c5",
                    "surface-container": "#eaefec",
                    "primary-container": "#008376",
                    "surface-tint": "#006b5f",
                    "on-secondary-fixed-variant": "#1c4a6a",
                    "surface-dim": "#d6dbd9",
                    "surface-bright": "#f6faf8",
                    "surface-container-high": "#e4e9e7",
                    "on-primary-fixed": "#00201c",
                    "on-primary": "#ffffff",
                    "inverse-primary": "#72d8c8",
                    "on-tertiary-fixed-variant": "#43474b",
                    "on-tertiary-fixed": "#171c1f",
                    "surface-variant": "#dfe4e1",
                    "on-secondary-fixed": "#001e30",
                    "secondary-fixed-dim": "#a1cbf0",
                    "on-error-container": "#93000a",
                    "inverse-on-surface": "#edf2ef",
                    "secondary-container": "#aed9ff",
                    "tertiary": "#585d60",
                    "surface-container-highest": "#dfe4e1",
                    "secondary-fixed": "#cbe6ff",
                    "primary": "#00685d",
                    "on-background": "#171d1b",
                    "tertiary-fixed-dim": "#c3c7cb",
                    "background": "#f6faf8",
                    "primary-fixed": "#8ff4e3",
                    "on-secondary": "#ffffff",
                    "surface": "#f6faf8",
                    "surface-container-low": "#f0f5f2",
                    "tertiary-fixed": "#dfe3e7",
                    "secondary": "#376283",
                    "on-surface-variant": "#3d4946",
                    "on-secondary-container": "#345f80",
                    "surface-container-lowest": "#ffffff"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "container-padding": "24px",
                    "gutter": "20px",
                    "stack-md": "16px",
                    "card-gap": "16px",
                    "sidebar-width": "260px",
                    "stack-sm": "8px"
            },
            "fontFamily": {
                    "body-md": ["Inter"],
                    "body-lg": ["Inter"],
                    "headline-md": ["Manrope"],
                    "headline-lg": ["Manrope"],
                    "label-bold": ["Inter"],
                    "label-caps": ["Inter"],
                    "body-sm": ["Inter"]
            },
            "fontSize": {
                    "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                    "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "headline-md": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                    "headline-lg": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                    "label-bold": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                    "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "600"}],
                    "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-nav-item {
            background-color: #005047; /* style_active_navigation from JSON */
            color: #f4fffb;
            font-weight: 700;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-family: 'Inter';
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .sidebar-width { width: 260px; }
        .ml-sidebar-width { margin-left: 260px; }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md overflow-x-hidden">
<!-- SideNavBar (Predicted & Structured via JSON) -->
<aside class="text-primary-fixed dark:text-primary-fixed-dim docked h-screen w-sidebar fixed left-0 top-0 flex flex-col h-full py-6 z-50" style="background-color: #00685D;">
<div class="px-6 mb-8">
<h1 class="text-headline-md font-headline-md font-bold text-surface-container-lowest">ASCLEPIUS Medical &<br> Diagnostic Group Inc.</h1>
<p class="text-label-bold text-surface-variant/60 font-label-bold">Laboratory Information System</p>
</div>
  
    <nav class="flex-1 px-4 space-y-1">

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
<a class="flex items-center gap-3 px-3 py-2 bg-surface-variant/20 text-surface-bright rounded-lg mx-2 my-1 opacity-100 transition-colors" href="#">
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
<!-- TopAppBar -->
<header class="flex justify-between items-center h-16 w-[calc(100%-260px)] px-container-padding sticky top-0 z-40 ml-sidebar-width bg-surface-container-lowest border-b border-outline-variant">
<div class="flex items-center bg-surface-container rounded-full px-4 py-2 w-96">
<span class="material-symbols-outlined text-on-surface-variant mr-2">search</span>
<input class="bg-transparent border-none focus:ring-0 text-body-md w-full placeholder:text-on-surface-variant/60" placeholder="Search referrals, patients, or agencies..." type="text"/>
</div>
<div class="flex items-center gap-4">
<button class="p-2 hover:bg-surface-container rounded-full transition-colors relative">
<span class="material-symbols-outlined text-on-surface-variant">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-surface-container-lowest"></span>
</button>
</div>
</header>
<!-- Main Workspace -->
<main class="ml-sidebar-width p-container-padding transition-all">
<!-- Screen Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Agency Referrals</h2>
<p class="text-on-surface-variant text-body-md">Manage and track outbound diagnostic tests sent to external laboratory partners.</p>
</div>
<button class="flex items-center gap-2 bg-primary text-on-primary px-6 py-3 rounded-lg font-bold transition-all hover:shadow-lg active:scale-95">
<span class="material-symbols-outlined">add_circle</span>
<span>New Referral</span>
</button>
</div>
<!-- Metrics Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-card-gap mb-8">
<div class="bg-surface-container-lowest p-container-padding border border-outline-variant rounded-xl shadow-[0px_4px_20px_-12px_rgba(0,0,0,0.05)] hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-4">
<div class="w-10 h-10 bg-primary-container/10 flex items-center justify-center rounded-lg hover:bg-primary-container/20 transition-colors">
<span class="material-symbols-outlined text-primary" data-icon="assignment_ind">assignment_ind</span>
</div>
<span class="text-[11px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full">+12%</span>
</div>
<p class="text-label-bold font-label-bold text-outline uppercase">Active Referrals</p>
<p class="font-headline-lg text-headline-lg text-on-surface">0</p>
</div>
<div class="bg-surface-container-lowest p-container-padding border border-outline-variant rounded-xl shadow-[0px_4px_20px_-12px_rgba(0,0,0,0.05)] hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-4">
<div class="w-10 h-10 bg-secondary-container/20 flex items-center justify-center rounded-lg hover:bg-secondary-container/30 transition-colors">
<span class="material-symbols-outlined text-secondary" data-icon="location_on">location_on</span>
</div>
</div>
<p class="text-label-bold font-label-bold text-outline uppercase">Pending Local Hires</p>
<p class="font-headline-lg text-headline-lg text-on-surface">0</p>
</div>
<div class="bg-surface-container-lowest p-container-padding border border-outline-variant rounded-xl shadow-[0px_4px_20px_-12px_rgba(0,0,0,0.05)] hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-4">
<div class="w-10 h-10 bg-tertiary-container/10 flex items-center justify-center rounded-lg hover:bg-tertiary-container/20 transition-colors">
<span class="material-symbols-outlined text-tertiary" data-icon="flight_takeoff">flight_takeoff</span>
</div>
<span class="text-[11px] font-bold text-tertiary bg-tertiary/10 px-2 py-0.5 rounded-full">TRANSIT</span>
</div>
<p class="text-label-bold font-label-bold text-outline uppercase">Abroad Hires in Transit</p>
<p class="font-headline-lg text-headline-lg text-on-surface">0</p>
</div>
<div class="bg-primary text-on-primary p-container-padding rounded-xl shadow-[0px_8px_30px_-10px_rgba(0,104,93,0.3)] hover:shadow-xl hover:scale-[1.02] transition-all duration-300 cursor-pointer">
<div class="flex items-center justify-between mb-4">
<div class="w-10 h-10 bg-white/20 flex items-center justify-center rounded-lg hover:bg-white/30 transition-colors">
<span class="material-symbols-outlined text-white" data-icon="verified">verified</span>
</div>
</div>
<p class="text-label-bold font-label-bold text-white/70 uppercase">Total Placements</p>
<p class="font-headline-lg text-headline-lg text-white">0</p>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-gutter">
<!-- Filter & Table Area -->
<div class="lg:col-span-4 space-y-gutter">
<!-- Filter Bar -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
<div class="px-container-padding py-4 bg-surface-container border-b border-outline-variant flex items-center justify-between">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary" data-icon="home_work">home_work</span>
<h3 class="font-headline-md text-headline-md text-on-surface">Local Hire</h3>
</div>
<div class="flex items-center gap-2">
<button class="text-label-bold font-label-bold text-primary px-3 py-1.5 hover:bg-primary/5 rounded transition-colors uppercase">View Analytics</button>
<span class="material-symbols-outlined text-outline cursor-pointer hover:bg-surface-container-low p-1 rounded-full transition-colors" data-icon="more_vert">more_vert</span>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant">
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Candidate Name</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Agency Name</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Role</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Referral Date</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Status</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/30">
<tr class="hover:bg-surface-container-low/50 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-xs">RM</div>
<span class="font-bold text-on-surface">(ex)Jake Alaman</span>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface-variant">(ex)Metro Medical Talent</td>
<td class="px-6 py-4">
<span class="text-body-sm font-medium px-2 py-1 bg-surface-container-high rounded text-on-surface-variant">Lab Technician</span>
</td>
<td class="px-6 py-4 text-body-md text-on-surface-variant">(ex)Oct 12, 2025</td>
<td class="px-6 py-4">
<span class="text-[10px] font-extrabold px-2.5 py-1 rounded bg-secondary-container/40 text-on-secondary-container uppercase hover:bg-secondary-container/50 cursor-pointer transition-colors">(ex)Interviewing</span>
</td>
<td class="px-6 py-4 text-right">
<button class="text-primary hover:bg-primary/10 p-1 rounded transition-colors">
<span class="material-symbols-outlined" data-icon="visibility">visibility</span>
</button>
</td>
</tr>

</tbody>
</table>
</div>

</section>
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
<div class="px-container-padding py-4 bg-surface-container border-b border-outline-variant flex items-center justify-between">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-secondary" data-icon="public">public</span>
<h3 class="font-headline-md text-headline-md text-on-surface">Abroad Hire</h3>
</div>
<div class="flex items-center gap-3">
<div class="flex -space-x-2">

<img alt="Philippines Flag" class="w-6 h-6 rounded-full border-2 border-white ring-1 ring-gray-200 hover:scale-110 hover:z-10 transition-transform duration-200" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBkIzgpBkSzouSA16Y0XBYHsXhn8h6zlyCVBiuorGGEPM4UdA7gEOO0RNZJGayZK3C6WckFeH5_NkgRJ_uZOy74yxt9YC78djFgHBrzpVDfQbYUz13ktugkjSOJc_P-M9o7ab619ly2TLKEGco2c4YV87Fjengyf4_oN-VUTTm_WRmAtSf6whFALj6xkC5eTQn6T3ryDT1cl58xatk206fcmWbgsO4CPUjPQmUoegSgQHJLfhXi7aWZwPXlyZTb3SRed_QLjY-Xjic"/>

</div>
<span class="text-body-sm text-outline">1 Country</span>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant">
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Candidate Name</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Origin</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Agency</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Relocation Progress</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Current Stage</th>
<th class="px-6 py-3 text-label-caps font-label-caps text-outline uppercase tracking-wider">Arrival Date</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/30">
<tr class="hover:bg-surface-container-low/50 transition-colors group">
<td class="px-6 py-4">
<div class="flex flex-col">
<span class="font-bold text-on-surface">(ex) JAKE ALAMAN</span>
<span class="text-[11px] text-outline">Ref: AB-4921</span>
</div>
</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-outline text-sm" data-icon="flag">flag</span>
<span class="text-body-md text-on-surface-variant">Philippines</span>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface-variant">(ex)Global MedLink</td>
<td class="px-6 py-4">
<div class="w-32">
<div class="flex justify-between items-center mb-1">
<span class="text-[10px] font-bold text-primary uppercase">(ex)85%</span>
</div>
<div class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden">
<div class="bg-primary h-full transition-all duration-1000" style="width: 85%"></div>
</div>
</div>
</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
<span class="text-body-sm font-medium text-on-surface">(ex)Flight Scheduled</span>
</div>
</td>
<td class="px-6 py-4 text-body-md font-bold text-primary">(ex)Oct 28, 2025</td>
</tr>

</tbody>
</table>
</div>
</section>
<!-- Main Data Table -->

</div>
</div>
</main>
<!-- FAB for Quick Actions -->
<button class="fixed bottom-8 right-8 w-14 h-14 bg-primary text-on-primary rounded-full shadow-2xl flex items-center justify-center transition-all hover:scale-110 active:scale-90 group z-50">
<span class="material-symbols-outlined text-3xl">add</span>
<div class="absolute right-16 bg-on-surface text-white px-3 py-1 rounded text-xs whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
            Quick Referral
        </div>
</button>
<script>
        // Micro-interaction for table rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                row.classList.add('scale-[0.99]');
                setTimeout(() => row.classList.remove('scale-[0.99]'), 150);
            });
        });

        // Search bar focus effect
        const searchInput = document.querySelector('header input');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-primary', 'bg-white');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-primary', 'bg-white');
        });
    </script>
</body></html>
