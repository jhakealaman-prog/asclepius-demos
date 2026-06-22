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

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>MedLab Pro | X-Ray Diagnostic Viewport</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Manrope:wght@600;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #bdc9c5;
            border-radius: 10px;
        }
        .xray-container {
            background: radial-gradient(circle at center, #2c3130 0%, #171d1b 100%);
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-high": "#e4e9e7",
                        "on-secondary-fixed": "#001e30",
                        "on-primary": "#ffffff",
                        "on-surface": "#171d1b",
                        "error-container": "#ffdad6",
                        "on-error-container": "#93000a",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#3d4946",
                        "surface-container": "#eaefec",
                        "error": "#ba1a1a",
                        "surface-dim": "#d6dbd9",
                        "on-primary-container": "#f4fffb",
                        "surface-container-highest": "#dfe4e1",
                        "on-tertiary": "#ffffff",
                        "on-error": "#ffffff",
                        "surface-bright": "#f6faf8",
                        "primary-container": "#008376",
                        "background": "#f6faf8",
                        "on-primary-fixed": "#00201c",
                        "tertiary": "#585d60",
                        "on-secondary-container": "#345f80",
                        "primary-fixed": "#8ff4e3",
                        "tertiary-container": "#707579",
                        "on-secondary-fixed-variant": "#1c4a6a",
                        "secondary-fixed": "#cbe6ff",
                        "on-tertiary-fixed": "#171c1f",
                        "surface-variant": "#dfe4e1",
                        "on-tertiary-container": "#fbfcff",
                        "secondary-fixed-dim": "#a1cbf0",
                        "secondary-container": "#aed9ff",
                        "outline": "#6d7a77",
                        "surface-container-lowest": "#ffffff",
                        "surface": "#f6faf8",
                        "primary-fixed-dim": "#72d8c8",
                        "surface-tint": "#006b5f",
                        "primary": "#00685d",
                        "outline-variant": "#bdc9c5",
                        "tertiary-fixed": "#dfe3e7",
                        "on-primary-fixed-variant": "#005047",
                        "tertiary-fixed-dim": "#c3c7cb",
                        "on-tertiary-fixed-variant": "#43474b",
                        "surface-container-low": "#f0f5f2",
                        "secondary": "#376283",
                        "inverse-surface": "#2c3130",
                        "inverse-primary": "#72d8c8",
                        "inverse-on-surface": "#edf2ef",
                        "on-background": "#171d1b"
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
                        "gutter": "20px",
                        "stack-md": "16px",
                        "stack-sm": "8px",
                        "card-gap": "16px"
                    },
                    "fontFamily": {
                        "label-bold": ["Inter"],
                        "body-sm": ["Inter"],
                        "body-md": ["Inter"],
                        "label-caps": ["Inter"],
                        "headline-md": ["Manrope"],
                        "headline-lg": ["Manrope"],
                        "body-lg": ["Inter"]
                    },
                    "fontSize": {
                        "label-bold": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                        "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "600"}],
                        "headline-md": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                        "headline-lg": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
                    }
                },
            },
        }
    </script>
