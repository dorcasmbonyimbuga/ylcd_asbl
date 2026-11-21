<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Gestion des fichiers envoyés. Les fichiers sont rangés dans uploads/<dossier>/
 * (un dossier par table) et seul leur chemin relatif est enregistré en base.
 */
final class Upload
{
    public const IMAGE_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    public const VIDEO_TYPES = [
        'video/mp4'  => 'mp4',
        'video/webm' => 'webm',
        'video/ogg'  => 'ogv',
    ];

    /** Vrai si un fichier a été choisi dans le champ. */
    public static function has(?array $file): bool
    {
        return $file !== null && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
    }

    /**
     * Enregistre un fichier et retourne ['path' => 'uploads/x/abc.jpg', 'type' => 'image'|'video'].
     * @throws RuntimeException si le fichier est invalide
     */
    public static function store(array $file, string $folder, bool $allowVideo = false): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException(self::errorMessage((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE)));
        }
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException(__('Fichier invalide.'));
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']) ?: '';
        if (isset(self::IMAGE_TYPES[$mime])) {
            $type = 'image';
            $ext = self::IMAGE_TYPES[$mime];
            $maxMb = (int) config('upload.max_image_mb', 5);
        } elseif ($allowVideo && isset(self::VIDEO_TYPES[$mime])) {
            $type = 'video';
            $ext = self::VIDEO_TYPES[$mime];
            $maxMb = (int) config('upload.max_video_mb', 60);
        } else {
            throw new RuntimeException(__('Format non accepté (:name).', ['name' => $file['name'] ?? '']) . ' '
                . ($allowVideo ? __('Images JPG, PNG, WEBP, GIF ou vidéos MP4, WEBM.') : __('Images JPG, PNG, WEBP ou GIF uniquement.')));
        }

        if ($file['size'] > $maxMb * 1024 * 1024) {
            throw new RuntimeException(__('Le fichier « :name » dépasse la taille maximale de :max Mo.', ['name' => $file['name'], 'max' => $maxMb]));
        }

        $folder = preg_replace('/[^a-z0-9_-]/i', '', $folder);
        $dir = ROOT . '/uploads/' . $folder;
        if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
            throw new RuntimeException(__('Impossible de créer le dossier uploads/:folder.', ['folder' => $folder]));
        }

        $name = date('Ymd') . '-' . bin2hex(random_bytes(8)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            throw new RuntimeException(__("Impossible d'enregistrer le fichier."));
        }

        return ['path' => "uploads/$folder/$name", 'type' => $type];
    }

    /** Transforme un champ <input type="file" multiple> en liste de fichiers. */
    public static function many(?array $input): array
    {
        if (!$input || !is_array($input['name'] ?? null)) {
            return self::has($input) ? [$input] : [];
        }
        $files = [];
        foreach ($input['name'] as $i => $name) {
            $file = [
                'name'     => $name,
                'type'     => $input['type'][$i],
                'tmp_name' => $input['tmp_name'][$i],
                'error'    => $input['error'][$i],
                'size'     => $input['size'][$i],
            ];
            if (self::has($file)) {
                $files[] = $file;
            }
        }
        return $files;
    }

    /** Supprime un fichier du dossier uploads/ (jamais en dehors). */
    public static function delete(?string $path): void
    {
        if (!$path || !str_starts_with($path, 'uploads/') || str_contains($path, '..')) {
            return;
        }
        $full = ROOT . '/' . $path;
        if (is_file($full)) {
            @unlink($full);
        }
    }

    private static function errorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => __('Le fichier est trop volumineux pour le serveur (voir upload_max_filesize).'),
            UPLOAD_ERR_PARTIAL  => __("Le fichier n'a été que partiellement envoyé."),
            UPLOAD_ERR_NO_FILE  => __('Aucun fichier envoyé.'),
            default             => __("Erreur lors de l'envoi du fichier (code :code).", ['code' => $code]),
        };
    }
}
