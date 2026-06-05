<?php

if (!isset($specialites)) $specialites = [];
if (!isset($medecins)) $medecins = [];
include __DIR__ . '/../layout/header.php';
?>

<!-- Hero de recherche -->
<div class="space-y-8">

    <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-sky-500/15 via-transparent to-transparent"></div>
        <div class="max-w-xl relative z-10 space-y-4">
            <span class="text-sky-400 text-xs font-bold uppercase tracking-widest bg-sky-500/10 px-3 py-1 rounded-full border border-sky-500/20">Prendre rendez-vous</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">Votre santé, <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-blue-400">notre priorité.</span></h1>
            <p class="text-xs text-slate-300 max-w-sm">Recherchez un médecin et consultez ses créneaux disponibles.</p>
        </div>

        <!-- Barre de recherche -->
        <div class="mt-8 bg-white p-2 rounded-2xl shadow-2xl flex gap-2 relative z-10 border border-slate-200/50 max-w-2xl">
            <div class="flex-1 relative flex items-center">
                <span class="absolute left-4 text-slate-400 text-base">&#128269;</span>
                <input type="text" id="nameSearch" oninput="filterDoctors()" placeholder="Nom du médecin (ex: Alami...)"
                       class="w-full pl-11 pr-4 py-3.5 text-slate-800 rounded-xl focus:outline-none text-sm font-medium placeholder-slate-400">
            </div>
        </div>
    </div>

  
    <div class="space-y-3">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Parcourir par spécialité</h3>
        <div class="flex items-center gap-3 overflow-x-auto pb-3">
            <button onclick="filterBySpecialite('all')" id="btn-spec-all"
                    class="shrink-0 px-5 py-3 rounded-2xl text-sm font-bold bg-sky-500 text-white shadow-lg cursor-pointer transition-all spec-btn">
                Tous les médecins
            </button>
            <?php foreach ($specialites as $spec): ?>
                <button onclick="filterBySpecialite('<?= $spec['id'] ?>')" id="btn-spec-<?= $spec['id'] ?>"
                        class="shrink-0 px-5 py-3 rounded-2xl text-sm font-semibold bg-white text-slate-700 border border-slate-100 cursor-pointer transition-all spec-btn">
                    <?= htmlspecialchars($spec['nom']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

  
    <div class="space-y-4" id="doctors-list">
        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Praticiens disponibles</h3>

        <?php if (empty($medecins)): ?>
            <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center">
                <p class="text-sm text-slate-400">Aucun médecin disponible pour le moment.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($medecins as $med): ?>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs doctor-card"
                 data-name="<?= htmlspecialchars(mb_strtolower($med['nom'] . ' ' . $med['prenom'])) ?>"
                 data-specialite="<?= $med['id_specialite'] ?>">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-900">
                            Dr. <?= htmlspecialchars($med['prenom'] . ' ' . $med['nom']) ?>
                        </h4>
                        <p class="text-xs text-sky-600 font-semibold mt-0.5"><?= htmlspecialchars($med['specialite_nom']) ?></p>
                    </div>
                </div>

             
                <?php if (!empty($med['creneaux'])): ?>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <?php foreach ($med['creneaux'] as $creneau): ?>
                            <?php
                                $dateFormatted = date('d/m H:i', strtotime($creneau['heure_debut']));
                            ?>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'patient'): ?>
                                <form method="POST" action="index.php?action=reserver_rdv" class="inline">
                                    <?= csrfTokenField() ?>
                                    <input type="hidden" name="id_creneau" value="<?= $creneau['id'] ?>">
                                    <input type="hidden" name="id_medecin" value="<?= $med['id_medecin'] ?>">
                                    <button type="submit"
                                            class="px-3 py-2 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100 hover:bg-emerald-100 cursor-pointer transition-all">
                                        <?= $dateFormatted ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="px-3 py-2 bg-slate-50 text-slate-600 text-xs font-semibold rounded-xl border border-slate-100">
                                    <?= $dateFormatted ?>
                                </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-slate-400 mt-3">Aucun créneau disponible.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (!isset($_SESSION['user'])): ?>
        <div class="bg-sky-50 border border-sky-100 p-6 rounded-2xl text-center">
            <p class="text-sm text-sky-700 font-semibold">
                Connectez-vous pour réserver un rendez-vous.
                <a href="index.php?action=login" class="underline font-bold">Se connecter</a>
            </p>
        </div>
    <?php endif; ?>
</div>

<script>

function filterDoctors() {
    const searchValue = document.getElementById('nameSearch').value.toLowerCase();
    const cards = document.querySelectorAll('.doctor-card');

    cards.forEach(function(card) {
        const name = card.getAttribute('data-name');
        if (name.includes(searchValue)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}


function filterBySpecialite(specId) {
    const cards = document.querySelectorAll('.doctor-card');
    const buttons = document.querySelectorAll('.spec-btn');

    
    buttons.forEach(function(btn) {
        btn.className = 'shrink-0 px-5 py-3 rounded-2xl text-sm font-semibold bg-white text-slate-700 border border-slate-100 cursor-pointer transition-all spec-btn';
    });

    const activeBtn = document.getElementById('btn-spec-' + specId);
    if (activeBtn) {
        activeBtn.className = 'shrink-0 px-5 py-3 rounded-2xl text-sm font-bold bg-sky-500 text-white shadow-lg cursor-pointer transition-all spec-btn';
    }

  
    cards.forEach(function(card) {
        if (specId === 'all' || card.getAttribute('data-specialite') === specId) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>