</head>
<body class="bg-background text-on-surface font-body-md overflow-hidden">
<!-- SideNavBar -->
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
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Biling.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-bold text-label-bold">Billing</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-surface-variant/70 hover:text-surface-bright mx-2 my-1 opacity-70 hover:bg-surface-variant/10 transition-colors" href="Dental.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-bold text-label-bold">Dental</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 bg-surface-variant/20 text-surface-bright rounded-lg mx-2 my-1 opacity-100 transition-colors" href="#">
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
<header class="fixed top-0 right-0 w-[calc(100%-260px)] h-16 bg-surface flex items-center justify-between px-container-padding border-b border-outline-variant z-40">
<div class="flex items-center flex-1">
<div class="relative w-full max-w-md">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="search">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:outline-none focus:border-primary transition-all text-body-md" placeholder="Search patient IDs, reports, or clinicians..." type="text"/>
</div>
</div>
<div class="flex items-center gap-4">
<div class="flex items-center gap-2 border-l border-outline-variant pl-4">
<button class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors relative">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border border-surface"></span>
</button>
</div>
</div>
</header>
<!-- Main Content Canvas -->
<main class="ml-sidebar-width pt-16 h-screen flex flex-col overflow-auto">
<!-- Patient Context Header -->
<section class="bg-surface-container-lowest px-container-padding py-4 flex items-center justify-between border-b border-outline-variant shadow-sm z-30 hover:shadow-md transition-shadow duration-300">
<div class="flex items-center gap-6">
<div class="flex flex-col">
<h2 class="text-headline-md font-headline-md text-on-surface">Elena Rodriguez</h2>
<p class="text-body-sm text-outline font-medium tracking-tight">ID: DX-9902 • DOB: Mar 14, 1982 (41Y)</p>
</div>
<div class="h-8 w-[1px] bg-outline-variant"></div>
<div class="flex gap-8">
<div>
<p class="text-label-caps text-outline">Gender</p>
<p class="text-body-md font-bold text-on-surface">Female</p>
</div>
<div>
<p class="text-label-caps text-outline">Blood Type</p>
<p class="text-body-md font-bold text-on-surface">O Positive</p>
</div>
<div>
<p class="text-label-caps text-outline">Allergies</p>
<p class="text-body-sm font-bold text-error bg-error-container/30 px-2 py-0.5 rounded">Penicillin</p>
</div>
</div>
</div>
<div class="flex gap-2">
<button class="flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg text-secondary font-bold text-label-bold hover:bg-secondary-container/20 transition-colors">
<span class="material-symbols-outlined text-[18px]" data-icon="history">history</span>
                    View History
                </button>
