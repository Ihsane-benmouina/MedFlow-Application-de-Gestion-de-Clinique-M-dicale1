<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($appointments)) $appointments = [];
if (!isset($creneaux)) $creneaux = [];

$countConfirmes = 0;
$countAttente = 0;
$countAnnules = 0;
$countTermines = 0;

foreach ($appointments as $rdv) {
    if ($rdv['statut'] === 'Confirmé') $countConfirmes++;
    if ($rdv['statut'] === 'En attente') $countAttente++;
    if ($rdv['statut'] === 'Annulé') $countAnnules++;
    if ($rdv['statut'] === 'Terminé') $countTermines++;
}

include __DIR__ . '/../layout/header.php';
?>

<div class="flex flex-col lg:flex-row gap-8 min-h-[calc(100vh-12rem)]">

    <!-- SIDEBAR MÉDECIN -->
    <aside class="w-full lg:w-64 shrink-0">
        <div class="bg-slate-900 text-slate-400 rounded-2xl p-4 sticky top-24 shadow-xl border border-slate-800 space-y-6">
            <div class="px-3 py-2 border-b border-slate-800/60">
                <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-400">Espace Praticien</p>
                <h4 class="text-white font-extrabold text-sm tracking-tight mt-0.5">
                    Dr. <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?>
                </h4>
            </div>
            <nav class="space-y-1">
                <button onclick="switchDocTab('doc-stats')" id="btn-doc-stats"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-500/10 to-emerald-500/20 border border-emerald-500/20 cursor-pointer doc-nav">
                    Tableau de bord
                </button>
                <button onclick="switchDocTab('doc-agenda')" id="btn-doc-agenda"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer doc-nav">
                    Gestion de l'Agenda
                </button>
                <button onclick="switchDocTab('doc-consult')" id="btn-doc-consult"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer doc-nav">
                    Consultations
                </button>
                <button onclick="switchDocTab('doc-creneaux')" id="btn-doc-creneaux"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer doc-nav">
                    Gérer mes créneaux
                </button>
            </nav>
        </div>
    </aside>

    <!-- CONTENU -->
    <div class="flex-1 space-y-6">

        <!-- Messages -->
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="bg-emerald-50 text-emerald-700 text-xs font-semibold p-3 rounded-xl border border-emerald-100">
                <?= htmlspecialchars($_SESSION['success_msg']); unset($_SESSION['success_msg']); ?>
            </div>
        <?php endif; ?>

        <!-- ===== TAB 1 : STATISTIQUES ===== -->
        <div id="doc-stats" class="space-y-6 doc-tab">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Bonjour, Dr. <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?></h2>
                <p class="text-xs text-slate-400">Vue d'ensemble de votre activité.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase">Confirmés</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1"><?= $countConfirmes ?></h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase">En Attente</p>
                    <h3 class="text-2xl font-extrabold text-amber-500 mt-1"><?= $countAttente ?></h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase">Annulés</p>
                    <h3 class="text-2xl font-extrabold text-rose-500 mt-1"><?= $countAnnules ?></h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase">Terminés</p>
                    <h3 class="text-2xl font-extrabold text-sky-600 mt-1"><?= $countTermines ?></h3>
                </div>
            </div>
        </div>

        <!-- ===== TAB 2 : AGENDA / GESTION RDV ===== -->
        <div id="doc-agenda" class="hidden space-y-6 doc-tab">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Gestion des Rendez-vous</h2>
                <p class="text-xs text-slate-400">Confirmez ou annulez les rendez-vous de vos patients.</p>
            </div>

            <?php if (empty($appointments)): ?>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center">
                    <p class="text-sm text-slate-400">Aucun rendez-vous.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($appointments as $rdv): ?>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">
                                        <?= htmlspecialchars($rdv['patient_prenom'] . ' ' . $rdv['patient_nom']) ?>
                                    </h4>
                                    <p class="text-xs text-slate-400"><?= date('d/m/Y à H:i', strtotime($rdv['heure_debut'])) ?></p>
                                    <span class="text-xs font-semibold text-slate-500"><?= htmlspecialchars($rdv['statut']) ?></span>
                                </div>

                                <?php if ($rdv['statut'] === 'En attente'): ?>
                                    <div class="flex gap-2">
                                        <form method="POST" action="index.php?action=doctor_update_statut">
                                            <?= csrfTokenField() ?>
                                            <input type="hidden" name="id_rdv" value="<?= $rdv['id_rdv'] ?>">
                                            <input type="hidden" name="statut_action" value="Confirmé">
                                            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 cursor-pointer">
                                                Confirmer
                                            </button>
                                        </form>
                                        <form method="POST" action="index.php?action=doctor_update_statut">
                                            <?= csrfTokenField() ?>
                                            <input type="hidden" name="id_rdv" value="<?= $rdv['id_rdv'] ?>">
                                            <input type="hidden" name="statut_action" value="Annulé">
                                            <button type="submit" class="px-4 py-2 bg-rose-500 text-white text-xs font-bold rounded-xl hover:bg-rose-600 cursor-pointer">
                                                Annuler
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== TAB 3 : CONSULTATIONS ===== -->
        <div id="doc-consult" class="hidden space-y-6 doc-tab">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Consultations & Prescriptions</h2>
                <p class="text-xs text-slate-400">Terminez une consultation et rédigez l'ordonnance.</p>
            </div>

            <?php
                // Filtrer uniquement les RDV confirmés pour les consultations
                $rdvConfirmes = array_filter($appointments, function($r) {
                    return $r['statut'] === 'Confirmé';
                });
            ?>

            <?php if (empty($rdvConfirmes)): ?>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center">
                    <p class="text-sm text-slate-400">Aucune consultation confirmée en attente.</p>
                </div>
            <?php else: ?>
                <?php foreach ($rdvConfirmes as $rdv): ?>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                        <h4 class="text-sm font-bold text-slate-900">
                            <?= htmlspecialchars($rdv['patient_prenom'] . ' ' . $rdv['patient_nom']) ?>
                        </h4>
                        <p class="text-xs text-slate-400"><?= date('d/m/Y à H:i', strtotime($rdv['heure_debut'])) ?></p>

                        <form method="POST" action="index.php?action=terminer_consultation" class="mt-3 space-y-3">
                            <?= csrfTokenField() ?>
                            <input type="hidden" name="id_rdv" value="<?= $rdv['id_rdv'] ?>">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Ordonnance / Prescription</label>
                                <textarea name="ordonnance" rows="4" placeholder="Rédiger l'ordonnance..."
                                          class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500" required></textarea>
                            </div>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 cursor-pointer">
                                Terminer la consultation
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- ===== TAB 4 : GÉRER CRÉNEAUX ===== -->
        <div id="doc-creneaux" class="hidden space-y-6 doc-tab">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Gérer mes créneaux</h2>
                <p class="text-xs text-slate-400">Ajoutez de nouveaux créneaux de disponibilité.</p>
            </div>

            <!-- Formulaire d'ajout -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                <h4 class="text-sm font-bold text-slate-900 mb-3">Ajouter un créneau</h4>
                <form method="POST" action="index.php?action=ajouter_creneau" class="flex flex-wrap gap-3 items-end">
                    <?= csrfTokenField() ?>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Début</label>
                        <input type="datetime-local" name="heure_debut"
                               class="p-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Fin</label>
                        <input type="datetime-local" name="heure_fin"
                               class="p-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 cursor-pointer">
                        Ajouter
                    </button>
                </form>
            </div>

            <!-- Liste des créneaux existants -->
            <div class="space-y-2">
                <?php foreach ($creneaux as $cr): ?>
                    <div class="bg-white p-4 rounded-xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-semibold text-slate-700">
                                <?= date('d/m/Y', strtotime($cr['heure_debut'])) ?>
                            </span>
                            <span class="text-xs text-slate-400 ml-2">
                                <?= date('H:i', strtotime($cr['heure_debut'])) ?> - <?= date('H:i', strtotime($cr['heure_fin'])) ?>
                            </span>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold rounded-lg <?= $cr['disponible'] ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-600' ?>">
                            <?= $cr['disponible'] ? 'Disponible' : 'Réservé' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<script>
// Changer d'onglet médecin
function switchDocTab(tabId) {
    document.querySelectorAll('.doc-tab').forEach(function(tab) {
        tab.classList.add('hidden');
    });
    document.getElementById(tabId).classList.remove('hidden');

    document.querySelectorAll('.doc-nav').forEach(function(btn) {
        btn.className = 'w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer doc-nav';
    });
    document.getElementById('btn-' + tabId).className = 'w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-500/10 to-emerald-500/20 border border-emerald-500/20 cursor-pointer doc-nav';
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
