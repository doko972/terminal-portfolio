<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Centralise l'emplacement du CV téléchargeable.
 *
 * Le fichier est délibérément stocké sur le disque privé plutôt que sur
 * `public` : un CV contient des données personnelles (téléphone, adresse), et
 * on préfère un point d'entrée unique et maîtrisé — la route /cv — plutôt
 * qu'une URL directe devinable et indexable dans /storage.
 */
class Cv
{
    public const DISK = 'local';

    /** Chemin fixe : un nouveau téléversement remplace simplement le précédent. */
    public const PATH = 'cv/cv.pdf';

    /** Nom proposé au visiteur au téléchargement. */
    public const DOWNLOAD_NAME = 'CV-David-Grougi.pdf';

    /** Poids maximum accepté, en kilo-octets. */
    public const MAX_KB = 5120;

    public static function exists(): bool
    {
        return Storage::disk(self::DISK)->exists(self::PATH);
    }

    public static function store(UploadedFile $file): void
    {
        Storage::disk(self::DISK)->putFileAs(
            dirname(self::PATH),
            $file,
            basename(self::PATH)
        );
    }

    public static function delete(): void
    {
        Storage::disk(self::DISK)->delete(self::PATH);
    }

    /** Taille en octets, ou null si aucun CV n'est en ligne. */
    public static function size(): ?int
    {
        return self::exists() ? Storage::disk(self::DISK)->size(self::PATH) : null;
    }

    /** Date du dernier téléversement, ou null si aucun CV n'est en ligne. */
    public static function updatedAt(): ?Carbon
    {
        if (! self::exists()) {
            return null;
        }

        return Carbon::createFromTimestamp(
            Storage::disk(self::DISK)->lastModified(self::PATH)
        );
    }

    /** Taille formatée pour l'affichage, par exemple « 1,4 Mo ». */
    public static function humanSize(): ?string
    {
        $bytes = self::size();

        if ($bytes === null) {
            return null;
        }

        return $bytes >= 1048576
            ? number_format($bytes / 1048576, 1, ',', ' ').' Mo'
            : number_format($bytes / 1024, 0, ',', ' ').' Ko';
    }
}
