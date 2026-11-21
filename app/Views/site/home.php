<?php
/**
 * Accueil. Variables : $services, $activites (6 dernières activités datées), $nbActivites, $projets, $membres.
 */
$slides = ['slide1', 'slide2', 'slide3'];
$carousel = [
    'leadership-2.jpg'         => 'Formation en leadership',
    'environnement-2023-2.jpg' => 'Reboisement avec les jeunes',
    'informatique-2026-1.jpg'  => 'Formation en informatique',
];
?>
<!------------------1. SLIDER -------------------->
<div class="slider-wrapper owl-carousel owl-theme" id="hero-slider">
  <?php foreach ($slides as $slide): ?>
  <div class="<?= $slide ?> slide d-flex align-items-center">
    <div>
      <div class="bg-cover slide-text">
        <div class="container">
          <div class="row gy-4 text-center justify-content-center">
            <div class="col-12 s-small">
              <div class="line"></div>
              <h3 class="display-5 my-2 text-white text-uppercase title">YOUTH LEAGUE FOR COMMUNITY DEVELOPMENT</h3>
              <h4 class="text-white">"<?= e(__('Former, accompagner, agir pour demain !')) ?>"</h4>
            </div>
          </div>
        </div>
      </div>
      <div><img src="<?= asset('img/logo-ylcd.jpg') ?>" class="logo logo-slide" alt="Logo YLCD-ASBL" /></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!------------------2. A PROPOS -------------------->
<section id="about">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-lg-7 position-relative vertical-line">
        <h1 class="title1"><?= e(__('QUI SOMMES NOUS?')) ?></h1>
        <p><?= e(__('YLCD-ASBL (Youth League for Community Development) est une association sans but lucratif fondée dans le but de promouvoir le développement humain durable à travers des actions concrètes, inclusives et accessibles à tous. Engagée depuis sa création dans le renforcement des capacités, notre organisation place la personne humaine au cœur de son intervention, avec l’ambition de construire un avenir plus équitable, plus instruit et plus résilient.')) ?></p>
        <p><?= e(__('Nous croyons fermement que l’autonomisation des individus passe avant tout par l’éducation, l’apprentissage de compétences pratiques et l’accompagnement personnalisé. C’est pourquoi nous mettons en œuvre des programmes de formation en langues, en métiers, et en compétences de vie, adaptés aux besoins des communautés locales. En parallèle, nous proposons des services d’audit, de consultance et d’appui technique pour les institutions ou initiatives cherchant à renforcer leur impact et leur gouvernance.')) ?></p>
        <p><?= e(__('YLCD-ASBL, c’est aussi une voix active dans la santé communautaire, l’action humanitaire, la protection de l’environnement et la lutte contre les effets du changement climatique. Ces enjeux globaux exigent des réponses locales efficaces, que nous construisons avec et pour les communautés.')) ?></p>
        <a href="<?= url('a-propos') ?>" class="btn btn-brand"><?= e(__('En savoir plus')) ?></a>
      </div>
      <div class="col-lg-4">
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php $first = true; foreach ($carousel as $img => $alt): ?>
            <div class="carousel-item <?= $first ? 'active' : '' ?>">
              <img src="<?= asset('img/' . $img) ?>" class="d-block w-100 imgSlide2" alt="<?= e(__($alt)) ?>" />
            </div>
            <?php $first = false; endforeach; ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden"><?= e(__('Précédent')) ?></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden"><?= e(__('Suivant')) ?></span>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<!------------------3. MISSION -------------------->
<section id="mission">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-lg-6">
        <h1 class="title1"><?= e(__('NOTRE MISSION')) ?></h1>
        <h4><?= e(__('Notre mission est claire : former, accompagner et agir pour demain.')) ?></h4>
        <p><?= e(__('À travers cette devise, nous traduisons notre engagement à :')) ?></p>
        <div class="row mission">
          <ul>
            <li><?= __('<strong>Former</strong> les jeunes et les adultes aux compétences linguistiques, techniques et professionnelles nécessaires à leur épanouissement personnel et à leur insertion dans la vie active ;') ?></li>
            <li><?= __('<strong>Accompagner</strong> les individus, les groupes et les institutions par un appui sur mesure, basé sur l’écoute, l’analyse et des solutions concrètes ;') ?></li>
            <li><?= __('<strong>Agir</strong> de manière solidaire et proactive dans les domaines de la santé, du développement durable, et de la justice sociale.') ?></li>
          </ul>
          <p><?= e(__('Nous voulons être un catalyseur de changement positif, en servant de pont entre les aspirations des communautés et les opportunités d’un monde en constante évolution.')) ?></p>
        </div>
      </div>
      <div class="col-lg-6">
        <img src="<?= asset('img/logo-ylcd.jpg') ?>" alt="Logo YLCD-ASBL" style="max-width: 420px" class="d-block mx-auto mission-logo" />
      </div>
    </div>
  </div>
</section>

