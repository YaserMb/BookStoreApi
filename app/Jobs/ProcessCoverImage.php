<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProcessCoverImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $book;
    public function __construct($book)
    {
        $this->book = $book;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $book = $this->book;

        $coverImage = Storage::disk('public')->get($book->cover_image);
        $processImage = Image::make($coverImage)
                        ->resize(200, 300, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode('png', 75);
        $processedImagePath = 'processed_covers/' . basename($book->cover_image, '.' . pathinfo($book->cover_image, PATHINFO_EXTENSION)) . '_processed.jpg';
        Storage::disk('public')->put($processedImagePath, $processImage);
        $book->cover_image = $processedImagePath;
        $book->save();
    }
}
