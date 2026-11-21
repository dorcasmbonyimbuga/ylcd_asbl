-- =====================================================================
--  YLCD-ASBL — Base de données bilingue FR / EN (schéma + données initiales)
--  Import : phpMyAdmin > Importer > ce fichier  (ou : mysql -u root < database/ylcd_asbl.sql)
--  Le script peut être relancé : il ne supprime rien et n'insère pas de doublons.
--  Base déjà importée avec l'ancienne version (colonnes sans _fr/_en) ?
--  => importez plutôt database/migrations/2026_11_21_bilingue.sql
--  Convention : chaque champ texte affiché sur le site existe en deux colonnes
--  <champ>_fr et <champ>_en ; les images n'ont qu'une seule colonne.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS ylcd_asbl
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ylcd_asbl;
SET NAMES utf8mb4;

-- Comptes de connexion. "Confirmation du mot de passe" n'est pas stockée :
-- elle sert seulement à vérifier la saisie dans les formulaires.
CREATE TABLE IF NOT EXISTS users (
  id                      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username                VARCHAR(50)  NOT NULL UNIQUE,
  email                   VARCHAR(150) NOT NULL UNIQUE,
  photo                   VARCHAR(255) NULL,
  password                VARCHAR(255) NOT NULL COMMENT 'hash password_hash()',
  role                    ENUM('admin','membre') NOT NULL DEFAULT 'membre',
  must_change_credentials TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '1 = doit changer ses identifiants à la prochaine connexion',
  last_login_at           DATETIME     NULL,
  created_at              DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at              DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS services (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre_fr       VARCHAR(150) NOT NULL,
  titre_en       VARCHAR(150) NOT NULL DEFAULT '',
  slug           VARCHAR(80)  NOT NULL UNIQUE COMMENT 'ancre dans la page Services (ex: langues)',
  pole_fr        VARCHAR(120) NULL,
  pole_en        VARCHAR(120) NULL,
  slogan_fr      VARCHAR(255) NULL,
  slogan_en      VARCHAR(255) NULL,
  resume_fr      VARCHAR(500) NULL COMMENT 'texte court (accueil, à propos)',
  resume_en      VARCHAR(500) NULL,
  description_fr TEXT         NULL,
  description_en TEXT         NULL,
  points_fr      TEXT         NULL COMMENT 'une ligne = un point de la liste',
  points_en      TEXT         NULL,
  image          VARCHAR(255) NULL COMMENT 'chemin dans uploads/services/',
  ordre          INT          NOT NULL DEFAULT 0,
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS activites (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre_fr       VARCHAR(180) NOT NULL,
  titre_en       VARCHAR(180) NOT NULL DEFAULT '',
  sous_titre_fr  VARCHAR(255) NULL,
  sous_titre_en  VARCHAR(255) NULL,
  categorie      ENUM('formation','ceremonie','social','environnement') NOT NULL DEFAULT 'formation',
  description_fr TEXT         NULL,
  description_en TEXT         NULL,
  date_activite  DATE         NULL COMMENT 'NULL = activité régulière ; le 1er du mois = affichage "Mois Année"',
  est_publie     TINYINT(1)   NOT NULL DEFAULT 1,
  created_by     INT UNSIGNED NULL,
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_activites_date (date_activite),
  CONSTRAINT fk_activites_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS activite_medias (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  activite_id INT UNSIGNED NOT NULL,
  chemin      VARCHAR(255) NOT NULL COMMENT 'chemin dans uploads/activites/',
  type        ENUM('image','video') NOT NULL DEFAULT 'image',
  ordre       INT          NOT NULL DEFAULT 0 COMMENT 'le premier média est l''image principale',
  created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_medias_activite FOREIGN KEY (activite_id) REFERENCES activites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS projets (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre_fr       VARCHAR(180) NOT NULL,
  titre_en       VARCHAR(180) NOT NULL DEFAULT '',
  categorie_fr   VARCHAR(80)  NULL COMMENT 'étiquette affichée sur la carte',
  categorie_en   VARCHAR(80)  NULL,
  description_fr TEXT         NULL,
  description_en TEXT         NULL,
  objectif_fr    TEXT         NULL,
  objectif_en    TEXT         NULL,
  image          VARCHAR(255) NULL COMMENT 'chemin dans uploads/projets/',
  service_id     INT UNSIGNED NULL,
  ordre          INT          NOT NULL DEFAULT 0,
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_projets_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS membres (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom              VARCHAR(120) NOT NULL COMMENT 'nom propre : identique dans les deux langues',
  fonction_fr      VARCHAR(120) NULL,
  fonction_en      VARCHAR(120) NULL,
  bio_fr           TEXT         NULL,
  bio_en           TEXT         NULL,
  photo            VARCHAR(255) NULL COMMENT 'chemin dans uploads/membres/',
  email            VARCHAR(150) NULL,
  linkedin         VARCHAR(255) NULL,
  est_coordinateur TINYINT(1)   NOT NULL DEFAULT 0,
  ordre            INT          NOT NULL DEFAULT 0,
  created_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Messages du formulaire de contact (saisis par les visiteurs : pas de traduction)
CREATE TABLE IF NOT EXISTS contacts (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom          VARCHAR(120) NOT NULL,
  email        VARCHAR(150) NOT NULL,
  telephone    VARCHAR(40)  NULL,
  sujet        VARCHAR(180) NULL,
  message      TEXT         NOT NULL,
  est_lu       TINYINT(1)   NOT NULL DEFAULT 0,
  email_envoye TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '1 = copie envoyée à l''adresse de l''association',
  ip           VARCHAR(45)  NULL,
  created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
--  DONNÉES INITIALES
-- =====================================================================

-- Admin par défaut : admin / admin (changement obligatoire à la 1re connexion)
INSERT IGNORE INTO users (id, username, email, password, role, must_change_credentials) VALUES
(1, 'admin', 'admin@test.com', '$2y$10$iftRDjEYeiteVgkbWyjsfu3AaARleZVb8vpy/.NoJ1klHHTG9xSWG', 'admin', 1);

INSERT IGNORE INTO services (id, slug, titre_fr, titre_en, pole_fr, pole_en, slogan_fr, slogan_en, resume_fr, resume_en, description_fr, description_en, points_fr, points_en, image, ordre) VALUES
(1, 'langues', 'Formation en langues et métiers', 'Language and vocational training', 'PÔLE 01 — ÉDUCATION & EMPLOYABILITÉ', 'HUB 01 — EDUCATION & EMPLOYABILITY', '💡 Apprenez, progressez, réussissez.', '💡 Learn, grow, succeed.', 'Maîtrisez une nouvelle langue ou développez une compétence professionnelle qui ouvre des portes. Nos formations sont pratiques, interactives et adaptées à votre rythme.', 'Master a new language or develop a professional skill that opens doors. Our training courses are practical, interactive and adapted to your pace.', 'Nos formations sont pratiques, interactives et adaptées au rythme de chacun, pour préparer les jeunes et les adultes à saisir les opportunités d’aujourd’hui et de demain.', 'Our training courses are practical, interactive and adapted to each learner’s pace, preparing young people and adults to seize today’s and tomorrow’s opportunities.', 'Anglais : cours par niveaux, présentations publiques et remise de brevets / certificats en fin de cycle.\nInformatique : initiation à l’ordinateur, bureautique et outils numériques.\nIntelligence artificielle : ateliers pour comprendre et utiliser l’IA de manière responsable.\nEntrepreneuriat & leadership : autonomisation économique, gestion de projets et prise de parole.', 'English: level-based classes, public presentations and diplomas / certificates at the end of each cycle.\nComputer skills: introduction to computers, office software and digital tools.\nArtificial intelligence: workshops to understand and use AI responsibly.\nEntrepreneurship & leadership: economic empowerment, project management and public speaking.', 'uploads/services/service-langues.jpg', 1),
(2, 'consultance', 'Accompagnement technique, audit & consultance', 'Technical support, audit & consulting', 'PÔLE 02 — APPUI AUX ORGANISATIONS', 'HUB 02 — SUPPORT FOR ORGANISATIONS', '📈 Faites passer vos projets au niveau supérieur.', '📈 Take your projects to the next level.', 'Que vous soyez une organisation, une entreprise ou une initiative locale, nous vous accompagnons avec des solutions sur mesure pour optimiser votre gestion et maximiser votre impact.', 'Whether you are an organisation, a business or a local initiative, we support you with tailored solutions to optimise your management and maximise your impact.', 'Que vous soyez une organisation, une entreprise ou une initiative locale, nous vous accompagnons avec des solutions sur mesure pour optimiser votre gestion, améliorer vos performances et maximiser votre impact.', 'Whether you are an organisation, a business or a local initiative, we support you with tailored solutions to optimise your management, improve your performance and maximise your impact.', 'Rédaction de projets et appui à la recherche de financements.\nAudit organisationnel, suivi et évaluation des projets.\nRenforcement des capacités des équipes et des leaders communautaires.', 'Project writing and support in finding funding.\nOrganisational audits, project monitoring and evaluation.\nCapacity building for teams and community leaders.', 'uploads/services/service-consultance.jpg', 2),
(3, 'sante', 'Santé communautaire & action humanitaire', 'Community health & humanitarian action', 'PÔLE 03 — PROTECTION & BIEN-ÊTRE', 'HUB 03 — PROTECTION & WELL-BEING', '❤️ Agir pour la vie, agir pour tous.', '❤️ Acting for life, acting for all.', 'Nous travaillons aux côtés des communautés pour promouvoir la prévention et le bien-être. En cas de crise, nous apportons aide et espoir aux plus vulnérables.', 'We work alongside communities to promote prevention and well-being. In times of crisis, we bring help and hope to the most vulnerable.', 'Nous travaillons aux côtés des communautés pour promouvoir la prévention et le bien-être, et pour apporter aide et espoir aux plus vulnérables en période de crise.', 'We work alongside communities to promote prevention and well-being, and to bring help and hope to the most vulnerable in times of crisis.', 'Assistance alimentaire aux victimes des guerres et aux familles déplacées.\nDiscussions et sensibilisation sur la lutte contre les violences basées sur le genre (VBG).\nEncadrement des enfants et des adolescents pendant les vacances.', 'Food assistance for war victims and displaced families.\nDiscussions and awareness-raising on the fight against gender-based violence (GBV).\nSupervision and activities for children and teenagers during the holidays.', 'uploads/services/service-sante.jpg', 3),
(4, 'environnement', 'Sensibilisation environnementale & lutte contre le changement climatique', 'Environmental awareness & climate action', 'PÔLE 04 — ÉCOLOGIE & CLIMAT', 'HUB 04 — ECOLOGY & CLIMATE', '🌍 Protégeons notre planète, aujourd’hui et pour demain.', '🌍 Protecting our planet, today and for tomorrow.', 'À travers des campagnes, ateliers et actions concrètes, nous sensibilisons et mobilisons pour préserver nos ressources et construire un futur plus vert.', 'Through campaigns, workshops and concrete actions, we raise awareness and mobilise people to preserve our resources and build a greener future.', 'À travers des campagnes, des ateliers et des actions concrètes, nous mobilisons les jeunes pour préserver nos ressources et construire un futur plus vert.', 'Through campaigns, workshops and concrete actions, we mobilise young people to preserve our resources and build a greener future.', 'Reboisement communautaire et plantation d’arbres avec les jeunes.\nCréation et entretien de pépinières locales.\nÉducation écologique dans les écoles et les quartiers.', 'Community reforestation and tree planting with young people.\nCreation and maintenance of local tree nurseries.\nEnvironmental education in schools and neighbourhoods.', 'uploads/services/service-environnement.jpg', 4);
-- Les \n sont des retours à la ligne (une ligne = un point)

INSERT IGNORE INTO activites (id, categorie, date_activite, titre_fr, titre_en, sous_titre_fr, sous_titre_en, description_fr, description_en, created_by) VALUES
(1, 'social', '2026-07-01', 'Encadrement des enfants de moins de 12 ans', 'Supervision of children under 12', 'Accompagnement éducatif des enfants de moins de 12 ans', 'Educational support for children under 12', 'Pendant les vacances, nous avons accueilli des enfants de moins de 12 ans pour des séances d’encadrement : lecture, écriture, jeux éducatifs et apprentissage des valeurs de respect et de vivre-ensemble. Une manière de les occuper utilement et de soutenir les parents.', 'During the holidays, we welcomed children under 12 for supervised sessions: reading, writing, educational games and learning the values of respect and living together. A way to keep them usefully busy and to support their parents.', 1),
(2, 'social', '2026-07-01', 'Encadrement des adolescents', 'Supervision of teenagers', 'Orientation, valeurs et préparation à l’avenir', 'Guidance, values and preparing for the future', 'Des sessions d’échange et d’orientation avec les adolescents sur la discipline, l’estime de soi, l’éducation et les choix de vie. Nos encadreurs les accompagnent pour qu’ils deviennent des jeunes responsables et engagés dans leur communauté.', 'Discussion and guidance sessions with teenagers on discipline, self-esteem, education and life choices. Our mentors support them so that they become responsible young people, engaged in their community.', 1),
(3, 'formation', '2026-04-01', 'Formation en informatique', 'Computer training', 'Initiation aux outils numériques et bureautiques', 'Introduction to digital and office tools', 'Une formation pratique pour initier les participants à l’utilisation de l’ordinateur et des outils bureautiques. Chaque apprenant a pu manipuler, s’exercer et repartir avec des compétences numériques directement utiles pour les études et l’emploi.', 'A hands-on course introducing participants to computers and office software. Each learner was able to practise and leave with digital skills that are directly useful for their studies and employment.', 1),
(4, 'formation', '2026-03-01', 'Ateliers sur l’utilisation de l’intelligence artificielle', 'Workshops on using artificial intelligence', 'Découvrir et utiliser l’IA de façon responsable', 'Discovering and using AI responsibly', 'Des ateliers pour découvrir ce qu’est l’intelligence artificielle, comment l’utiliser pour apprendre, rédiger et travailler plus efficacement, et comment l’utiliser de manière responsable et éthique.', 'Workshops to discover what artificial intelligence is, how to use it to learn, write and work more efficiently, and how to use it responsibly and ethically.', 1),
(5, 'ceremonie', '2026-03-01', 'Remise des brevets après la formation d’anglais', 'Diploma ceremony after the English course', 'Fin de formation et célébration des lauréats', 'End of training and celebration of the graduates', 'Un moment de fierté : les apprenants ayant terminé avec succès leur cycle de formation en anglais ont reçu leurs brevets en présence des formateurs, des membres de YLCD-ASBL et de leurs proches.', 'A proud moment: learners who successfully completed their English training cycle received their diplomas in the presence of the trainers, YLCD-ASBL members and their families.', 1),
(6, 'ceremonie', '2025-11-01', 'Remise de certificats après la formation d’anglais', 'Certificate ceremony after the English course', 'Clôture d’une session de formation en anglais', 'Closing of an English training session', 'Clôture d’une session de formation en anglais avec la remise des certificats aux participants, récompensant leur assiduité et leurs progrès tout au long de la formation.', 'Closing of an English training session with certificates awarded to the participants, rewarding their commitment and progress throughout the course.', 1),
(7, 'social', '2024-07-04', 'Assistance aux victimes des guerres', 'Assistance to war victims', 'Soutien aux familles et enfants vulnérables', 'Support for vulnerable families and children', 'Le 4 juillet 2024, YLCD-ASBL est allée à la rencontre des familles et des enfants vulnérables touchés par les conflits armés. Des repas ont été partagés et une assistance a été apportée pour soulager, ne serait-ce qu’un moment, leur quotidien.', 'On 4 July 2024, YLCD-ASBL went to meet vulnerable families and children affected by armed conflict. Meals were shared and assistance was provided to ease their daily lives, if only for a moment.', 1),
(8, 'environnement', '2023-02-01', 'Protection de l’environnement et reboisement', 'Environmental protection and reforestation', 'Reboisement et pépinières communautaires', 'Reforestation and community tree nurseries', 'Avec les jeunes et les membres de la communauté, nous avons préparé des pépinières, mis en terre et arrosé de jeunes plants. Cette campagne de reboisement vise à lutter contre la déforestation et à sensibiliser chacun à la protection de l’environnement.', 'Together with young people and community members, we prepared tree nurseries, planted and watered young seedlings. This reforestation campaign aims to fight deforestation and raise everyone’s awareness of environmental protection.', 1),
(9, 'formation', '2021-06-01', 'Formation en entrepreneuriat et autonomisation économique', 'Entrepreneurship and economic empowerment training', 'Accompagner les jeunes et les femmes entrepreneurs', 'Supporting young and women entrepreneurs', 'L’une de nos premières activités : une formation destinée aux jeunes et aux femmes entrepreneurs pour les aider à développer une idée d’entreprise, gérer une activité génératrice de revenus et gagner en autonomie économique.', 'One of our very first activities: a training course for young people and women entrepreneurs to help them develop a business idea, run an income-generating activity and gain economic independence.', 1),
(10, 'formation', NULL, 'Formation d’anglais', 'English classes', 'Cours réguliers par niveaux', 'Regular level-based classes', 'Des cours d’anglais réguliers, organisés par niveaux, pour améliorer la communication, l’employabilité et l’accès aux opportunités internationales.', 'Regular English classes, organised by level, to improve communication, employability and access to international opportunities.', 1),
(11, 'formation', NULL, 'Présentation publique d’anglais', 'English public presentation', 'Prendre la parole en anglais', 'Speaking English in public', 'Les apprenants prennent la parole en public en anglais : un exercice pour gagner en confiance et mettre en pratique ce qu’ils ont appris.', 'Learners speak in public in English: an exercise to build confidence and put into practice what they have learned.', 1),
(12, 'social', NULL, 'Discussion sur la lutte contre les VBG', 'Discussion on fighting GBV', 'Violences basées sur le genre', 'Gender-based violence', 'Un espace d’échange sur la lutte contre les violences basées sur le genre : comprendre, prévenir, orienter les victimes et briser le silence.', 'A space for dialogue on the fight against gender-based violence: understanding, preventing, referring victims and breaking the silence.', 1),
(13, 'formation', NULL, 'Formation en leadership', 'Leadership training', 'Devenir acteur du changement', 'Becoming an agent of change', 'Des sessions pour développer le sens des responsabilités, la prise de parole, le travail en équipe et la capacité à conduire le changement dans sa communauté.', 'Sessions to develop a sense of responsibility, public speaking, teamwork and the ability to lead change in one’s community.', 1),
(14, 'ceremonie', NULL, 'Remise des certificats', 'Certificate ceremony', 'Célébrer la fin des formations', 'Celebrating the end of each training course', 'À la fin de chaque formation, nous célébrons les participants qui ont mené leur parcours à terme avec la remise officielle de leurs certificats.', 'At the end of each training course, we celebrate the participants who completed their programme with the official presentation of their certificates.', 1);

INSERT IGNORE INTO activite_medias (id, activite_id, chemin, type, ordre) VALUES
(1, 1, 'uploads/activites/encadrement-enfants-2026.jpg', 'image', 1),
(2, 2, 'uploads/activites/encadrement-adolescents-2026.jpg', 'image', 1),
(3, 3, 'uploads/activites/informatique-2026-1.jpg', 'image', 1),
(4, 3, 'uploads/activites/informatique-2026-2.jpg', 'image', 2),
(5, 4, 'uploads/activites/ia-2026-1.jpg', 'image', 1),
(6, 4, 'uploads/activites/ia-2026-2.jpg', 'image', 2),
(7, 5, 'uploads/activites/brevets-anglais-2026-1.jpg', 'image', 1),
(8, 5, 'uploads/activites/brevets-anglais-2026-2.jpg', 'image', 2),
(9, 5, 'uploads/activites/brevets-anglais-2026-3.jpg', 'image', 3),
(10, 6, 'uploads/activites/certificats-anglais-2025.jpg', 'image', 1),
(11, 7, 'uploads/activites/assistance-2024-1.jpg', 'image', 1),
(12, 7, 'uploads/activites/assistance-2024-2.jpg', 'image', 2),
(13, 7, 'uploads/activites/assistance-2024-3.jpg', 'image', 3),
(14, 8, 'uploads/activites/environnement-2023-1.jpg', 'image', 1),
(15, 8, 'uploads/activites/environnement-2023-2.jpg', 'image', 2),
(16, 8, 'uploads/activites/environnement-2023-3.jpg', 'image', 3),
(17, 8, 'uploads/activites/environnement-2023-4.jpg', 'image', 4),
(18, 8, 'uploads/activites/environnement-2023-5.jpg', 'image', 5),
(19, 8, 'uploads/activites/environnement-2023-6.jpg', 'image', 6),
(20, 9, 'uploads/activites/entrepreneuriat-2021-1.jpg', 'image', 1),
(21, 9, 'uploads/activites/entrepreneuriat-2021-2.jpg', 'image', 2),
(22, 10, 'uploads/activites/anglais-1.jpg', 'image', 1),
(23, 10, 'uploads/activites/anglais-2.jpg', 'image', 2),
(24, 11, 'uploads/activites/presentation-anglais.jpg', 'image', 1),
(25, 12, 'uploads/activites/vbg-1.jpg', 'image', 1),
(26, 12, 'uploads/activites/vbg-2.jpg', 'image', 2),
(27, 12, 'uploads/activites/vbg-3.jpg', 'image', 3),
(28, 12, 'uploads/activites/vbg-4.jpg', 'image', 4),
(29, 13, 'uploads/activites/leadership-2.jpg', 'image', 1),
(30, 13, 'uploads/activites/leadership-1.jpg', 'image', 2),
(31, 13, 'uploads/activites/leadership-3.jpg', 'image', 3),
(32, 14, 'uploads/activites/certificats-1.jpg', 'image', 1),
(33, 14, 'uploads/activites/certificats-2.jpg', 'image', 2);

INSERT IGNORE INTO projets (id, service_id, ordre, image, titre_fr, titre_en, categorie_fr, categorie_en, description_fr, description_en, objectif_fr, objectif_en) VALUES
(1, 1, 1, 'uploads/projets/projet-1.jpg', 'Langues pour l’avenir', 'Languages for the future', 'Formation', 'Training', 'Un programme de formation linguistique ouvert aux jeunes, adultes et professionnels, visant à renforcer leurs compétences en anglais, français, swahili et autres langues stratégiques.', 'A language training programme open to young people, adults and professionals, aimed at strengthening their skills in English, French, Swahili and other strategic languages.', 'Améliorer l’employabilité, faciliter la communication interculturelle et ouvrir l’accès aux opportunités internationales.', 'Improve employability, facilitate intercultural communication and open access to international opportunities.'),
(2, 1, 2, 'uploads/projets/projet-2.jpg', 'Compétences et métiers durables', 'Sustainable skills and trades', 'Formation', 'Training', 'Un projet de formation professionnelle dans des secteurs porteurs tels que la couture, l’informatique, la menuiserie, la coiffure, ou encore l’agroalimentaire.', 'A vocational training project in promising sectors such as sewing, computing, carpentry, hairdressing and food processing.', 'Permettre aux bénéficiaires de créer leur propre emploi, de générer des revenus et de contribuer au développement local.', 'Enable beneficiaries to create their own jobs, generate income and contribute to local development.'),
(3, 3, 3, 'uploads/projets/projet-3.jpg', 'Santé pour tous', 'Health for all', 'Santé', 'Health', 'Une initiative communautaire axée sur la prévention et la promotion de la santé : campagnes de sensibilisation, dépistages gratuits, ateliers sur l’hygiène et la nutrition.', 'A community initiative focused on health prevention and promotion: awareness campaigns, free screenings, hygiene and nutrition workshops.', 'Réduire les risques de maladies évitables et améliorer le bien-être des populations, en particulier dans les zones vulnérables.', 'Reduce the risk of preventable diseases and improve people’s well-being, especially in vulnerable areas.'),
(4, 4, 4, 'uploads/projets/projet-4.jpg', 'Un geste pour la planète', 'A gesture for the planet', 'Environnement', 'Environment', 'Un programme de sensibilisation environnementale comprenant des activités de reboisement, de gestion des déchets, et d’éducation écologique dans les écoles et communautés.', 'An environmental awareness programme including reforestation, waste management and environmental education in schools and communities.', 'Encourager l’adoption de comportements écoresponsables et contribuer à la lutte contre le changement climatique.', 'Encourage eco-friendly behaviour and contribute to the fight against climate change.');

-- Noms et fonctions à compléter depuis l'espace admin
INSERT IGNORE INTO membres (id, nom, fonction_fr, fonction_en, photo, est_coordinateur, ordre) VALUES
(1, 'Nom du coordonnateur', 'Coordination', 'Coordination', 'uploads/membres/membre-1.jpg', 1, 1),
(2, 'Nom du membre', 'Fonction', 'Role', 'uploads/membres/membre-2.jpg', 0, 2),
(3, 'Nom du membre', 'Fonction', 'Role', 'uploads/membres/membre-3.jpg', 0, 3),
(4, 'Nom du membre', 'Fonction', 'Role', 'uploads/membres/membre-4.jpg', 0, 4),
(5, 'Nom du membre', 'Fonction', 'Role', 'uploads/membres/membre-5.jpg', 0, 5),
(6, 'Nom du membre', 'Fonction', 'Role', 'uploads/membres/membre-6.jpg', 0, 6),
(7, 'Nom du membre', 'Fonction', 'Role', 'uploads/membres/membre-7.jpg', 0, 7);