<button class="flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg text-secondary font-bold text-label-bold hover:bg-secondary-container/20 transition-colors">
<span class="material-symbols-outlined text-[18px]" data-icon="print">print</span>
</button>
</div>
</section>
<!-- Main Workspace Grid -->
<div class="flex-1 overflow-hidden grid grid-cols-12">
<!-- Left Side: Diagnostics Sidebar -->
<aside class="col-span-3 border-r border-outline-variant bg-surface-container-low flex flex-col overflow-y-auto custom-scrollbar hover:shadow-md transition-shadow duration-300">
<!-- Imaging Study Details -->
<div class="p-6 border-b border-outline-variant hover:shadow-sm transition-shadow duration-300">
<h3 class="text-label-caps text-outline mb-4">Imaging Study</h3>
<div class="space-y-4">
<div class="flex justify-between items-center">
<span class="text-body-sm text-on-surface-variant">Study Date</span>
<span class="text-body-sm font-bold text-on-surface">Oct 24, 2023</span>
</div>
<div class="flex justify-between items-center">
<span class="text-body-sm text-on-surface-variant">Modality</span>
<span class="text-body-sm font-bold text-on-surface">Digital Radiography</span>
</div>
<div class="flex justify-between items-center">
<span class="text-body-sm text-on-surface-variant">Procedure</span>
<span class="text-body-sm font-bold text-on-surface">Chest PA/Lateral</span>
</div>
<div class="flex justify-between items-center">
<span class="text-body-sm text-on-surface-variant">Accession #</span>
<span class="text-body-sm font-bold text-primary">#RX-44021-B</span>
</div>
</div>
</div>
<!-- Recent Studies Comparison -->
<div class="p-6 border-b border-outline-variant hover:shadow-sm transition-shadow duration-300">
<div class="flex justify-between items-center mb-4">
<h3 class="text-label-caps text-outline">Recent Studies</h3>
<span class="text-[10px] bg-primary-container text-on-primary-container px-1.5 py-0.5 rounded font-bold">SAME MODALITY</span>
</div>
<div class="space-y-3">
<div class="group p-2 bg-surface rounded-lg border border-outline-variant hover:border-primary cursor-pointer transition-all" data-study="chest" data-image="https://lh3.googleusercontent.com/aida-public/AB6AXuDt8i07NC30-7fAl7Brwv1Yg5voJM0ndtPt8Y-csmgRvVzSF3NPWEf83HiAmteo90YfR8GbOokfJn-3rT-8zXrn354VcfoIRCkmjD9uDzGZpwZNm314uOgsdTyhfcG0xsCSLgnDYwJ7t2fA0IJRfY-y8ILSgo0JJbWANS_lVrGWo70TMEsQhg3lQhbwfhR9xbp-hDZcuPgZhzrG-oesqcLta3WMCLxblb1tNCOOhOM79IE7IT98Sa7CBIzWFA1ep15k8741V-P-uHA">
<div class="flex gap-3">
<div class="w-16 h-16 bg-on-surface-variant rounded flex items-center justify-center overflow-hidden">
<img alt="Previous Scan" class="w-full h-full object-cover opacity-80" data-alt="A small clinical thumbnail of a previous chest X-ray radiograph, displaying medical precision with clear lung fields and thoracic cage details in high contrast black and white. The aesthetic is purely professional and medical, focused on diagnostic accuracy and longitudinal patient data comparison in a clinical laboratory software interface." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDt8i07NC30-7fAl7Brwv1Yg5voJM0ndtPt8Y-csmgRvVzSF3NPWEf83HiAmteo90YfR8GbOokfJn-3rT-8zXrn354VcfoIRCkmjD9uDzGZpwZNm314uOgsdTyhfcG0xsCSLgnDYwJ7t2fA0IJRfY-y8ILSgo0JJbWANS_lVrGWo70TMEsQhg3lQhbwfhR9xbp-hDZcuPgZhzrG-oesqcLta3WMCLxblb1tNCOOhOM79IE7IT98Sa7CBIzWFA1ep15k8741V-P-uHA"/>
</div>
<div class="flex-1 min-w-0">
<p class="text-label-bold truncate">Chest PA/Lateral</p>
<p class="text-[11px] text-outline">Aug 12, 2023</p>
<p class="text-[11px] text-primary font-bold mt-1">Normal Findings</p>
</div>
</div>
</div>
<div class="group p-2 bg-surface rounded-lg border border-outline-variant hover:border-primary cursor-pointer transition-all" data-study="spine" data-image="https://lh3.googleusercontent.com/aida-public/AB6AXuCHVVKw5eYBg6l4wrkJEy9-q4kiHnixN5DBiSideyS42NxmRJSEdEgCwDRssuewhLfCe8pqkUnXqsX2G35urrFQeEFS3Yw42AkYcr9aJ-cdKztdDNO6R-W8VUT2acm8vRoFcrSSb6P-clKWrTt81tnTCrlkNUyKBhlUrHq1yQ1b2VSYHL1QN6TD8DpNwKUkKloeUBFV7VgC-wvRWzOeZsUFJgaFO_3MJBhFCBw1turGd1VgklX6_CU3fIL6swimRVGWGXuxuJFmwOo">
<div class="flex gap-3">
<div class="w-16 h-16 bg-on-surface-variant rounded flex items-center justify-center overflow-hidden">
<img alt="Previous Scan" class="w-full h-full object-cover opacity-80" data-alt="A small monochrome clinical thumbnail of a lumbar spine X-ray study from a previous medical appointment. The image shows the spinal column in sharp detail with high medical contrast. The atmosphere is clinical and analytical, designed for a professional diagnostic dashboard used by radiologists and laboratory staff." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCHVVKw5eYBg6l4wrkJEy9-q4kiHnixN5DBiSideyS42NxmRJSEdEgCwDRssuewhLfCe8pqkUnXqsX2G35urrFQeEFS3Yw42AkYcr9aJ-cdKztdDNO6R-W8VUT2acm8vRoFcrSSb6P-clKWrTt81tnTCrlkNUyKBhlUrHq1yQ1b2VSYHL1QN6TD8DpNwKUkKloeUBFV7VgC-wvRWzOeZsUFJgaFO_3MJBhFCBw1turGd1VgklX6_CU3fIL6swimRVGWGXuxuJFmwOo"/>
</div>
<div class="flex-1 min-w-0">
<p class="text-label-bold truncate">Lumbar Spine 2V</p>
<p class="text-[11px] text-outline">May 03, 2023</p>
<p class="text-[11px] text-outline mt-1 italic">Comparison Available</p>
</div>
</div>
</div>
</div>
</div>
<!-- Diagnostic Report Form -->
<div class="p-6 flex-1 flex flex-col">
<h3 class="text-label-caps text-outline mb-4">Diagnostic Report</h3>
<div class="flex-1 space-y-4">
<div>
<label class="text-label-bold text-on-surface-variant block mb-1">Clinical Impressions</label>
<textarea class="w-full h-32 p-3 bg-surface border border-outline-variant rounded-lg text-body-sm focus:ring-1 focus:ring-primary focus:border-primary transition-all resize-none" id="clinicalImpressions" placeholder="Enter diagnostic impressions..."></textarea>
</div>
<div>
<label class="text-label-bold text-on-surface-variant block mb-1">Recommendations</label>
<input class="w-full p-3 bg-surface border border-outline-variant rounded-lg text-body-sm focus:ring-1 focus:ring-primary focus:border-primary transition-all" id="recommendations" placeholder="Follow-up advice..." type="text"/>
</div>
</div>
<button class="w-full bg-primary text-white py-3 rounded-lg font-bold text-label-bold mt-6 shadow-sm hover:shadow-md transition-all active:scale-[0.98]" id="finalizeReportBtn">
                        Finalize Report
                    </button>
