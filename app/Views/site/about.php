<?php
/** Page À propos. Variables : $services, $nbActivites. */
$bannerTitle = __('À propos de YLCD-ASBL');
$bannerSubtitle = __('Youth League for Community Development — Ligue de Jeunes pour le Développement Communautaire');
$bannerCrumb = __('A propos');
$bannerImg = 'img/leadership-2.jpg';
require ROOT . '/app/Views/partials/banner.php';

$valeurs = [
    ['fa-hands-holding-circle', 'Inclusion & équité', 'Accès égal à nos programmes, avec une attention particulière aux femmes, aux jeunes et aux personnes vulnérables.'],
    ['fa-shield-halved', 'Intégrité & redevabilité', 'Une gestion transparente et rigoureuse des ressources, envers nos bénéficiaires comme nos partenaires.'],
    ['fa-lightbulb', 'Innovation locale', 'Des solutions pratiques, adaptées aux réalités socio-économiques de Goma et du Nord-Kivu.'],
    ['fa-leaf', 'Durabilité', 'Transférer des compétences durables pour que les communautés deviennent autonomes.'],
];
?>
<!------------------ HISTOIRE -------------------->
<section class="py-5" id="about">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-6">
        <div class="position-relative">
          <img src="<?= asset('img/brevets-anglais-2026-1.jpg') ?>" alt="<?= e(__('Les membres et lauréats de YLCD-ASBL')) ?>" class="img-cover shadow-lg" style="height: 440px" />
          <div class="img-badge shadow">
            <h3 class="mb-1"><?= e(__('Depuis :year', ['year' => (int) config('site.annee_creation')])) ?></h3>
            <p class="mb-0 small"><?= e(__('Au service de la jeunesse et du développement communautaire à Goma, au Nord-Kivu.')) ?></p>
          </div>
        </div>
      </div>
      <div class="col-lg-6 ps-lg-5">
        <h1 class="title1"><?= e(__('QUI SOMMES NOUS?')) ?></h1>
        <p><?= __('<strong>YLCD-ASBL (Youth League for Community Development)</strong>, en français <em>Ligue de Jeunes pour le Développement Communautaire</em>, est une organisation à but non lucratif basée à Goma, en République Démocratique du Congo. Elle est née de la volonté de jeunes professionnels, enseignants et acteurs communautaires de mettre leurs compétences au service de leur communauté.') ?></p>
        <p><?= e(__('Nous sommes engagés dans le renforcement des capacités des communautés à travers des formations en langues, en métiers et en compétences pratiques. Nous offrons également un accompagnement en audit et consultance, et intervenons dans les domaines humanitaire, environnemental et de la santé.')) ?></p>
        <p><?= __('Face au chômage des jeunes, au manque d’accès à des formations pratiques, aux conséquences des conflits armés et à la dégradation de l’environnement dans l’Est de la RDC, nous avons fait un choix simple : <strong>donner à chaque personne les moyens d’être actrice de son propre développement.</strong>') ?></p>
      </div>
    </div>
  </div>
</section>

