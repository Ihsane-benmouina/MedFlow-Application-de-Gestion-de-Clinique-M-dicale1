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


                        <!-- Spécialités -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs space-y-4">
                <h3 class="font-bold text-slate-900 text-sm">Médecins par Spécialité</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <?php foreach ($specialites as $spec): ?>
                        <div class="p-4 border border-slate-100 bg-slate-50/50 rounded-xl flex justify-between items-center">
                            <h4 class="text-sm font-bold text-slate-700"><?= htmlspecialchars($spec['nom']) ?></h4>
                            <span class="text-xs font-bold bg-white px-2.5 py-1 rounded-md border border-slate-200">
                                <?= $spec['total_medecins'] ?> médecin(s)
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


         <!-- ===== TAB 2 : AJOUTER MÉDECIN ===== -->
        <div id="adm-add" class="hidden space-y-6 adm-tab">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Ajouter un Médecin</h2>
                <p class="text-xs text-slate-400">Créez un nouveau compte médecin pour la clinique.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs">
                <form method="POST" action="index.php?action=admin_creer_medecin" class="space-y-4">
                    <?= csrfTokenField() ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nom</label>
                            <input type="text" name="nom" placeholder="Nom du médecin"
                                   class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Prénom</label>
                            <input type="text" name="prenom" placeholder="Prénom du médecin"
                                   class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                        <input type="email" name="email" placeholder="medecin@medflow.com"
                               class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Mot de passe</label>
                        <input type="password" name="password" placeholder="Mot de passe"
                               class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Spécialité</label>
                        <select name="id_specialite"
                                class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-purple-500" required>
                            <option value="">-- Choisir une spécialité --</option>
                            <?php foreach ($listeSpecialites as $spec): ?>
                                <option value="<?= $spec['id'] ?>"><?= htmlspecialchars($spec['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs py-3.5 rounded-xl cursor-pointer transition-all">
                        Créer le médecin
                    </button>
                </form>
            </div>
        </div>

         <!-- ===== TAB 3 : LISTE DES MÉDECINS ===== -->
        <div id="adm-list" class="hidden space-y-6 adm-tab">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Liste des Médecins</h2>
                <p class="text-xs text-slate-400">Gérez, modifiez ou désactivez les comptes médecins.</p>
            </div>

            <?php if (empty($medecins)): ?>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center">
                    <p class="text-sm text-slate-400">Aucun médecin enregistré.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($medecins as $med): ?>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900">
                                        Dr. <?= htmlspecialchars($med['prenom'] . ' ' . $med['nom']) ?>
                                    </h4>
                                    <p class="text-xs text-purple-600 font-semibold"><?= htmlspecialchars($med['specialite_nom']) ?></p>
                                    <p class="text-xs text-slate-400"><?= htmlspecialchars($med['email']) ?></p>
                                    <span class="text-xs font-bold <?= $med['actif'] ? 'text-emerald-600' : 'text-rose-600' ?>">
                                        <?= $med['actif'] ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </div>

                                <div class="flex gap-2 flex-wrap">
                                    <!-- Bouton Modifier -->
                                    <button onclick="toggleEditForm(<?= $med['id_medecin'] ?>)"
                                            class="px-4 py-2 bg-sky-50 text-sky-700 text-xs font-bold rounded-xl border border-sky-100 hover:bg-sky-100 cursor-pointer">
                                        Modifier
                                    </button>

                                       <!-- Bouton Activer/Désactiver -->
                                    <?php if ($med['actif']): ?>
                                        <a href="index.php?action=admin_toggle_medecin&id=<?= $med['id_medecin'] ?>&toggle=desactiver"
                                           class="px-4 py-2 bg-rose-50 text-rose-700 text-xs font-bold rounded-xl border border-rose-100 hover:bg-rose-100 no-underline">
                                            Désactiver
                                        </a>
                                    <?php else: ?>
                                        <a href="index.php?action=admin_toggle_medecin&id=<?= $med['id_medecin'] ?>&toggle=activer"
                                           class="px-4 py-2 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100 hover:bg-emerald-100 no-underline">
                                            Activer
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>