</div>
</aside>
<!-- Center/Right: X-Ray Viewport -->
<section class="col-span-9 bg-inverse-surface flex flex-col relative hover:shadow-inner transition-shadow duration-300">
<!-- Viewport Toolbar -->
<div class="h-12 bg-on-surface/90 backdrop-blur-md flex items-center justify-between px-6 border-b border-white/10 text-white/80 z-20">
<div class="flex items-center gap-4">
<div class="flex items-center gap-1 bg-white/5 p-1 rounded">
<button class="p-1 hover:bg-white/10 rounded transition-colors" title="Zoom In" id="zoomInBtn">
<span class="material-symbols-outlined text-[20px]" data-icon="zoom_in">zoom_in</span>
</button>
<button class="p-1 hover:bg-white/10 rounded transition-colors" title="Zoom Out" id="zoomOutBtn">
<span class="material-symbols-outlined text-[20px]" data-icon="zoom_out">zoom_out</span>
</button>
</div>
<div class="flex items-center gap-1 bg-white/5 p-1 rounded">
<button class="p-1 hover:bg-white/10 rounded transition-colors" title="Rotate" id="rotateBtn">
<span class="material-symbols-outlined text-[20px]" data-icon="rotate_right">rotate_right</span>
</button>
<button class="p-1 hover:bg-white/10 rounded transition-colors" title="Flip" id="flipBtn">
<span class="material-symbols-outlined text-[20px]" data-icon="flip">flip</span>
</button>
</div>
<div class="flex items-center gap-1 bg-white/5 p-1 rounded">
<button class="p-1 hover:bg-white/10 rounded transition-colors" title="Measure" id="measureBtn">
<span class="material-symbols-outlined text-[20px]" data-icon="straighten">straighten</span>
</button>
<button class="p-1 hover:bg-white/10 rounded transition-colors" title="Annotate" id="annotateBtn">
<span class="material-symbols-outlined text-[20px]" data-icon="edit">edit</span>
</button>
</div>
</div>
<div class="flex items-center gap-8">
<div class="flex items-center gap-3">
<span class="text-[10px] font-bold uppercase tracking-widest opacity-60">Contrast</span>
<input class="w-24 h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-primary-fixed" id="contrastSlider" type="range" min="50" max="200" value="125"/>
</div>
<div class="flex items-center gap-3">
<span class="text-[10px] font-bold uppercase tracking-widest opacity-60">Brightness</span>
<input class="w-24 h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-primary-fixed" id="brightnessSlider" type="range" min="50" max="200" value="110"/>
</div>
<button class="flex items-center gap-1 text-[11px] font-bold bg-white/10 hover:bg-white/20 px-3 py-1 rounded transition-all" id="resetBtn">
<span class="material-symbols-outlined text-[16px]" data-icon="reset_settings">reset_settings</span>
                            RESET
                        </button>
