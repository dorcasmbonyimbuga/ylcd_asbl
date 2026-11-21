<?php /** Formulaire de contact (enregistré en base + envoyé par email à l'association). */ ?>
<section id="contact" class="contact">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h1 class="title1"><?= e(__('CONTACTEZ-NOUS')) ?></h1>
        <img src="<?= asset('img/certificats-1.jpg') ?>" alt="<?= e(__('Les membres et lauréats de YLCD-ASBL')) ?>" class="rounded-3 mt-3" />
      </div>
      <div class="col-lg-6">
        <form action="<?= url('contact') ?>" method="post" class="row p-lg-5 gy-3" novalidate>
          <?= csrf_field() ?>
          <!-- Champ piège anti-robots (invisible) -->
          <div class="d-none" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>

          <div class="col-12">
            <h5><?= e(__('Engagez-vous avec nous et devenez acteur du changement positif.')) ?></h5>
            <h6><?= e(__('Notre équipe reste à votre disposition en cas de questions concernant nos formations, un accompagnement ou simplement une question.')) ?></h6>
          </div>
          <div class="col-lg-6">
            <label for="contact-nom" class="form-label"><?= e(__('Nom')) ?> *</label>
            <input type="text" name="nom" id="contact-nom" class="form-control<?= invalid('nom') ?>" placeholder="<?= e(__('Votre nom')) ?>" value="<?= e(old('nom')) ?>" required />
            <?= error_feedback('nom') ?>
          </div>
          <div class="col-lg-6">
            <label for="contact-email" class="form-label"><?= e(__('Email')) ?> *</label>
            <input type="email" name="email" id="contact-email" class="form-control<?= invalid('email') ?>" placeholder="<?= e(__('Ex : exemple@gmail.com')) ?>" value="<?= e(old('email')) ?>" required />
            <?= error_feedback('email') ?>
          </div>
          <div class="col-lg-6">
            <label for="contact-tel" class="form-label"><?= e(__('Téléphone')) ?></label>
            <input type="tel" name="telephone" id="contact-tel" class="form-control<?= invalid('telephone') ?>" placeholder="+243 ..." value="<?= e(old('telephone')) ?>" />
            <?= error_feedback('telephone') ?>
          </div>
          <div class="col-lg-6">
            <label for="contact-sujet" class="form-label"><?= e(__('Sujet')) ?></label>
            <input type="text" name="sujet" id="contact-sujet" class="form-control<?= invalid('sujet') ?>" placeholder="<?= e(__('Formation, partenariat...')) ?>" value="<?= e(old('sujet')) ?>" />
            <?= error_feedback('sujet') ?>
          </div>
          <div class="col-12">
            <label for="contact-message" class="form-label"><?= e(__('Message')) ?> *</label>
            <textarea name="message" id="contact-message" rows="4" class="form-control<?= invalid('message') ?>"
              placeholder="<?= e(__('Ecrivez-nous ici le sujet dont vous aimeriez discuter, ou simplement une question à poser')) ?>" required><?= e(old('message')) ?></textarea>
            <?= error_feedback('message') ?>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-brand"><i class="fa-solid fa-paper-plane me-1"></i> <?= e(__('Envoyer')) ?></button>
            <button type="reset" class="btn btn-secondary"><?= e(__('Annuler')) ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
