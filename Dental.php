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
<title>Dental Imaging &amp; Diagnostics | MedLab Pro</title>
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
                        "surface-container-lowest": "#ffffff",
                        "primary": "#00685d",
                        "outline": "#6d7a77",
                        "tertiary": "#585d60",
                        "surface-bright": "#f6faf8",
                        "secondary-fixed-dim": "#a1cbf0",
                        "primary-container": "#008376",
                        "surface-container": "#eaefec",
                        "tertiary-fixed-dim": "#c3c7cb",
                        "surface-variant": "#dfe4e1",
                        "on-background": "#171d1b",
                        "surface-container-high": "#e4e9e7",
                        "secondary": "#376283",
                        "on-tertiary-fixed-variant": "#43474b",
                        "on-error": "#ffffff",
                        "error-container": "#ffdad6",
                        "outline-variant": "#bdc9c5",
                        "surface-container-highest": "#dfe4e1",
                        "surface": "#f6faf8",
                        "tertiary-container": "#707579",
                        "on-tertiary-fixed": "#171c1f",
                        "error": "#ba1a1a",
                        "on-secondary-container": "#345f80",
                        "on-primary-fixed-variant": "#005047",
                        "inverse-primary": "#72d8c8",
                        "surface-tint": "#006b5f",
                        "on-tertiary": "#ffffff",
                        "on-secondary-fixed-variant": "#1c4a6a",
                        "secondary-container": "#aed9ff",
                        "on-primary": "#ffffff",
                        "on-surface": "#171d1b",
                        "secondary-fixed": "#cbe6ff",
                        "inverse-surface": "#2c3130",
                        "inverse-on-surface": "#edf2ef",
                        "primary-fixed": "#8ff4e3",
                        "on-surface-variant": "#3d4946",
                        "background": "#f6faf8",
                        "tertiary-fixed": "#dfe3e7",
                        "on-secondary": "#ffffff",
                        "on-primary-fixed": "#00201c",
                        "surface-container-low": "#f0f5f2",
                        "on-primary-container": "#f4fffb",
                        "on-tertiary-container": "#fbfcff",
                        "primary-fixed-dim": "#72d8c8",
                        "on-secondary-fixed": "#001e30"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "stack-sm": "8px",
                        "sidebar-width": "260px",
                        "gutter": "20px",
                        "container-padding": "24px",
                        "stack-md": "16px",
                        "card-gap": "16px"
                    },
                    "fontFamily": {
                        "headline-md": ["Manrope"],
                        "label-caps": ["Inter"],
                        "headline-lg": ["Manrope"],
                        "body-md": ["Inter"],
                        "body-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-bold": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                        "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "600"}],
                        "headline-lg": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "label-bold": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
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
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-surface overflow-hidden h-screen flex">
<!-- Sidebar Navigation -->
<aside class="fixed left-0 top-0 h-screen w-sidebar-width flex flex-col py-container-padding z-50 shadow-md" style="background-color: #00685D;">
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
<a class="flex items-center gap-3 px-3 py-2 bg-surface-variant/20 text-surface-bright rounded-lg mx-2 my-1 opacity-100 transition-colors" href="#">
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
<!-- Main Content Area -->
<main class="ml-sidebar-width flex-1 flex flex-col h-screen overflow-hidden">
<!-- Header -->
<header class="h-16 flex justify-between items-center px-gutter bg-surface-container-lowest border-b border-outline-variant z-40">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container overflow-hidden">
<img alt="Patient Avatar" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCACGJheaptPmqZPBvrtkIADS3CyM-vCpI3cy6bEU7bMd1RhasIMZbPE6iO3bgJ8LeMUVArVwliVwC6bof7XG1axvY3JPU1xpX3VUPZdspPMEbsTpwdyF3aOctoQZcJMnct56O5t05Q-tyP90YFtjDejdFlBbZizE2K-lsKdJBsXUngqWnYpJVWj33sisVoRnnohXDTNDMHRiQdVnglK4HVheLAtFOzJrY1gp9-Sxxq5hnIohRbyhcUsx6NBsOl2PtjRi5tbwaVHoE"/>
</div>
<div>
<h2 class="font-headline-md text-headline-md text-primary">Elena Rodriguez</h2>
<p class="font-body-sm text-body-sm text-on-surface-variant">ID: #DX-9902 • 34 Years • Female</p>
</div>
</div>
<div class="flex items-center gap-stack-md">
<div class="relative">
<span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline">search</span>
<input class="bg-surface-container-low border-none rounded-full pl-10 pr-4 py-2 text-body-sm w-64 focus:ring-2 focus:ring-primary" placeholder="Search images or reports..." type="text"/>
</div>
<button class="bg-primary text-on-primary font-label-bold py-2 px-6 rounded-lg flex items-center gap-2 hover:opacity-90 transition-opacity" onclick="openRequestModal()">
<span class="material-symbols-outlined">add_to_photos</span>
                    Request New Imaging
                </button>
</div>
</header>
<!-- Workspace Grid -->
<div class="flex-1 flex overflow-hidden">
<!-- Left: Imaging Gallery & Viewer -->
<section class="flex-1 p-gutter overflow-y-auto custom-scrollbar bg-background hover:shadow-inner transition-shadow duration-300">
<!-- Tabs/Filters -->
<div class="flex items-center justify-between mb-6">
<div class="flex gap-2 p-1 bg-surface-container-high rounded-lg">
<button class="px-4 py-1.5 bg-surface-container-lowest shadow-sm rounded-md font-label-bold text-primary">All Images</button>
<button class="px-4 py-1.5 hover:bg-surface-container-low rounded-md font-label-bold text-on-surface-variant transition-colors">Periapical</button>
<button class="px-4 py-1.5 hover:bg-surface-container-low rounded-md font-label-bold text-on-surface-variant transition-colors">Bitewing</button>
<button class="px-4 py-1.5 hover:bg-surface-container-low rounded-md font-label-bold text-on-surface-variant transition-colors">Panoramic</button>
</div>
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="font-body-sm">Sort by:</span>
<select class="bg-transparent border-none text-body-sm font-label-bold focus:ring-0 cursor-pointer">
<option>Latest First</option>
<option>Oldest First</option>
</select>
</div>
</div>
<!-- Bento-style Image Grid -->
<div class="grid grid-cols-12 gap-card-gap">
<!-- Featured Panoramic (Large) -->
<div class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl p-4 group cursor-pointer hover:shadow-lg transition-all" onclick="openViewer('Panoramic X-Ray', '2023-10-12')">
<div class="flex justify-between items-center mb-3">
<span class="font-label-bold text-primary flex items-center gap-1"><span class="material-symbols-outlined text-sm">view_carousel</span> Panoramic</span>
<span class="font-body-sm text-outline">Oct 12, 2023</span>
</div>
<div class="aspect-[21/9] bg-inverse-surface rounded-lg overflow-hidden relative">
<img class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" data-alt="A highly detailed panoramic dental X-ray showing the full upper and lower jaw structure, teeth alignment, and bone density. The image is rendered in a clinical, high-contrast monochrome style typical of medical imaging software. Soft cyan highlights suggest digital analysis of specific tooth roots and nerve pathways. The mood is professional and diagnostic, set against a dark clinical workspace." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDOOdH4Mm4JOv263fYrwdHFMgQ6Jlk8upWG11Jpocd8gxD504thBi59T43uRncTKFB9u6wMXb9lWerqIKoyscotOliPGRbFXrT1-OUAdRgIsAMhd0r09GUWdneXi8nMBcBruzhJK_p4tby5DFjeWdy_TWSUXgPmRThfc1Vx11--ee-rxdjmY0cSfFUouI5rGpYwVdKrWSQxC49MvdseLDW3VCcl0ZlwGmJU9ktETqEFnkjlnL1Sa05vQTtKQX0OWrXIYW0iW_iX8J0"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
<span class="text-white font-label-bold flex items-center gap-2"><span class="material-symbols-outlined">zoom_in</span> Click to Analyze</span>
</div>
</div>
</div>
<!-- Bitewing 1 -->
<div class="col-span-6 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all" onclick="openViewer('Left Bitewing', '2023-11-05')">
<div class="flex justify-between items-center mb-3">
<span class="font-label-bold text-primary flex items-center gap-1"><span class="material-symbols-outlined text-sm">grid_view</span> Bitewing</span>
<span class="font-body-sm text-outline">Nov 05, 2023</span>
</div>
<div class="aspect-square bg-inverse-surface rounded-lg overflow-hidden">
<img class="w-full h-full object-cover opacity-70" data-alt="Close-up bitewing dental X-ray focused on the posterior teeth of the upper and lower jaw. The monochrome image shows clear vertical detail of crowns and interproximal spaces. The lighting is crisp, highlighting the high density of enamel and dental fillings. The background is a clean, dark gray clinical interface with minimal UI overlays." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-EWudtOdFKlXbmzGNUun2iya6kK7hcwl-Jz2-7LW_Zd8ogl9BXj9GRtjC0mJUSg4CFDR4NsGKNCFDUbneuzAjErbvWjUdscdjmRQrhOo5_VLDpcfec4VP8UPdTJbOQDRRoccwp_ceg8fqrzDfJ3LLWqXILEs6QZc_QBEnzfI0GEZ0liABUK7Eg90CJUaDaFkzcjuj189L_Dzm3HF7BO0MqpU4zq5YMqi2NfyTrubyQbT-0fCQz0oOHw-UeXMfZgCy9EMD94fbeUI"/>
</div>
</div>
<!-- Periapical 1 -->
<div class="col-span-6 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all">
<div class="flex justify-between items-center mb-3">
<span class="font-label-bold text-primary flex items-center gap-1"><span class="material-symbols-outlined text-sm">target</span> Periapical</span>
<span class="font-body-sm text-outline">Nov 05, 2023</span>
</div>
<div class="aspect-square bg-inverse-surface rounded-lg overflow-hidden">
<img class="w-full h-full object-cover opacity-70" data-alt="A focused periapical dental X-ray showing the entire length of a specific molar, from the crown to the root tip in the jawbone. High-resolution medical imaging aesthetic with subtle digital enhancements identifying the root canal anatomy. The tone is clinical and precise, utilizing shades of silver and deep obsidian." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBffoZY37fWrh11Ng4ilbfWCh4C_6EX64_XaJ4vZS1R17uemNWlPP5Oh5BPJJyQQ67ts1ULNDQMQ7cZp9vsdHDveurF968LZ83FOAbLDXLxKoZbLW6_obmdgyAOdaTvfto4XVr5jtQ5NlxUt8P10rEG2eho6AbiLOgeyQ3rAAdscRkgFycM7YWZrsHLGtvgwFvm8XjL5R9jDID6tw8ihu_aLqEJc2umDPbnRZEJgzdif3yo8Tfua7ExhfNy1vZR7A20fzfSBJ33N3g"/>
</div>
</div>
<!-- Periapical 2 -->
<div class="col-span-6 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all">
<div class="flex justify-between items-center mb-3">
<span class="font-label-bold text-primary flex items-center gap-1"><span class="material-symbols-outlined text-sm">target</span> Periapical</span>
<span class="font-body-sm text-outline">Nov 05, 2023</span>
</div>
<div class="aspect-square bg-inverse-surface rounded-lg overflow-hidden">
<img class="w-full h-full object-cover opacity-70" data-alt="Medical diagnostic X-ray of incisor teeth showing bone structure and healthy root systems. Crisp monochrome levels provide high clarity for dental professionals. The image is part of a clean, modern medical software suite with subtle grid lines in the background for alignment reference." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_XvxOyAvERf0HVcuSacQPwoW6C8DyJMu28jyTy4q5dxULyD5gzPrqdXyDAz3uYf8AoyEFPuqLp2MxdNoTJf4-Psu88_Az5L9i5-IL6ArOpHK_ByY9Ux41GVDeuFO6YGRTdSbSyKP0RXz671zzhC8d9qnYjHwJm0XKIqH3FX4EAeUsQgPb3NLnpZssDF37dR4iurv3IhSTpBOYgJ_rBnWevGVyYIO5sOwYeg_lqiTkRR6_hXG8VE-hMmsgj3pOvSgQmj163rmd2PI"/>
</div>
</div>
<!-- Bitewing 2 -->
<div class="col-span-6 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all">
<div class="flex justify-between items-center mb-3">
<span class="font-label-bold text-primary flex items-center gap-1"><span class="material-symbols-outlined text-sm">grid_view</span> Bitewing</span>
<span class="font-body-sm text-outline">Sep 28, 2023</span>
</div>
<div class="aspect-square bg-inverse-surface rounded-lg overflow-hidden">
<img class="w-full h-full object-cover opacity-70" data-alt="Clinical dental bitewing radiograph focused on molar health and occlusion. The visual style is high-contrast, professional-grade diagnostic imaging. The teeth are clearly defined against the darker soft tissue areas. The scene is illuminated by the digital glow of a lab monitor in a minimalist setting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAyU4znkuIpOBTnIQeVkeFalfPRauW9rPXmaww8NlzjCTpyYLFa-JV143oFFCnlYWmpIoNyEZZTcaZxSo2zOkk-X5oubbZD5r0cqYzOt9_S6GYVQI2U-52hfbixIQvwWniMfevVcnU157gu9ZCvbs1QQvV5dl4FMNgzaUrVQLt_7IL-kl9IrjjLKYqL6ZNpF71-DrSjcQRmizEPbW6b1cKq7N_WJP32YDUHjje7NC5yf_6aEkmDaQWESDOpr5TIbswfB-uIStukgqg"/>
</div>
</div>
</div>
</section>
<!-- Right Sidebar: Diagnostic Reports -->
<aside class="w-80 bg-surface-container-low border-l border-outline-variant flex flex-col overflow-hidden hover:shadow-md transition-shadow duration-300">
<div class="p-4 border-b border-outline-variant bg-surface-container">
<h3 class="font-headline-md text-headline-md">Diagnostic Reports</h3>
<p class="font-body-sm text-on-surface-variant">Validated by Imaging Center</p>
</div>
<div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-4">
<!-- Report Card 1 -->
<div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant hover:border-primary transition-colors cursor-pointer group">
<div class="flex justify-between items-start mb-2">
<div class="px-2 py-0.5 bg-error-container text-on-error-container rounded text-[10px] font-label-bold uppercase">Priority</div>
<span class="font-body-sm text-outline">2h ago</span>
</div>
<h4 class="font-label-bold text-on-surface mb-1">Radiology Assessment #882</h4>
<p class="font-body-sm text-on-surface-variant mb-3 line-clamp-2">Potential abscess detected near tooth 18 root apex. Immediate follow-up recommended...</p>
<div class="flex items-center justify-between">
<div class="flex -space-x-2">
<div class="w-6 h-6 rounded-full border-2 border-surface bg-primary text-[8px] flex items-center justify-center text-white">DR</div>
</div>
<span class="text-primary font-label-bold text-xs flex items-center gap-1 group-hover:underline">
                                View Full Report <span class="material-symbols-outlined text-xs">arrow_forward</span>
</span>
</div>
</div>
<!-- Report Card 2 -->
<div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant hover:border-primary transition-colors cursor-pointer group">
<div class="flex justify-between items-start mb-2">
<div class="px-2 py-0.5 bg-surface-container-high text-on-surface-variant rounded text-[10px] font-label-bold uppercase">Routine</div>
<span class="font-body-sm text-outline">Nov 15</span>
</div>
<h4 class="font-label-bold text-on-surface mb-1">Caries Progression Report</h4>
<p class="font-body-sm text-on-surface-variant mb-3 line-clamp-2">Comparison of Oct and Nov bitewings show stabilization of interproximal decay on tooth 14.</p>
<div class="flex items-center justify-between">
<div class="flex -space-x-2">
<div class="w-6 h-6 rounded-full border-2 border-surface bg-primary text-[8px] flex items-center justify-center text-white">MS</div>
</div>
<span class="text-primary font-label-bold text-xs flex items-center gap-1 group-hover:underline">
                                View Full Report <span class="material-symbols-outlined text-xs">arrow_forward</span>
</span>
</div>
</div>
<!-- Report Card 3 -->
<div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant hover:border-primary transition-colors cursor-pointer group">
<div class="flex justify-between items-start mb-2">
<div class="px-2 py-0.5 bg-secondary-container text-on-secondary-container rounded text-[10px] font-label-bold uppercase">Lab Sync</div>
<span class="font-body-sm text-outline">Oct 29</span>
</div>
<h4 class="font-label-bold text-on-surface mb-1">Endodontic Preview</h4>
<p class="font-body-sm text-on-surface-variant mb-3 line-clamp-2">3D reconstruction of mandibular canal in relation to wisdom tooth positioning.</p>
<div class="flex items-center justify-between">
<div class="flex -space-x-2">
<div class="w-6 h-6 rounded-full border-2 border-surface bg-primary text-[8px] flex items-center justify-center text-white">AI</div>
</div>
<span class="text-primary font-label-bold text-xs flex items-center gap-1 group-hover:underline">
                                View Full Report <span class="material-symbols-outlined text-xs">arrow_forward</span>
</span>
</div>
</div>
</div>
<!-- Pipeline Mini Tracker -->
<div class="p-4 bg-surface-container-lowest border-t border-outline-variant hover:shadow-md transition-shadow duration-300">
<h5 class="font-label-bold text-xs mb-3 uppercase tracking-wider text-outline">Imaging Pipeline</h5>
<div class="space-y-3">
<div class="flex items-center justify-between mb-1">
<span class="font-body-sm text-primary">Request #901</span>
<span class="text-[10px] font-label-bold">75%</span>
</div>
<div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
<div class="h-full bg-primary" style="width: 75%"></div>
</div>
<p class="text-[10px] text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">schedule</span> Processing by Lab B
                        </p>
</div>
</div>
</aside>
</div>
</main>
<!-- Modal: Image Viewer (Hidden by default) -->
<div class="fixed inset-0 z-[100] bg-black/95 flex flex-col hidden transition-all duration-300" id="imageViewer">
<header class="h-16 flex items-center justify-between px-gutter text-white border-b border-white/10">
<div class="flex items-center gap-4">
<button class="p-2 hover:bg-white/10 rounded-full transition-colors" onclick="closeViewer()">
<span class="material-symbols-outlined">close</span>
</button>
<div>
<h2 class="font-headline-md" id="viewerTitle">Panoramic X-Ray</h2>
<p class="text-xs text-white/60" id="viewerDate">Date: October 12, 2023</p>
</div>
</div>
<div class="flex items-center gap-4">
<div class="flex items-center gap-1 bg-white/10 px-3 py-1 rounded-full">
<button class="p-1 hover:text-primary transition-colors"><span class="material-symbols-outlined">zoom_out</span></button>
<span class="text-xs font-label-bold w-12 text-center">100%</span>
<button class="p-1 hover:text-primary transition-colors"><span class="material-symbols-outlined">zoom_in</span></button>
</div>
<div class="h-8 w-[1px] bg-white/10"></div>
<button class="flex items-center gap-2 bg-primary px-4 py-2 rounded-lg font-label-bold hover:opacity-90">
<span class="material-symbols-outlined text-sm">edit</span> Save Annotations
                </button>
</div>
</header>
<div class="flex-1 flex overflow-hidden">
<!-- Annotation Tools -->
<aside class="w-16 bg-white/5 flex flex-col items-center py-6 gap-6 border-r border-white/10">
<button class="text-white hover:text-primary active:scale-95 transition-all"><span class="material-symbols-outlined">draw</span></button>
<button class="text-white hover:text-primary active:scale-95 transition-all"><span class="material-symbols-outlined">square_foot</span></button>
<button class="text-white hover:text-primary active:scale-95 transition-all"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">circle</span></button>
<button class="text-white hover:text-primary active:scale-95 transition-all"><span class="material-symbols-outlined">text_fields</span></button>
<div class="w-8 h-[1px] bg-white/10"></div>
<button class="text-white hover:text-primary active:scale-95 transition-all"><span class="material-symbols-outlined">brightness_6</span></button>
<button class="text-white hover:text-primary active:scale-95 transition-all"><span class="material-symbols-outlined">contrast</span></button>
<button class="text-white hover:text-primary active:scale-95 transition-all"><span class="material-symbols-outlined">invert_colors</span></button>
</aside>
<!-- Main Viewing Area -->
<div class="flex-1 relative flex items-center justify-center p-8">
<img alt="Zoomed X-Ray" class="max-w-full max-h-full object-contain shadow-2xl" id="mainViewerImg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBnBgPwvU1VVmUCkrTbDjGWJRE7crpz7Vv3dkxeBSOac47SmtlIi9MdAWpWIKYVwkvyz7RkRFbl8zstvZ0WMUa8iPlRIT1S3glYGHAIpz8GLnsO_wxqRZfFMi_oTZsUCND0EaJQ3wWxyWaWwyJvOcj4wlaZ8j8-HIR0_i706LnuVqvpCgEWm9XlLt6P7sK07cXywUN99dVb0qSRDIlIj34ARi3kq9dH46bbj9YrxK__Gn_ZHNyuLGD092h41sASdgIBkeEQNi2Kw-Y"/>
<!-- Artificial Annotation Overlays -->
<div class="absolute top-1/2 left-1/3 w-12 h-12 border-2 border-red-500 rounded-full animate-pulse flex items-center justify-center">
<span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-red-500 text-white text-[10px] px-2 py-0.5 rounded whitespace-nowrap">Anomalous Density</span>
</div>
</div>
<!-- Analysis Sidebar -->
<aside class="w-72 bg-white/5 border-l border-white/10 p-6 flex flex-col">
<h3 class="font-label-bold text-white mb-4 flex items-center gap-2">
<span class="material-symbols-outlined text-sm text-primary">analytics</span> AI Insights
                </h3>
<div class="space-y-4">
<div class="p-3 bg-white/10 rounded-lg border border-white/5">
<span class="text-[10px] font-label-bold text-white/50 uppercase">Findings</span>
<p class="text-xs text-white/90 mt-1">Tooth #18: Distal cervical burnout vs. early caries. Recommendation: Clinical verification.</p>
</div>
<div class="p-3 bg-white/10 rounded-lg border border-white/5">
<span class="text-[10px] font-label-bold text-white/50 uppercase">Bone Levels</span>
<p class="text-xs text-white/90 mt-1">Moderate horizontal bone loss in posterior quadrants. Consistent with Stage II Periodontitis.</p>
</div>
</div>
<div class="mt-auto">
<button class="w-full border border-white/20 text-white py-2 rounded font-label-bold text-xs hover:bg-white/5 transition-colors">Compare with Previous</button>
</div>
</aside>
</div>
</div>
<!-- Modal: Request New Imaging (Hidden by default) -->
<div class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/60 hidden" id="requestModal">
<div class="bg-surface-container-lowest w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
<div class="p-6 border-b border-outline-variant flex justify-between items-center">
<h3 class="font-headline-lg text-headline-lg text-primary">New Imaging Request</h3>
<button class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-high rounded-full p-1" onclick="closeRequestModal()">close</button>
</div>
<div class="p-6 space-y-6">
<div>
<label class="block font-label-bold mb-2">Patient Context</label>
<div class="p-3 bg-surface-container-low rounded-lg flex items-center gap-3">
<img alt="Patient" class="w-8 h-8 rounded-full" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDVhNDpgUKfI76OGxH9KqYOsCJFdioZvNziw80YcVPZ6Tet8YrO1GSJXGgt6LiTKJ6_NVHt4NfwlJtS04ZjzKFdy6p66yrD9I8PyU6spKO7sIe3gbpYUYgpq3vO0zVK_blhISoDDmCxbIRdBIBCCG0ejZjfAYJ211vGR7FUnxoCMnrtXn54XOasu_YHm68wUOiqLznPQ7JWC3vEZmLClYBtnET96tlJGiXh9lcRSrSmxlZC0kHBD4iJ-_gV9cDueptwRaWOYFTb8eU"/>
<span class="font-body-md font-semibold">Elena Rodriguez</span>
<span class="text-xs text-on-surface-variant">DOB: 05/12/1989</span>
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block font-label-bold mb-2">Modality</label>
<select class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary text-body-md">
<option>CBCT (3D Imaging)</option>
<option>Panoramic X-Ray</option>
<option>Bitewing Series</option>
<option>Periapical (Specific Tooth)</option>
</select>
</div>
<div>
<label class="block font-label-bold mb-2">Priority</label>
<div class="flex gap-2">
<button class="flex-1 py-2 border border-outline-variant rounded-lg font-label-bold text-xs hover:border-primary">Routine</button>
<button class="flex-1 py-2 bg-error/10 text-error border border-error/20 rounded-lg font-label-bold text-xs">Urgent</button>
</div>
</div>
</div>
<div>
<label class="block font-label-bold mb-2">Clinical Indication / Reason</label>
<textarea class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary text-body-md h-24 p-3" placeholder="e.g., Pre-operative implant planning for tooth #19..."></textarea>
</div>
<div class="flex items-center gap-2 p-3 bg-primary/5 rounded-lg border border-primary/20">
<span class="material-symbols-outlined text-primary">info</span>
<p class="text-[11px] text-on-primary-fixed-variant">This request will be automatically synced with the X-Ray Imaging Dashboard and Lab 04.</p>
</div>
</div>
<div class="p-6 bg-surface-container border-t border-outline-variant flex justify-end gap-3">
<button class="px-6 py-2 font-label-bold text-on-surface-variant" onclick="closeRequestModal()">Cancel</button>
<button class="px-8 py-2 bg-primary text-on-primary font-label-bold rounded-lg hover:opacity-90 shadow-lg shadow-primary/20">Send Request</button>
</div>
</div>
</div>
<script>
        function openViewer(title, date) {
            const viewer = document.getElementById('imageViewer');
            document.getElementById('viewerTitle').innerText = title;
            document.getElementById('viewerDate').innerText = 'Date: ' + date;
            viewer.classList.remove('hidden');
        }

        function closeViewer() {
            document.getElementById('imageViewer').classList.add('hidden');
        }

        function openRequestModal() {
            document.getElementById('requestModal').classList.remove('hidden');
        }

        function closeRequestModal() {
            document.getElementById('requestModal').classList.add('hidden');
        }

        // Lightweight zoom interaction simulation
        const img = document.getElementById('mainViewerImg');
        img.addEventListener('click', function(e) {
            this.style.transform = this.style.transform === 'scale(1.5)' ? 'scale(1)' : 'scale(1.5)';
            this.style.cursor = this.style.transform === 'scale(1.5)' ? 'zoom-out' : 'zoom-in';
            this.style.transition = 'transform 0.3s ease';
        });

        // Toggle reports
        document.querySelectorAll('aside .bg-surface-container-lowest').forEach(card => {
            card.addEventListener('click', () => {
                card.classList.toggle('bg-primary-container/10');
            });
        });
    </script>
</body></html>
