<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Validation simple des formulaires (messages traduits dans la langue courante).
 * $v = new Validator($_POST);
 * $v->required('titre_fr', 'Le titre (FR)')->max('titre_fr', 150, 'Le titre (FR)');
 * if ($v->fails()) { ... $v->errors ... }
 * Les libellés sont écrits en français et traduits via __().
 */
final class Validator
{
    public array $errors = [];

    public function __construct(private array $data)
    {
    }

    private function value(string $field): string
    {
        $v = $this->data[$field] ?? '';
        return is_string($v) ? trim($v) : '';
    }

    private function add(string $field, string $message, array $params = []): self
    {
        if (isset($params['label'])) {
            $params['label'] = __($params['label']);
        }
        $this->errors[$field] ??= __($message, $params);
        return $this;
    }

    public function required(string $field, string $label): self
    {
        return $this->value($field) === '' ? $this->add($field, ':label est obligatoire.', ['label' => $label]) : $this;
    }

    public function max(string $field, int $max, string $label = 'Ce champ'): self
    {
        return mb_strlen($this->value($field)) > $max
            ? $this->add($field, ':label ne doit pas dépasser :max caractères.', ['label' => $label, 'max' => $max]) : $this;
    }

    public function min(string $field, int $min, string $label = 'Ce champ'): self
    {
        $v = $this->value($field);
        return $v !== '' && mb_strlen($v) < $min
            ? $this->add($field, ':label doit contenir au moins :min caractères.', ['label' => $label, 'min' => $min]) : $this;
    }

    public function email(string $field): self
    {
        $v = $this->value($field);
        return $v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL) ? $this->add($field, 'Adresse email invalide.') : $this;
    }

    public function in(string $field, array $allowed, string $label = 'Cette valeur'): self
    {
        return !in_array($this->value($field), $allowed, true)
            ? $this->add($field, ":label n'est pas valide.", ['label' => $label]) : $this;
    }

    public function date(string $field): self
    {
        $v = $this->value($field);
        if ($v === '') {
            return $this;
        }
        $d = \DateTime::createFromFormat('Y-m-d', $v);
        return !$d || $d->format('Y-m-d') !== $v ? $this->add($field, 'Date invalide.') : $this;
    }

    public function same(string $field, string $other, string $message): self
    {
        return $this->value($field) !== $this->value($other) ? $this->add($other, $message) : $this;
    }

    public function pattern(string $field, string $regex, string $message): self
    {
        $v = $this->value($field);
        return $v !== '' && !preg_match($regex, $v) ? $this->add($field, $message) : $this;
    }

    public function url(string $field): self
    {
        $v = $this->value($field);
        return $v !== '' && !filter_var($v, FILTER_VALIDATE_URL) ? $this->add($field, 'Lien invalide (ex: https://...).') : $this;
    }

    /** Ajoute une erreur personnalisée (message en français, traduit automatiquement) si la condition est vraie. */
    public function check(bool $failed, string $field, string $message): self
    {
        return $failed ? $this->add($field, $message) : $this;
    }

    /** Champ bilingue : vérifie <champ>_fr et <champ>_en avec les mêmes règles. */
    public function bilingual(string $field, string $label, int $max, bool $required = false): self
    {
        foreach (['fr' => 'FR', 'en' => 'EN'] as $code => $tag) {
            $name = $field . '_' . $code;
            $fullLabel = __($label) . " ($tag)";
            if ($required && $this->value($name) === '') {
                $this->errors[$name] ??= __(':label est obligatoire.', ['label' => $fullLabel]);
            }
            if (mb_strlen($this->value($name)) > $max) {
                $this->errors[$name] ??= __(':label ne doit pas dépasser :max caractères.', ['label' => $fullLabel, 'max' => $max]);
            }
        }
        return $this;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }
}
