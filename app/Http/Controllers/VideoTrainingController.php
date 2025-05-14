<?php

namespace App\Http\Controllers;

use App\Models\CategoryVideoTraining;
use App\Models\VideoTraining;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoTrainingController extends Controller
{
    public function index(): Factory|View|Application
    {
        $categories = CategoryVideoTraining::all();

        foreach ($categories as $category) {
            $category->videos = VideoTraining::where('category_video_training_id', $category->id)
                ->orderBy('sequence')
                ->get();
        }

        return view('support.videos.index', compact('categories'));
    }

    /**
     * @param $filename
     * @return StreamedResponse
     */
    public function stream($filename): StreamedResponse
    {

        $path = storage_path('app/support/videos/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = new StreamedResponse(function () use ($file) {
            echo $file;
        });

        $response->headers->set('Content-Type', $type);
        $response->headers->set('Content-Disposition', 'inline; filename="' . $filename . '"');

        return $response;
    }

    /**
     * @param $filename
     * @return Factory|View|Application
     */
    public function player($filename): Factory|View|Application
    {
        return view('support.videos.player', compact('filename'));
    }
}