<!------------------ CHIFFRES CLES -------------------->
<section class="bg-cover" id="milestone">
  <div class="container">
    <div class="row gy-4 text-center justify-content-center">
      <div class="col-lg-3 col-sm-6"><div class="display-4"><?= (int) config('site.annee_creation') ?></div><p class="mb-0"><?= e(__('Année de nos premières actions')) ?></p></div>
      <div class="col-lg-3 col-sm-6"><div class="display-4"><?= (int) config('site.nombre_membres') ?></div><p class="mb-0"><?= e(__('Membres engagés')) ?></p></div>
      <div class="col-lg-3 col-sm-6"><div class="display-4"><?= count($services) ?></div><p class="mb-0"><?= e(__("Domaines d'action")) ?></p></div>
      <div class="col-lg-3 col-sm-6"><div class="display-4"><?= (int) $nbActivites ?>+</div><p class="mb-0"><?= e(__('Activités réalisées')) ?></p></div>
    </div>
  </div>
</section>

<!------------------4. SERVICES -------------------->
<section id="service">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="title1"><?= e(__('NOS SERVICES')) ?></h1>
        <p><?= e(__('Chez YLCD-ASBL, nous mettons notre expertise au service des communautés pour répondre à leurs besoins réels et actuels.')) ?></p>
        <p><?= e(__('Nos interventions combinent formation, accompagnement personnalisé, actions de terrain et sensibilisation, afin d’offrir des solutions durables et adaptées aux réalités locales.')) ?></p>
      </div>
    </div>
    <div class="row gy-4">
      <?php foreach ($services as $s): ?>
      <div class="col-md-6 col-lg-3">
        <img src="<?= e(media_url($s['image'])) ?>" class="home-img" alt="<?= e(tr($s, 'titre')) ?>" />
        <h6><?= e(tr($s, 'titre')) ?></h6>
        <?php if (tr($s, 'slogan')): ?><strong><?= e(tr($s, 'slogan')) ?></strong><?php endif; ?>
        <p><?= e(tr($s, 'resume')) ?></p>
        <a href="<?= url('services#' . $s['slug']) ?>" class="voir-plus"><?= e(__('Voir plus')) ?></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!------------------5. ACTIVITES RECENTES -------------------->
<section id="projects" class="bg-light">
  <div class="container">
    <h1 class="title1"><?= e(__('NOS ACTIVITES RECENTES')) ?></h1>
    <p><?= e(__("De la salle de classe au terrain, voici un aperçu de nos dernières actions : formations, encadrement des jeunes, assistance aux personnes vulnérables et protection de l'environnement.")) ?></p>
  </div>
  <?php if ($activites): ?>
  <div id="project-slider" class="owl-carousel owl-theme">
    <?php foreach ($activites as $a):
        $media = $a['medias'][0] ?? null; ?>
    <div class="project">
      <?php if ($media && $media['type'] === 'video'): ?>
        <video src="<?= e(media_url($media['chemin'])) ?>" muted loop playsinline class="w-100" style="height:400px;object-fit:cover"></video>
      <?php else: ?>
        <img src="<?= e(media_url($media['chemin'] ?? null)) ?>" alt="<?= e(tr($a, 'titre')) ?>" />
      <?php endif; ?>
      <div class="overlay"></div>
      <div class="content">
        <h1><?= e(tr($a, 'titre')) ?></h1>
        <?php if (tr($a, 'sous_titre')): ?><p class="text-white mb-1"><?= e(tr($a, 'sous_titre')) ?></p><?php endif; ?>
        <p class="text-white"><i class="fa-regular fa-calendar me-1"></i> <?= e(date_activite($a['date_activite'])) ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <div class="container text-center mt-4">
    <a href="<?= url('activites') ?>" class="btn btn-brand"><?= e(__('Voir toutes nos activités')) ?></a>
  </div>
</section>

<!-----------------6. PROJETS  --------------------->
<section class="blog" id="projets">
  <div class="container">
    <h1 class="title1"><?= e(__('NOS PROJETS')) ?></h1>
    <div class="row g-4">
      <?php foreach ($projets as $p): ?>
      <div class="col-md-6 col-lg-3">
        <div class="blog-post">
          <?php if (tr($p, 'categorie')): ?>
          <a href="<?= url('services' . ($p['service'] ? '#' . $p['service']['slug'] : '')) ?>" class="tag"><?= e(tr($p, 'categorie')) ?></a>
          <?php endif; ?>
          <img src="<?= e(media_url($p['image'])) ?>" alt="<?= e(tr($p, 'titre')) ?>" />
          <h4><?= e(tr($p, 'titre')) ?></h4>
          <p>
            <?= e(tr($p, 'description')) ?>
            <?php if (tr($p, 'objectif')): ?><br><strong><?= e(__('Objectif :')) ?></strong> <?= e(tr($p, 'objectif')) ?><?php endif; ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!------------------7. EQUIPE ------------------->
<section class="bg-cover" id="equipe">
  <div class="container">
    <h1 class="title1"><?= e(__('NOTRE EQUIPE')) ?></h1>
    <br /><br />
    <div class="row g-4 justify-content-center">
      <?php foreach ($membres as $m): ?>
      <div class="review col-6 col-md-3 col-lg">
        <img src="<?= e(media_url($m['photo'])) ?>" alt="<?= e($m['nom']) ?>" />
        <h6><?= e($m['nom']) ?></h6>
        <p class="lead"><?= e(tr($m, 'fonction')) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="<?= url('equipe') ?>" class="btn btn-brand"><?= e(__("Découvrir toute l'équipe")) ?></a>
    </div>
  </div>
</section>

<!-----------------8. CONTACT  --------------------->
<?php require ROOT . '/app/Views/partials/contact_form.php'; ?>
