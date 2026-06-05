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

