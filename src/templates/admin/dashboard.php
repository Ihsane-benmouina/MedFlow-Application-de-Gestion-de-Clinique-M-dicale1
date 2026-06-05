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

