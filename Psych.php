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
                    "on-primary-container": "#f4fffb",
                    "tertiary-container": "#707579",
                    "surface-container-highest": "#dfe4e1",
                    "on-background": "#171d1b",
                    "inverse-on-surface": "#edf2ef",
                    "secondary-fixed": "#cbe6ff",
                    "primary": "#00685d",
                    "surface-dim": "#d6dbd9",
                    "surface-container-low": "#f0f5f2",
                    "on-tertiary": "#ffffff",
                    "inverse-primary": "#72d8c8",
                    "surface-container-high": "#e4e9e7",
                    "on-secondary": "#ffffff",
                    "error-container": "#ffdad6",
                    "outline-variant": "#bdc9c5",
                    "outline": "#6d7a77",
                    "on-error-container": "#93000a",
                    "tertiary-fixed": "#dfe3e7",
                    "surface-bright": "#f6faf8",
                    "tertiary": "#585d60",
                    "on-secondary-fixed": "#001e30",
                    "inverse-surface": "#2c3130",
                    "on-tertiary-fixed-variant": "#43474b",
                    "on-surface-variant": "#3d4946",
                    "surface": "#f6faf8",
                    "surface-tint": "#006b5f",
                    "secondary-fixed-dim": "#a1cbf0",
                    "on-tertiary-container": "#fbfcff",
                    "on-tertiary-fixed": "#171c1f",
                    "surface-variant": "#dfe4e1",
                    "background": "#f6faf8",
                    "primary-fixed-dim": "#72d8c8",
                    "on-error": "#ffffff",
                    "on-primary-fixed": "#00201c",
                    "on-primary-fixed-variant": "#005047",
                    "surface-container-lowest": "#ffffff",
                    "on-secondary-container": "#345f80",
                    "tertiary-fixed-dim": "#c3c7cb",
                    "primary-fixed": "#8ff4e3",
                    "primary-container": "#008376",
                    "secondary": "#376283",
                    "on-surface": "#171d1b",
                    "on-primary": "#ffffff",
                    "secondary-container": "#aed9ff",
                    "on-secondary-fixed-variant": "#1c4a6a",
                    "surface-container": "#eaefec",
                    "error": "#ba1a1a"
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
                    "card-gap": "16px",
                    "stack-md": "16px",
                    "gutter": "20px",
                    "stack-sm": "8px"
            },
            "fontFamily": {
                    "label-bold": ["Inter"],
                    "headline-lg": ["Manrope"],
                    "label-caps": ["Inter"],
                    "body-sm": ["Inter"],
                    "headline-md": ["Manrope"],
                    "body-md": ["Inter"],
                    "body-lg": ["Inter"]
            },
            "fontSize": {
                    "label-bold": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                    "headline-lg": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                    "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "600"}],
                    "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                    "headline-md": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                    "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                    "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .scale-98 { transform: scale(0.98); }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(8px); }
        .sidebar-active { background-color: #005047; color: #8ff4e3; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md">
<!-- SideNavBar (Execution from JSON) -->
<aside class="text-primary-fixed dark:text-primary-fixed-dim docked h-screen w-sidebar fixed left-0 top-0 flex flex-col h-full py-6 z-50" style="background-color: #00685D;">
<div class="px-6 mb-8">
<h1 class="text-headline-md font-headline-md font-bold text-surface-container-lowest">ASCLEPIUS Medical &<br> Diagnostic Group Inc.</h1>
<p class="text-label-bold text-surface-variant/60 font-label-bold">Laboratory Information System</p>
</div>
<nav class="flex-1 px-4 space-y-1 overflow-y-auto no-scrollbar">
<!-- Dashboard -->
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Dashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-bold text-label-bold">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Patient.php">
<span class="material-symbols-outlined">group</span>
<span class="font-label-bold text-label-bold">Patients</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Doctor.php">
<span class="material-symbols-outlined">group</span>
<span class="font-label-bold text-label-bold">Doctors</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Appointment.php">
<span class="material-symbols-outlined">receipt_long</span>
<span class="font-label-bold text-label-bold">Appointment</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Medical Records.php">
<span class="material-symbols-outlined">biotech</span>
<span class="font-label-bold text-label-bold">Medical Records</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Laboratory Result.php">
<span class="material-symbols-outlined">science</span>
<span class="font-label-bold text-label-bold">Laboratory Results</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Agency Referral.php">
<span class="material-symbols-outlined">description</span>
<span class="font-label-bold text-label-bold">Agency Referral</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Prescription.php">
<span class="material-symbols-outlined">settings</span>
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
<span class="font-label-bold text-label-bold">X-ray</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 bg-surface-variant/20 text-surface-bright rounded-lg mx-2 my-1 opacity-100 transition-colors" href="#">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-bold text-label-bold">Psych</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-error/80 hover:text-error hover:bg-error/10 mx-2 my-1 opacity-70 transition-colors" href="index.php" onclick="localStorage.clear();">
<span class="material-symbols-outlined">logout</span>
<span class="font-label-bold text-label-bold">Logout</span>
</a>
</nav>
</aside>
<!-- Main Workspace -->
<main class="ml-sidebar h-screen flex flex-col overflow-y-auto overflow-x-hidden no-scrollbar" style="margin-left: 260px; width: calc(100% - 260px);">
<!-- TopNavBar (Execution from JSON) -->
<header class="bg-surface sticky top-0 flex justify-between items-center h-16 px-gutter w-full border-b border-outline-variant/30 z-40">
<div class="flex flex-col">
<h2 class="font-headline-lg text-headline-lg text-primary">Psych</h2>
<p class="text-body-sm text-on-surface-variant">Psychiatry clinical suite</p>
</div>
<div class="flex items-center gap-4">
<div class="relative w-80">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary text-body-md" placeholder="Search patient, MRN, order" type="text"/>
</div>
<button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-all">
<span class="material-symbols-outlined">notifications</span>
</button>
</div>
</header>
<!-- Dashboard Workspace -->
<er border-outline-variant flex justify-between items-center opacity-70">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant font-bold">JM</div>
<div>
<p class="text-body-md font-bold text-on-surface leading-none">James Miller</p>
<p class="text-label-caps text-on-surface-variant mt-1">1:00 - 2:00 PM</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="more_vert">more_vert</span>
</div>
<div class="p-4 rounded-lg border border-outline-variant flex justify-between items-center opacity-70">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant font-bold">SK</div>
<div>
<p class="text-body-md font-bold text-on-surface leading-none">Sarah Ko</p>
<p class="text-label-caps text-on-surface-variant mt-1">2:15 - 3:15 PM</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="more_vert">more_vert</span>
</div>
<div class="p-4 rounded-lg border border-outline-variant flex justify-between items-center opacity-70">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant font-bold">TB</div>
<div>
<p class="text-body-md font-bold text-on-surface leading-none">Tom Baker</p>
<p class="text-label-caps text-on-surface-variant mt-1">4:00 - 5:00 PM</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="more_vert">more_vert</span>
</div>
</div>
</div>
</div>
<!-- Lower Grid Section -->
<div class="grid grid-cols-12 gap-card-gap">
<!-- Clinical Metrics / Quick Stats -->
<div class="col-span-12 lg:col-span-7 space-y-card-gap">
<div class="bg-white border border-outline-variant rounded-xl p-6">
<div class="flex justify-between items-center mb-6">
<h4 class="font-headline-md text-headline-md text-on-surface">Weekly Case Progress</h4>
<div class="flex gap-2">
<span class="w-3 h-3 rounded-full bg-primary"></span>
<span class="text-label-caps text-on-surface-variant">Clinical Goals</span>
</div>
</div>
<div class="space-y-6">
<div class="space-y-2">
<div class="flex justify-between text-body-sm">
<span class="font-bold">Anxiety Reductions (Group B)</span>
<span class="text-on-surface-variant">72% Goal Met</span>
</div>
<div class="w-full bg-surface-container-high h-2 rounded-full">
<div class="bg-primary h-2 rounded-full w-[72%]"></div>
</div>
</div>
<div class="space-y-2">
<div class="flex justify-between text-body-sm">
<span class="font-bold">CBT Module Completion</span>
<span class="text-on-surface-variant">45% Goal Met</span>
</div>
<div class="w-full bg-surface-container-high h-2 rounded-full">
<div class="bg-secondary h-2 rounded-full w-[45%]"></div>
</div>
</div>
<div class="space-y-2">
<div class="flex justify-between text-body-sm">
<span class="font-bold">Patient Retention Rate</span>
<span class="text-on-surface-variant">94% Goal Met</span>
</div>
<div class="w-full bg-surface-container-high h-2 rounded-full">
<div class="bg-primary-container h-2 rounded-full w-[94%]"></div>
</div>
</div>
</div>
</div>
<div class="grid grid-cols-2 gap-card-gap">
<div class="bg-primary-container text-on-primary-container p-6 rounded-xl flex flex-col justify-between h-32">
<span class="text-label-caps uppercase tracking-wider opacity-80">Pending Notes</span>
<div class="flex items-end justify-between">
<h3 class="text-[32px] font-bold">0</h3>
<span class="material-symbols-outlined text-[32px]" data-icon="edit_calendar">edit_calendar</span>
</div>
</div>
<div class="bg-tertiary-container text-on-tertiary-container p-6 rounded-xl flex flex-col justify-between h-32">
<span class="text-label-caps uppercase tracking-wider opacity-80">Refill Requests</span>
<div class="flex items-end justify-between">
<h3 class="text-[32px] font-bold">04</h3>
<span class="material-symbols-outlined text-[32px]" data-icon="prescriptions">prescriptions</span>
</div>
</div>
</div>
</div>
<!-- Recent Activity / Timeline -->
<div class="col-span-12 lg:col-span-5 bg-white border border-outline-variant rounded-xl p-6">
<h4 class="font-headline-md text-headline-md text-on-surface mb-6">Recent Clinical Activity</h4>
<button class="w-full mt-8 py-3 text-primary font-bold text-label-bold border border-primary rounded-lg hover:bg-primary/5 transition-colors">
                        View All Activity
                    </button>
</div>
</div>
</div>
</main>
<script>
        // Micro-interactions
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('mousedown', () => btn.classList.add('scale-95'));
            btn.addEventListener('mouseup', () => btn.classList.remove('scale-95'));
            btn.addEventListener('mouseleave', () => btn.classList.remove('scale-95'));
        });

        // Simple animation for progress bars on load
        window.addEventListener('load', () => {
            const bars = document.querySelectorAll('.bg-primary, .bg-secondary, .bg-primary-container');
            bars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.transition = 'width 1s cubic-bezier(0.4, 0, 0.2, 1)';
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body></html>