</div>
</div>
<!-- X-Ray Visual Area -->
<div class="flex-1 relative flex items-center justify-center p-8 xray-container overflow-hidden group hover:shadow-2xl transition-shadow duration-300">
<!-- Diagnostic Overlays -->
<div class="absolute top-8 left-8 text-white/40 text-[10px] font-mono pointer-events-none space-y-1">
<p>PATIENT: RODRIGUEZ, ELENA</p>
<p>ID: DX-9902</p>
<p>PROC: CHEST PA</p>
<p>DATE: 2023-10-24 09:14:22</p>
<p>INST: MEDLAB PRO CLINIC 04</p>
</div>
<div class="absolute bottom-8 right-8 text-white/40 text-[10px] font-mono text-right pointer-events-none space-y-1">
<p>WL: 2048 / WW: 4096</p>
<p>ZOOM: 1.0x</p>
<p>FILTER: SHARPEN-HD</p>
<p>RENDER: DICOM V3.0</p>
</div>
<!-- Main Radiograph -->
<div class="relative max-h-full aspect-[4/5] shadow-2xl shadow-black/80 transition-transform duration-300 cursor-move" id="xrayImageContainer">
<img alt="Chest X-Ray Diagnostic" class="w-full h-full object-contain brightness-110 contrast-125 rounded-sm transition-all duration-300" id="xrayImage" data-alt="A large, professional chest X-ray radiograph presented in high definition on a medical viewing station. The image shows detailed thoracic anatomy, including the lungs, heart silhouette, and rib cage with clinical clarity. The background is a dark, cinematic medical diagnostic environment with subtle blue and green digital overlays indicating measurements and metadata. The aesthetic is clean, sharp, and authoritative, emphasizing diagnostic precision in a modern laboratory setting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBBFYkbl04Qj-LRCt4D2PmNXgGv4DqyZmlsBTE3ht62EKR5lfipnxAOMzS55yQbLuGQJO33hue9uCoP3l3BRHxnPrNcmbO94sNidj3a9qUbu4-vPUvZlBc4bn0TC5WBSYHJyRRUvEe0rVm1Hp9MDzih7Bu4TbecIU1tiwjpu7quAvNRsz6bmMRw852dMyY8tZyGAGhKC1ud0KAboUZozzbdRreSkZhEIBBAbG37gPHw-zxHL4LjB0cE-kgfaUvIVgxS-JWyi2V3AlU" data-study="chest"/>
<!-- ROI Annotations (Decorative) -->
<div class="absolute top-[40%] left-[30%] w-16 h-16 border-2 border-primary-fixed/40 rounded-full animate-pulse flex items-center justify-center">
<div class="w-1 h-1 bg-primary-fixed rounded-full"></div>
</div>
<div class="absolute top-[20%] right-[25%] px-2 py-1 bg-primary/20 backdrop-blur-sm border border-primary/40 text-primary-fixed text-[10px] rounded font-bold">
                            T-SPINE CLEAR
                        </div>
</div>
<!-- Magnifier Simulation (UX Flare) -->
<div class="absolute w-40 h-40 border-2 border-white/20 rounded-full bg-black/40 backdrop-blur-xl hidden group-hover:block pointer-events-none transform -translate-x-1/2 -translate-y-1/2 z-10" id="magnifier">
<div class="absolute inset-0 flex items-center justify-center">
<span class="text-white/20 text-[10px]">LENS ACTIVE</span>
</div>
</div>
</div>
<!-- Footer Status Bar -->
<div class="h-10 bg-on-surface flex items-center justify-between px-6 text-[10px] font-bold text-outline uppercase tracking-widest border-t border-white/5">
<div class="flex items-center gap-6">
<span class="flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            PACS Connected
                        </span>