<!------------------ MISSION / VISION -------------------->
<section class="py-5 bg-light" id="mission">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-7">
        <h1 class="title1"><?= e(__('NOTRE MISSION')) ?></h1>
        <h4><?= e(__('Former, accompagner, agir pour demain.')) ?></h4>
        <p><?= e(__('Contribuer activement au développement humain durable en formant, accompagnant et soutenant les individus et les institutions dans la construction d’un avenir meilleur.')) ?></p>
        <div class="row g-3 mt-2">
          <div class="col-md-4"><div class="info-tile navy">
            <h6><i class="fa-solid fa-graduation-cap me-2 text-ylcd-navy"></i><?= e(__('Former')) ?></h6>
            <p class="mb-0 small"><?= e(__('Les jeunes et les adultes aux compétences linguistiques, numériques, techniques et professionnelles.')) ?></p>
          </div></div>
          <div class="col-md-4"><div class="info-tile purple">
            <h6><i class="fa-solid fa-handshake-angle me-2 text-ylcd-purple"></i><?= e(__('Accompagner')) ?></h6>
            <p class="mb-0 small"><?= e(__('Les individus, groupes et institutions par un appui sur mesure, basé sur l’écoute et des solutions concrètes.')) ?></p>
          </div></div>
          <div class="col-md-4"><div class="info-tile">
            <h6><i class="fa-solid fa-seedling me-2 text-ylcd-green"></i><?= e(__('Agir')) ?></h6>
            <p class="mb-0 small"><?= e(__('De manière solidaire dans la santé, l’humanitaire, l’environnement et la justice sociale.')) ?></p>
          </div></div>
        </div>
      </div>
      <div class="col-lg-5 text-center">
        <img src="<?= asset('img/logo-ylcd.jpg') ?>" alt="Logo YLCD-ASBL" style="max-width: 360px" class="mission-logo" />
      </div>
    </div>
    <div class="row g-4 mt-4">
      <div class="col-md-6">
        <div class="ylcd-card p-4">
          <div class="icon-circle"><i class="fa-solid fa-eye"></i></div>
          <h5><?= e(__('Notre vision')) ?></h5>
          <p class="mb-0"><?= e(__('Une société résiliente, instruite et autonome, où chaque jeune et chaque communauté peut réaliser son plein potentiel et contribuer à la paix et au développement de la région.')) ?></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="ylcd-card p-4">
          <div class="icon-circle"><i class="fa-solid fa-quote-left"></i></div>
          <h5><?= e(__('Notre slogan')) ?></h5>
          <p class="mb-0"><?= __('<strong>« Former, accompagner, agir pour demain ! »</strong> — trois verbes qui résument notre engagement quotidien sur le terrain, aux côtés des communautés.') ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!------------------ CHIFFRES -------------------->
<section class="bg-cover" id="milestone">
  <div class="container py-4">
    <div class="row gy-4 text-center justify-content-center">
      <div class="col-lg-3 col-sm-6"><div class="display-4"><?= (int) config('site.annee_creation') ?></div><p class="mb-0"><?= e(__('Année de nos premières actions')) ?></p></div>
      <div class="col-lg-3 col-sm-6"><div class="display-4"><?= (int) config('site.nombre_membres') ?></div><p class="mb-0"><?= e(__('Membres engagés')) ?></p></div>
      <div class="col-lg-3 col-sm-6"><div class="display-4"><?= count($services) ?></div><p class="mb-0"><?= e(__("Domaines d'action")) ?></p></div>
      <div class="col-lg-3 col-sm-6"><div class="display-4"><?= (int) $nbActivites ?>+</div><p class="mb-0"><?= e(__('Activités réalisées')) ?></p></div>
    </div>
  </div>
</section>

<!------------------ DOMAINES -------------------->
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5"><h1 class="title1"><?= e(__("NOS DOMAINES D'ACTION")) ?></h1></div>
    <div class="row g-4">
      <?php foreach ($services as $s): ?>
      <div class="col-md-6 col-lg-3">
        <a href="<?= url('services#' . $s['slug']) ?>" class="d-block ylcd-card">
          <img src="<?= e(media_url($s['image'])) ?>" class="card-cover" alt="<?= e(tr($s, 'titre')) ?>" />
          <div class="p-3">
            <h6 class="mb-1"><?= e(tr($s, 'titre')) ?></h6>
            <p class="small mb-0"><?= e(excerpt(tr($s, 'resume'), 110)) ?></p>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!------------------ VALEURS -------------------->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h1 class="title1"><?= e(__('NOS VALEURS')) ?></h1>
      <p class="mt-2"><?= e(__('Les principes qui guident chacune de nos interventions.')) ?></p>
    </div>
    <div class="row g-4">
      <?php foreach ($valeurs as [$icon, $titre, $texte]): ?>
      <div class="col-md-6 col-lg-3">
        <div class="ylcd-card p-4">
          <div class="icon-circle"><i class="fa-solid <?= $icon ?>"></i></div>
          <h5><?= e(__($titre)) ?></h5>
          <p class="mb-0"><?= e(__($texte)) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
$ctaTitle = __("Envie de rejoindre l'aventure YLCD-ASBL ?");
$ctaText = __("Bénévole, partenaire ou bénéficiaire : ensemble, construisons l'avenir de nos communautés.");
require ROOT . '/app/Views/partials/cta.php';
