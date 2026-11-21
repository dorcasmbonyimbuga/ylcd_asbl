<?php
/** Indique si la version anglaise d'un élément est remplie (variable $a = ligne, $trField = champ vérifié, "titre" par défaut). */
$__f = $trField ?? 'titre';
$__ok = trim((string) ($a[$__f . '_en'] ?? '')) !== '' && trim((string) ($a[$__f . '_fr'] ?? '')) !== '';
?>
<span class="tr-badge <?= $__ok ? 'ok' : 'missing' ?>" title="<?= e($__ok ? __('Français et anglais remplis') : __('Traduction anglaise manquante')) ?>">
  FR <i class="fa-solid <?= $__ok ? 'fa-check' : 'fa-xmark' ?>"></i> EN
</span>
