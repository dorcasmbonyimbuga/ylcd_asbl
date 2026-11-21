<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Contact;

/** Messages reçus via le formulaire de contact (table contacts). */
class MessageController extends AdminController
{
    private const BASE = 'admin/messages';

    public function index(): void
    {
        $this->view('messages/index', ['title' => __('Messages'), 'pager' => $this->listing(Contact::class)]);
    }

    public function show(int $id): void
    {
        $message = Contact::find($id) ?? $this->notFound(self::BASE);
        if (!$message['est_lu']) {
            Contact::update($id, ['est_lu' => 1]);
        }
        $this->view('messages/show', ['title' => __('Message de :name', ['name' => $message['nom']]), 'message' => $message]);
    }

    public function destroy(int $id): void
    {
        Contact::find($id) ?? $this->notFound(self::BASE);
        Contact::delete($id);
        flash('success', __('Message supprimé.'));
        redirect(self::BASE);
    }
}
