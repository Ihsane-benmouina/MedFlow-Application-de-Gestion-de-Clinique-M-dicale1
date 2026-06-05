<?php


require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->query("SELECT id, password FROM utilisateurs");
$users = $stmt->fetchAll();

$updated = 0;
foreach ($users as $user) {
    if (str_starts_with($user['password'], '$2y$')) {
        continue;
    }
    $hashed = password_hash($user['password'], PASSWORD_BCRYPT);
    $update = $pdo->prepare("UPDATE utilisateurs SET password = :pw WHERE id = :id");
    $update->execute([':pw' => $hashed, ':id' => $user['id']]);
    $updated++;
}

echo "Done. Hashed {$updated} plaintext password(s).\n";
