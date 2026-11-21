-- =====================================================================
--  Migration : passage au site bilingue FR / EN
--  À exécuter UNE SEULE FOIS sur une base créée avec l'ancienne version
--  (colonnes titre, description... sans suffixe _fr / _en).
--  Les textes existants deviennent la version française (aucune perte).
--  Les traductions anglaises du contenu d'origine sont ajoutées, uniquement
--  si le texte français n'a pas été modifié entre-temps depuis l'admin.
-- =====================================================================
USE ylcd_asbl;
SET NAMES utf8mb4;

ALTER TABLE services
  CHANGE titre titre_fr VARCHAR(150) NOT NULL,
  ADD COLUMN titre_en VARCHAR(150) NOT NULL DEFAULT '' AFTER titre_fr,
  CHANGE pole pole_fr VARCHAR(120) NULL,
  ADD COLUMN pole_en VARCHAR(120) NULL AFTER pole_fr,
  CHANGE slogan slogan_fr VARCHAR(255) NULL,
  ADD COLUMN slogan_en VARCHAR(255) NULL AFTER slogan_fr,
  CHANGE resume resume_fr VARCHAR(500) NULL,
  ADD COLUMN resume_en VARCHAR(500) NULL AFTER resume_fr,
  CHANGE description description_fr TEXT NULL,
  ADD COLUMN description_en TEXT NULL AFTER description_fr,
  CHANGE points points_fr TEXT NULL,
  ADD COLUMN points_en TEXT NULL AFTER points_fr;

ALTER TABLE activites
  CHANGE titre titre_fr VARCHAR(180) NOT NULL,
  ADD COLUMN titre_en VARCHAR(180) NOT NULL DEFAULT '' AFTER titre_fr,
  CHANGE sous_titre sous_titre_fr VARCHAR(255) NULL,
  ADD COLUMN sous_titre_en VARCHAR(255) NULL AFTER sous_titre_fr,
  CHANGE description description_fr TEXT NULL,
  ADD COLUMN description_en TEXT NULL AFTER description_fr;

ALTER TABLE projets
  CHANGE titre titre_fr VARCHAR(180) NOT NULL,
  ADD COLUMN titre_en VARCHAR(180) NOT NULL DEFAULT '' AFTER titre_fr,
  CHANGE categorie categorie_fr VARCHAR(80) NULL,
  ADD COLUMN categorie_en VARCHAR(80) NULL AFTER categorie_fr,
  CHANGE description description_fr TEXT NULL,
  ADD COLUMN description_en TEXT NULL AFTER description_fr,
  CHANGE objectif objectif_fr TEXT NULL,
  ADD COLUMN objectif_en TEXT NULL AFTER objectif_fr;

ALTER TABLE membres
  CHANGE fonction fonction_fr VARCHAR(120) NULL,
  ADD COLUMN fonction_en VARCHAR(120) NULL AFTER fonction_fr,
  CHANGE bio bio_fr TEXT NULL,
  ADD COLUMN bio_en TEXT NULL AFTER bio_fr;