<span class="opacity-40">Latency: 14ms</span>
</div>
<div class="flex gap-4">
<span>FPS: 60.0</span>
<span>RES: 3840 x 2160</span>
</div>
</div>
</section>
</div>
</main>
<script>
        // X-Ray Viewport State
        let zoomLevel = 1.0;
        let rotation = 0;
        let isFlipped = false;
        let contrast = 125;
        let brightness = 110;
        let isMeasureMode = false;
        let isAnnotateMode = false;

        // DOM Elements
        const xrayImage = document.getElementById('xrayImage');
        const xrayImageContainer = document.getElementById('xrayImageContainer');
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const rotateBtn = document.getElementById('rotateBtn');
        const flipBtn = document.getElementById('flipBtn');
        const measureBtn = document.getElementById('measureBtn');
        const annotateBtn = document.getElementById('annotateBtn');
        const contrastSlider = document.getElementById('contrastSlider');
        const brightnessSlider = document.getElementById('brightnessSlider');
        const resetBtn = document.getElementById('resetBtn');
        const finalizeReportBtn = document.getElementById('finalizeReportBtn');
        const clinicalImpressions = document.getElementById('clinicalImpressions');
        const recommendations = document.getElementById('recommendations');

        // Update X-Ray Image Transform
        function updateImageTransform() {
            const scaleX = isFlipped ? -1 : 1;
            xrayImage.style.transform = `scale(${zoomLevel * scaleX}) rotate(${rotation}deg)`;
            xrayImage.style.filter = `brightness(${brightness}%) contrast(${contrast}%)`;
            
            // Update zoom display in footer
            const zoomDisplay = document.querySelector('.absolute.bottom-8.right-8 p:nth-child(2)');
            if (zoomDisplay) {
                zoomDisplay.textContent = `ZOOM: ${zoomLevel.toFixed(1)}x`;
            }
        }

        // Zoom In
        zoomInBtn.addEventListener('click', () => {
            if (zoomLevel < 3.0) {
                zoomLevel += 0.1;
                updateImageTransform();
            }
        });

        // Zoom Out
        zoomOutBtn.addEventListener('click', () => {
            if (zoomLevel > 0.5) {
                zoomLevel -= 0.1;
                updateImageTransform();
            }
        });

        // Rotate
        rotateBtn.addEventListener('click', () => {
            rotation = (rotation + 90) % 360;
            updateImageTransform();
        });

        // Flip
        flipBtn.addEventListener('click', () => {
            isFlipped = !isFlipped;
            updateImageTransform();
        });

        // Measure Mode
        measureBtn.addEventListener('click', () => {
            isMeasureMode = !isMeasureMode;
            measureBtn.classList.toggle('bg-primary/20', isMeasureMode);
            if (isMeasureMode) {
                xrayImageContainer.style.cursor = 'crosshair';
                alert('Measure mode activated. Click on the image to set measurement points.');
            } else {
                xrayImageContainer.style.cursor = 'move';
            }
        });

        // Annotate Mode
        annotateBtn.addEventListener('click', () => {
            isAnnotateMode = !isAnnotateMode;
            annotateBtn.classList.toggle('bg-primary/20', isAnnotateMode);
            if (isAnnotateMode) {
                xrayImageContainer.style.cursor = 'text';
                alert('Annotation mode activated. Click on the image to add annotations.');
            } else {
                xrayImageContainer.style.cursor = 'move';
            }
        });

        // Contrast Slider
        contrastSlider.addEventListener('input', (e) => {
            contrast = e.target.value;
            updateImageTransform();
        });

        // Brightness Slider
        brightnessSlider.addEventListener('input', (e) => {
            brightness = e.target.value;
            updateImageTransform();
        });

        // Reset Button
        resetBtn.addEventListener('click', () => {
            zoomLevel = 1.0;
            rotation = 0;
            isFlipped = false;
            contrast = 125;
            brightness = 110;
            isMeasureMode = false;
            isAnnotateMode = false;
            
            contrastSlider.value = 125;
            brightnessSlider.value = 110;
            measureBtn.classList.remove('bg-primary/20');
            annotateBtn.classList.remove('bg-primary/20');
            xrayImageContainer.style.cursor = 'move';
            
            updateImageTransform();
        });

        // Finalize Report
        finalizeReportBtn.addEventListener('click', () => {
            const impressions = clinicalImpressions.value;
            const recs = recommendations.value;
            
            if (!impressions.trim()) {
                alert('Please enter clinical impressions before finalizing the report.');
                return;
            }
            
            // Simulate report submission
            finalizeReportBtn.textContent = 'Saving...';
            finalizeReportBtn.disabled = true;
            
            setTimeout(() => {
                finalizeReportBtn.textContent = 'Report Saved!';
                finalizeReportBtn.classList.remove('bg-primary');
                finalizeReportBtn.classList.add('bg-emerald-600');
                
                setTimeout(() => {
                    finalizeReportBtn.textContent = 'Finalize Report';
                    finalizeReportBtn.classList.remove('bg-emerald-600');
                    finalizeReportBtn.classList.add('bg-primary');
                    finalizeReportBtn.disabled = false;
                    
                    // Clear form
                    clinicalImpressions.value = '';
                    recommendations.value = '';
                    
                    alert('Diagnostic report has been finalized and saved successfully!');
                }, 1500);
            }, 1000);
        });

        // Recent Studies Click Handler
        document.querySelectorAll('.group.p-2').forEach(study => {
            study.addEventListener('click', () => {
                const studyTitle = study.querySelector('.text-label-bold').textContent;
                const imageUrl = study.getAttribute('data-image');
                const studyType = study.getAttribute('data-study');
                
                if (imageUrl) {
                    // Reset zoom and rotation when changing images
                    zoomLevel = 1.0;
                    rotation = 0;
                    isFlipped = false;
                    contrastSlider.value = 125;
                    brightnessSlider.value = 110;
                    contrast = 125;
                    brightness = 110;
                    
                    // Change the main image with a fade effect
                    xrayImage.style.opacity = '0';
                    setTimeout(() => {
                        xrayImage.src = imageUrl;
                        xrayImage.setAttribute('data-study', studyType);
                        xrayImage.style.opacity = '1';
                        updateImageTransform();
                    }, 200);
                    
                    // Update study details in the sidebar
                    const studyDate = study.querySelector('.text-[11px].text-outline').textContent;
                    const procedureElement = document.querySelector('.flex.justify-between.items-center:nth-child(4) .text-body-sm.font-bold');
                    const dateElement = document.querySelector('.flex.justify-between.items-center:nth-child(1) .text-body-sm.font-bold');
                    
                    if (procedureElement) {
                        procedureElement.textContent = studyTitle;
                    }
                    if (dateElement) {
                        dateElement.textContent = studyDate;
                    }
                    
                    // Update viewer title
                    const viewerTitle = document.getElementById('viewerTitle');
                    if (viewerTitle) {
                        viewerTitle.textContent = studyTitle;
                    }
                }
            });
        });

        // Simple JS for Magnifier movement
        const xrayContainer = document.querySelector('.xray-container');
        const magnifier = document.getElementById('magnifier');

        xrayContainer.addEventListener('mousemove', (e) => {
            const rect = xrayContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            magnifier.style.left = `${x}px`;
            magnifier.style.top = `${y}px`;
        });

        // Toggle active state for nav links logic is handled by pre-rendering but let's add micro-interaction
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                // In a real app we'd navigate, here we just show an active state toggle
                document.querySelectorAll('nav a').forEach(l => {
                    l.classList.remove('text-primary-fixed', 'bg-primary-container/10', 'border-r-4', 'border-primary-fixed', 'font-bold');
                    l.classList.add('text-surface-variant/70', 'font-medium');
                });
                this.classList.remove('text-surface-variant/70', 'font-medium');
                this.classList.add('text-primary-fixed', 'bg-primary-container/10', 'border-r-4', 'border-primary-fixed', 'font-bold');
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === '+' || e.key === '=') {
                zoomInBtn.click();
            } else if (e.key === '-') {
                zoomOutBtn.click();
            } else if (e.key === 'r' || e.key === 'R') {
                rotateBtn.click();
            } else if (e.key === 'f' || e.key === 'F') {
                flipBtn.click();
            } else if (e.key === 'Escape') {
                resetBtn.click();
            }
        });
    </script>
</body></html>
