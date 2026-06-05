<?php
// Variables par défaut
if (!isset($specialites)) $specialites = [];
if (!isset($medecins)) $medecins = [];
if (!isset($mesRendezVous)) $mesRendezVous = [];
if (!isset($mesOrdonnances)) $mesOrdonnances = [];
include __DIR__ . '/../layout/header.php';
?>

<div class="flex flex-col lg:flex-row gap-8 min-h-[calc(100vh-12rem)]">

    
    <aside class="w-full lg:w-64 shrink-0">
        <div class="bg-slate-900 text-slate-400 rounded-2xl p-4 sticky top-24 shadow-xl border border-slate-800 space-y-6">
            <div class="px-3 py-2 border-b border-slate-800/60">
                <p class="text-[10px] font-bold uppercase tracking-widest text-sky-400">Espace Patient</p>
                <h4 class="text-white font-extrabold text-sm tracking-tight mt-0.5">
                    <?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>
                </h4>
            </div>
            <nav class="space-y-1">
                <button onclick="switchTab('tab-book')" id="btn-tab-book"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-500/10 to-sky-500/20 border border-sky-500/20 cursor-pointer nav-btn">
                    Prendre un Rendez-vous
                </button>
                <button onclick="switchTab('tab-rdv')" id="btn-tab-rdv"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer nav-btn">
                    Mes Rendez-vous
                </button>
                <button onclick="switchTab('tab-ordo')" id="btn-tab-ordo"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer nav-btn">
                    Mes Ordonnances
                </button>
            </nav>
        </div>
    </aside>

    <div class="flex-1 space-y-6">

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

        <div id="tab-book" class="space-y-6 tab-content">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Rechercher un médecin</h2>
                <p class="text-xs text-slate-400">Trouvez un spécialiste et réservez un créneau.</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100">
                <input type="text" id="searchDoctor" oninput="filterDoctors()" placeholder="Rechercher par nom..."
                       class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div class="flex flex-wrap gap-2">
                <button onclick="filterSpec('all')" class="px-4 py-2 rounded-xl text-xs font-bold bg-sky-500 text-white cursor-pointer spec-btn">Tous</button>
                <?php foreach ($specialites as $spec): ?>
                    <button onclick="filterSpec('<?= $spec['id'] ?>')"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-600 border border-slate-100 cursor-pointer spec-btn">
                        <?= htmlspecialchars($spec['nom']) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($medecins as $med): ?>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs doc-card"
                     data-name="<?= htmlspecialchars(mb_strtolower($med['nom'] . ' ' . $med['prenom'])) ?>"
                     data-spec="<?= $med['id_specialite'] ?>">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900">Dr. <?= htmlspecialchars($med['prenom'] . ' ' . $med['nom']) ?></h4>
                            <p class="text-xs text-sky-600 font-semibold"><?= htmlspecialchars($med['specialite_nom']) ?></p>
                        </div>
                    </div>
                    <?php if (!empty($med['creneaux'])): ?>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <?php foreach ($med['creneaux'] as $cr): ?>
                                <form method="POST" action="index.php?action=reserver_rdv" class="inline">
                                    <?= csrfTokenField() ?>
                                    <input type="hidden" name="id_creneau" value="<?= $cr['id'] ?>">
                                    <input type="hidden" name="id_medecin" value="<?= $med['id_medecin'] ?>">
                                    <button type="submit"
                                            class="px-3 py-2 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100 hover:bg-emerald-100 cursor-pointer">
                                        <?= date('d/m H:i', strtotime($cr['heure_debut'])) ?>
                                    </button>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-slate-400 mt-2">Aucun créneau disponible.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="tab-rdv" class="hidden space-y-6 tab-content">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Mes Rendez-vous</h2>
                <p class="text-xs text-slate-400">Historique de tous vos rendez-vous.</p>
            </div>

            <?php if (empty($mesRendezVous)): ?>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center">
                    <p class="text-sm text-slate-400">Aucun rendez-vous pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($mesRendezVous as $rdv): ?>
                        <?php
                            $badgeClass = match($rdv['statut']) {
                                'En attente' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'Confirmé'   => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'Annulé'     => 'bg-rose-50 text-rose-700 border-rose-100',
                                'Terminé'    => 'bg-sky-50 text-sky-700 border-sky-100',
                                default      => 'bg-slate-50 text-slate-700 border-slate-100',
                            };
                        ?>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">
                                    Dr. <?= htmlspecialchars($rdv['medecin_prenom'] . ' ' . $rdv['medecin_nom']) ?>
                                </h4>
                                <p class="text-xs text-slate-500"><?= htmlspecialchars($rdv['specialite_nom']) ?></p>
                                <p class="text-xs text-slate-400 mt-1"><?= date('d/m/Y à H:i', strtotime($rdv['heure_debut'])) ?></p>
                            </div>
                            <span class="px-3 py-1.5 text-xs font-bold rounded-xl border <?= $badgeClass ?>">
                                <?= htmlspecialchars($rdv['statut']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div id="tab-ordo" class="hidden space-y-6 tab-content">
            <div class="border-b border-slate-200/60 pb-3">
                <h2 class="text-xl font-extrabold text-slate-900">Mes Ordonnances</h2>
                <p class="text-xs text-slate-400">Consultez et téléchargez vos ordonnances.</p>
            </div>

            <?php if (empty($mesOrdonnances)): ?>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center">
                    <p class="text-sm text-slate-400">Aucune ordonnance pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($mesOrdonnances as $ordo): ?>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">
                                        Dr. <?= htmlspecialchars($ordo['medecin_prenom'] . ' ' . $ordo['medecin_nom']) ?>
                                    </h4>
                                    <p class="text-xs text-sky-600 font-semibold"><?= htmlspecialchars($ordo['specialite_nom']) ?></p>
                                    <p class="text-xs text-slate-400 mt-1"><?= date('d/m/Y', strtotime($ordo['date_rdv'])) ?></p>
                                </div>
                                <a href="index.php?action=telecharger_ordonnance&id=<?= $ordo['id'] ?>"
                                   class="px-4 py-2 bg-sky-500 text-white text-xs font-bold rounded-xl hover:bg-sky-600 no-underline">
                                    Télécharger
                                </a>
                            </div>
                            <div class="mt-3 p-3 bg-slate-50 rounded-xl text-xs text-slate-600">
                                <?= nl2br(htmlspecialchars($ordo['contenu'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(function(tab) {
        tab.classList.add('hidden');
    });
    document.getElementById(tabId).classList.remove('hidden');

    document.querySelectorAll('.nav-btn').forEach(function(btn) {
        btn.className = 'w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold hover:bg-slate-800/50 hover:text-white cursor-pointer nav-btn';
    });
    document.getElementById('btn-' + tabId).className = 'w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-500/10 to-sky-500/20 border border-sky-500/20 cursor-pointer nav-btn';
}

function filterDoctors() {
    const search = document.getElementById('searchDoctor').value.toLowerCase();
    document.querySelectorAll('.doc-card').forEach(function(card) {
        const name = card.getAttribute('data-name');
        card.style.display = name.includes(search) ? '' : 'none';
    });
}

function filterSpec(specId) {
    document.querySelectorAll('.doc-card').forEach(function(card) {
        if (specId === 'all' || card.getAttribute('data-spec') === specId) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>