-- Traductions anglaises du contenu d'origine
UPDATE services SET titre_en = 'Language and vocational training', pole_en = 'HUB 01 — EDUCATION & EMPLOYABILITY', slogan_en = '💡 Learn, grow, succeed.', resume_en = 'Master a new language or develop a professional skill that opens doors. Our training courses are practical, interactive and adapted to your pace.', description_en = 'Our training courses are practical, interactive and adapted to each learner’s pace, preparing young people and adults to seize today’s and tomorrow’s opportunities.', points_en = 'English: level-based classes, public presentations and diplomas / certificates at the end of each cycle.\nComputer skills: introduction to computers, office software and digital tools.\nArtificial intelligence: workshops to understand and use AI responsibly.\nEntrepreneurship & leadership: economic empowerment, project management and public speaking.' WHERE id = 1 AND titre_fr = 'Formation en langues et métiers';
UPDATE services SET titre_en = 'Technical support, audit & consulting', pole_en = 'HUB 02 — SUPPORT FOR ORGANISATIONS', slogan_en = '📈 Take your projects to the next level.', resume_en = 'Whether you are an organisation, a business or a local initiative, we support you with tailored solutions to optimise your management and maximise your impact.', description_en = 'Whether you are an organisation, a business or a local initiative, we support you with tailored solutions to optimise your management, improve your performance and maximise your impact.', points_en = 'Project writing and support in finding funding.\nOrganisational audits, project monitoring and evaluation.\nCapacity building for teams and community leaders.' WHERE id = 2 AND titre_fr = 'Accompagnement technique, audit & consultance';
UPDATE services SET titre_en = 'Community health & humanitarian action', pole_en = 'HUB 03 — PROTECTION & WELL-BEING', slogan_en = '❤️ Acting for life, acting for all.', resume_en = 'We work alongside communities to promote prevention and well-being. In times of crisis, we bring help and hope to the most vulnerable.', description_en = 'We work alongside communities to promote prevention and well-being, and to bring help and hope to the most vulnerable in times of crisis.', points_en = 'Food assistance for war victims and displaced families.\nDiscussions and awareness-raising on the fight against gender-based violence (GBV).\nSupervision and activities for children and teenagers during the holidays.' WHERE id = 3 AND titre_fr = 'Santé communautaire & action humanitaire';
UPDATE services SET titre_en = 'Environmental awareness & climate action', pole_en = 'HUB 04 — ECOLOGY & CLIMATE', slogan_en = '🌍 Protecting our planet, today and for tomorrow.', resume_en = 'Through campaigns, workshops and concrete actions, we raise awareness and mobilise people to preserve our resources and build a greener future.', description_en = 'Through campaigns, workshops and concrete actions, we mobilise young people to preserve our resources and build a greener future.', points_en = 'Community reforestation and tree planting with young people.\nCreation and maintenance of local tree nurseries.\nEnvironmental education in schools and neighbourhoods.' WHERE id = 4 AND titre_fr = 'Sensibilisation environnementale & lutte contre le changement climatique';
UPDATE activites SET titre_en = 'Supervision of children under 12', sous_titre_en = 'Educational support for children under 12', description_en = 'During the holidays, we welcomed children under 12 for supervised sessions: reading, writing, educational games and learning the values of respect and living together. A way to keep them usefully busy and to support their parents.' WHERE id = 1 AND titre_fr = 'Encadrement des enfants de moins de 12 ans';
UPDATE activites SET titre_en = 'Supervision of teenagers', sous_titre_en = 'Guidance, values and preparing for the future', description_en = 'Discussion and guidance sessions with teenagers on discipline, self-esteem, education and life choices. Our mentors support them so that they become responsible young people, engaged in their community.' WHERE id = 2 AND titre_fr = 'Encadrement des adolescents';
UPDATE activites SET titre_en = 'Computer training', sous_titre_en = 'Introduction to digital and office tools', description_en = 'A hands-on course introducing participants to computers and office software. Each learner was able to practise and leave with digital skills that are directly useful for their studies and employment.' WHERE id = 3 AND titre_fr = 'Formation en informatique';
UPDATE activites SET titre_en = 'Workshops on using artificial intelligence', sous_titre_en = 'Discovering and using AI responsibly', description_en = 'Workshops to discover what artificial intelligence is, how to use it to learn, write and work more efficiently, and how to use it responsibly and ethically.' WHERE id = 4 AND titre_fr = 'Ateliers sur l’utilisation de l’intelligence artificielle';
UPDATE activites SET titre_en = 'Diploma ceremony after the English course', sous_titre_en = 'End of training and celebration of the graduates', description_en = 'A proud moment: learners who successfully completed their English training cycle received their diplomas in the presence of the trainers, YLCD-ASBL members and their families.' WHERE id = 5 AND titre_fr = 'Remise des brevets après la formation d’anglais';
UPDATE activites SET titre_en = 'Certificate ceremony after the English course', sous_titre_en = 'Closing of an English training session', description_en = 'Closing of an English training session with certificates awarded to the participants, rewarding their commitment and progress throughout the course.' WHERE id = 6 AND titre_fr = 'Remise de certificats après la formation d’anglais';
UPDATE activites SET titre_en = 'Assistance to war victims', sous_titre_en = 'Support for vulnerable families and children', description_en = 'On 4 July 2024, YLCD-ASBL went to meet vulnerable families and children affected by armed conflict. Meals were shared and assistance was provided to ease their daily lives, if only for a moment.' WHERE id = 7 AND titre_fr = 'Assistance aux victimes des guerres';
UPDATE activites SET titre_en = 'Environmental protection and reforestation', sous_titre_en = 'Reforestation and community tree nurseries', description_en = 'Together with young people and community members, we prepared tree nurseries, planted and watered young seedlings. This reforestation campaign aims to fight deforestation and raise everyone’s awareness of environmental protection.' WHERE id = 8 AND titre_fr = 'Protection de l’environnement et reboisement';
UPDATE activites SET titre_en = 'Entrepreneurship and economic empowerment training', sous_titre_en = 'Supporting young and women entrepreneurs', description_en = 'One of our very first activities: a training course for young people and women entrepreneurs to help them develop a business idea, run an income-generating activity and gain economic independence.' WHERE id = 9 AND titre_fr = 'Formation en entrepreneuriat et autonomisation économique';
UPDATE activites SET titre_en = 'English classes', sous_titre_en = 'Regular level-based classes', description_en = 'Regular English classes, organised by level, to improve communication, employability and access to international opportunities.' WHERE id = 10 AND titre_fr = 'Formation d’anglais';
UPDATE activites SET titre_en = 'English public presentation', sous_titre_en = 'Speaking English in public', description_en = 'Learners speak in public in English: an exercise to build confidence and put into practice what they have learned.' WHERE id = 11 AND titre_fr = 'Présentation publique d’anglais';
UPDATE activites SET titre_en = 'Discussion on fighting GBV', sous_titre_en = 'Gender-based violence', description_en = 'A space for dialogue on the fight against gender-based violence: understanding, preventing, referring victims and breaking the silence.' WHERE id = 12 AND titre_fr = 'Discussion sur la lutte contre les VBG';
UPDATE activites SET titre_en = 'Leadership training', sous_titre_en = 'Becoming an agent of change', description_en = 'Sessions to develop a sense of responsibility, public speaking, teamwork and the ability to lead change in one’s community.' WHERE id = 13 AND titre_fr = 'Formation en leadership';
UPDATE activites SET titre_en = 'Certificate ceremony', sous_titre_en = 'Celebrating the end of each training course', description_en = 'At the end of each training course, we celebrate the participants who completed their programme with the official presentation of their certificates.' WHERE id = 14 AND titre_fr = 'Remise des certificats';
UPDATE projets SET titre_en = 'Languages for the future', categorie_en = 'Training', description_en = 'A language training programme open to young people, adults and professionals, aimed at strengthening their skills in English, French, Swahili and other strategic languages.', objectif_en = 'Improve employability, facilitate intercultural communication and open access to international opportunities.' WHERE id = 1 AND titre_fr = 'Langues pour l’avenir';
UPDATE projets SET titre_en = 'Sustainable skills and trades', categorie_en = 'Training', description_en = 'A vocational training project in promising sectors such as sewing, computing, carpentry, hairdressing and food processing.', objectif_en = 'Enable beneficiaries to create their own jobs, generate income and contribute to local development.' WHERE id = 2 AND titre_fr = 'Compétences et métiers durables';
UPDATE projets SET titre_en = 'Health for all', categorie_en = 'Health', description_en = 'A community initiative focused on health prevention and promotion: awareness campaigns, free screenings, hygiene and nutrition workshops.', objectif_en = 'Reduce the risk of preventable diseases and improve people’s well-being, especially in vulnerable areas.' WHERE id = 3 AND titre_fr = 'Santé pour tous';
UPDATE projets SET titre_en = 'A gesture for the planet', categorie_en = 'Environment', description_en = 'An environmental awareness programme including reforestation, waste management and environmental education in schools and communities.', objectif_en = 'Encourage eco-friendly behaviour and contribute to the fight against climate change.' WHERE id = 4 AND titre_fr = 'Un geste pour la planète';
UPDATE membres SET fonction_en = 'Coordination' WHERE fonction_fr = 'Coordination';
UPDATE membres SET fonction_en = 'Role' WHERE fonction_fr = 'Fonction';
