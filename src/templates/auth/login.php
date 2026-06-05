<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-6 px-4">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-xl relative overflow-hidden">

        <!-- Effets décoratifs -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-sky-500/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>

        <!-- ===== FORMULAIRE DE CONNEXION ===== -->
        <div id="login-form-box" class="space-y-6">
            <div class="text-center">
                <h2 class="mt-2 text-2xl font-extrabold text-slate-900 tracking-tight">Connexion</h2>
                <p class="mt-1.5 text-xs text-slate-400 font-medium">Saisissez vos identifiants pour accéder à votre espace.</p>
            </div>

            <!-- Messages d'erreur -->
            <?php if (isset($_SESSION['error_msg'])): ?>
                <div class="bg-rose-50 text-rose-700 text-xs font-semibold p-3 rounded-xl border border-rose-100 text-center">
                    <?= htmlspecialchars($_SESSION['error_msg']); unset($_SESSION['error_msg']); ?>
                </div>
            <?php endif; ?>

            <!-- Message de succès -->
            <?php if (isset($_SESSION['success_msg'])): ?>
                <div class="bg-emerald-50 text-emerald-700 text-xs font-semibold p-3 rounded-xl border border-emerald-100 text-center">
                    <?= htmlspecialchars($_SESSION['success_msg']); unset($_SESSION['success_msg']); ?>
                </div>
            <?php endif; ?>

            <form class="space-y-4" action="index.php?action=login" method="POST">
                <?= csrfTokenField() ?>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Adresse Email</label>
                    <input type="email" name="email" placeholder="nom@exemple.com"
                           class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium bg-slate-50/40" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Mot de passe</label>
                    <input type="password" name="password" placeholder="Votre mot de passe"
                           class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium bg-slate-50/40" required>
                </div>

                <button type="submit"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-3.5 rounded-xl cursor-pointer transition-all shadow-xs mt-2">
                    Se connecter
                </button>
            </form>

            <div class="text-center pt-2 border-t border-slate-100">
                <p class="text-xs text-slate-400 font-medium">
                    Nouveau sur notre plateforme ?
                    <button onclick="toggleAuthMode('register')" class="text-sky-500 font-bold hover:underline cursor-pointer ml-1">Créer un compte patient</button>
                </p>
            </div>
        </div>

        <!-- ===== FORMULAIRE D'INSCRIPTION ===== -->
        <div id="register-form-box" class="hidden space-y-6">
            <div class="text-center">
                <h2 class="mt-2 text-2xl font-extrabold text-slate-900 tracking-tight">Inscription Patient</h2>
                <p class="mt-1.5 text-xs text-slate-400 font-medium">Créez votre compte pour réserver un rendez-vous.</p>
            </div>

            <form class="space-y-3.5" action="index.php?action=register" method="POST">
                <?= csrfTokenField() ?>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nom</label>
                        <input type="text" name="nom" placeholder="Nassiri"
                               class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium bg-slate-50/40" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Prénom</label>
                        <input type="text" name="prenom" placeholder="Khalid"
                               class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium bg-slate-50/40" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Adresse Email</label>
                    <input type="email" name="email" placeholder="khalid@mail.com"
                           class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium bg-slate-50/40" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Mot de passe</label>
                    <input type="password" name="password" placeholder="Choisir un mot de passe"
                           class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium bg-slate-50/40" required>
                </div>

                <button type="submit"
                        class="w-full bg-sky-500 hover:bg-sky-600 text-white font-bold text-xs py-3.5 rounded-xl cursor-pointer transition-all shadow-xs mt-2">
                    Créer mon compte
                </button>
            </form>

            <div class="text-center pt-2 border-t border-slate-100">
                <p class="text-xs text-slate-400 font-medium">
                    Déjà un compte ?
                    <button onclick="toggleAuthMode('login')" class="text-sky-500 font-bold hover:underline cursor-pointer ml-1">Se connecter</button>
                </p>
            </div>
        </div>

    </div>
</div>

<script>

function toggleAuthMode(mode) {
    const loginBox = document.getElementById('login-form-box');
    const registerBox = document.getElementById('register-form-box');

    if (mode === 'register') {
        loginBox.classList.add('hidden');
        registerBox.classList.remove('hidden');
    } else {
        loginBox.classList.remove('hidden');
        registerBox.classList.add('hidden');
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>