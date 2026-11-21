<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Mailer;
use App\Core\Validator;
use App\Models\Contact;

/** Formulaire de contact : enregistrement en base + envoi d'un email à l'association. */
class ContactController extends Controller
{
    public function send(): void
    {
        // Champ piège invisible : rempli uniquement par les robots
        if ($this->input('website') !== '') {
            redirect('#contact');
        }

        $v = (new Validator($_POST))
            ->required('nom', 'Le nom')->max('nom', 120, 'Le nom')
            ->required('email', "L'email")->email('email')->max('email', 150, "L'email")
            ->max('telephone', 40, 'Le téléphone')
            ->max('sujet', 180, 'Le sujet')
            ->required('message', 'Le message')->max('message', 5000, 'Le message');

        if ($v->fails()) {
            $this->back(url('#contact'), $v->errors, $_POST);
        }

        $data = [
            'nom'       => $this->input('nom'),
            'email'     => $this->input('email'),
            'telephone' => $this->input('telephone') ?: null,
            'sujet'     => $this->input('sujet') ?: null,
            'message'   => $this->input('message'),
            'ip'        => $_SERVER['REMOTE_ADDR'] ?? null,
        ];
        $id = Contact::create($data);

        $body = "Nouveau message reçu depuis le site web de YLCD-ASBL\n"
            . str_repeat('-', 50) . "\n"
            . "Nom       : {$data['nom']}\n"
            . "Email     : {$data['email']}\n"
            . 'Téléphone : ' . ($data['telephone'] ?? '-') . "\n"
            . 'Sujet     : ' . ($data['sujet'] ?? '-') . "\n"
            . 'Date      : ' . date('d/m/Y H:i') . "\n"
            . str_repeat('-', 50) . "\n\n"
            . $data['message'] . "\n\n"
            . "Répondez directement à cet email pour contacter l'expéditeur.";

        $sent = Mailer::send(
            config('mail.to'),
            'Site YLCD : ' . ($data['sujet'] ?? 'nouveau message de ' . $data['nom']),
            $body,
            $data['email'],
            $data['nom']
        );
        Contact::update($id, ['email_envoye' => $sent ? 1 : 0]);

        flash('success', __('Merci :name ! Votre message a bien été envoyé. Nous vous répondrons très bientôt.', ['name' => $data['nom']]));
        redirect(url('#contact'));
    }
}
