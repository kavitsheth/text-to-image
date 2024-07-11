<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class SaveImageCommand extends Command
{
    protected $signature = 'image:save {url}';

    protected $description = 'Save an image from URL to storage';

    public function handle()
    {
        $url = $this->argument('url');
        $client = new Client();

        // Fetch the image
        $response = $client->get($url);

        // Get the file name from the URL
        $filename = basename($url);

        // Store the image in storage/app/public (adjust as needed)
        Storage::disk('public')->put($filename, $response->getBody());

        $this->info("Image saved successfully: {$filename}");
    }
}
