<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow - Gestion de Clinique Médicale</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Police Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50/60 text-slate-800 antialiased min-h-screen flex flex-col">

<!-- Barre de navigation -->
<header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        <!-- Logo MedFlow -->
        <a href="index.php?action=home" class="flex items-center gap-3 no-underline">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-sky-200">
                <span class="text-xl font-bold">M</span>
            </div>
            <div>
                <span class="text-lg font-extrabold text-slate-900 tracking-tight">Med<span class="text-sky-500">Flow</span></span>
                <span class="block text-[9px] text-slate-400 font-bold tracking-widest uppercase">Smart Clinic</span>
            </div>
        </a>

        <!-- Boutons de navigation -->
        <div class="flex items-center gap-3">
            <?php if (isset($_SESSION['user'])): ?>
                <!-- Utilisateur connecté -->
                <span class="text-xs font-semibold text-slate-500">
                    <?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>
                </span>
                <?php if ($_SESSION['user']['role'] === 'patient'): ?>
                    <a href="index.php?action=patient_dashboard" class="text-xs font-bold text-sky-600 hover:underline no-underline">Mon Espace</a>
                <?php elseif ($_SESSION['user']['role'] === 'medecin'): ?>
                    <a href="index.php?action=doctor_dashboard" class="text-xs font-bold text-emerald-600 hover:underline no-underline">Mon Cabinet</a>
                <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
                    <a href="index.php?action=admin_dashboard" class="text-xs font-bold text-purple-600 hover:underline no-underline">Administration</a>
                <?php endif; ?>
                <a href="index.php?action=logout" class="inline-flex items-center gap-2 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs px-4 py-2 rounded-xl border border-slate-200/60 transition-all no-underline">
                    Déconnexion
                </a>
            <?php else: ?>
                <!-- Visiteur -->
                <a href="index.php?action=login" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-all no-underline">
                    Se Connecter
                </a>
            <?php endif; ?>
        </div>

    </div>
</header>

<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
