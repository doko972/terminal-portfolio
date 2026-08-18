<?php

namespace App\Http\Controllers;

use App\Support\Cv;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CvController extends Controller
{
    /**
     * Sert le CV en téléchargement.
     */
    public function download(): StreamedResponse
    {
        abort_unless(Cv::exists(), 404);

        return Storage::disk(Cv::DISK)->download(Cv::PATH, Cv::DOWNLOAD_NAME);
    }
}
