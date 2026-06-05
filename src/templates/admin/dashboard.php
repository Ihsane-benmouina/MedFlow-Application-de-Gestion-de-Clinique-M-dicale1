<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($medecins)) $medecins = [];
if (!isset($specialites)) $specialites = [];
if (!isset($listeSpecialites)) $listeSpecialites = [];
if (!isset($totalMedecins)) $totalMedecins = 0;
if (!isset($totalPatients)) $totalPatients = 0;
if (!isset($totalRdv)) $totalRdv = 0;
if (!isset($rdvEnAttente)) $rdvEnAttente = 0;
include __DIR__ . '/../layout/header.php';
?>

<div class="flex flex-col lg:flex-row gap-8 min-h-[calc(100vh-12rem)]">

    
    <aside class="w-full lg:w-64 shrink-0">
        <div class="bg-slate-900 text-slate-400 rounded-2xl p-4 sticky top-24 shadow-xl border border-slate-800 space-y-6">
            <div class="px-3 py-2 border-b border-slate-800/60">
                <p class="text-[10px] font-bold uppercase tracking-widest text-purple-400">Administration</p>
                <h4 class="text-white font-extrabold text-sm tracking-tight mt-0.5">
                    <?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>
                </h4>
            </div>
            <nav class="space-y-1">
                <button onclick="switchAdminTab('adm-stats')" id="btn-adm-stats"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-purple-500/10 to-purple-500/20 border border-purple-500/20 cursor-pointer adm-nav">
                    Vue d'ensemble
                </button>
                <button onclick="switchAdminTab('adm-add')" id="btn-adm-add"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer adm-nav">
                    Ajouter un Médecin
                </button>
                <button onclick="switchAdminTab('adm-list')" id="btn-adm-list"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer adm-nav">
                    Liste des Médecins
                </button>
            </nav>
        </div>
    </aside>

       <div class="flex-1 space-y-6">

        <!-- Messages -->
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="bg-emerald-50 text-emerald-700 text-xs font-semibold p-3 rounded-xl border border-emerald-100">
                <?= htmlspecialchars($_SESSION['success_msg']); unset($_SESSION['success_msg']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="bg-rose-50 text-rose-700 text-xs font-semibold p-3 rounded-xl border border-rose-100">
                <?= htmlspecialchars($_SESSION['error_msg']); unset($_SESSION['error_msg']); ?>
            </div>
        <?php endif; ?> 

                    <!-- Cartes de statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase">Médecins Actifs</p>
                    <h3 class="text-2xl font-extrabold text-purple-600 mt-1"><?= $totalMedecins ?></h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase">Patients</p>
                    <h3 class="text-2xl font-extrabold text-sky-600 mt-1"><?= $totalPatients ?></h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase">Total RDV</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1"><?= $totalRdv ?></h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase">RDV En Attente</p>
                    <h3 class="text-2xl font-extrabold text-amber-500 mt-1"><?= $rdvEnAttente ?></h3>
                </div>
            </div>

