<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Image;

class MainController extends Controller
{

    public function TextToImage(Request $request) {

        $request->validate([

            'prompt'=>'required',

        ]);

        $payload = [
          "key"  => "Enter you API key here",
          "prompt" => $request->prompt,
          "negative_prompt" => "bad quality",
          "width" => "512",
          "height" => "512",
          "safety_checker" => false,
          "seed" => null,
          "samples" => 2,
          "base64" => false,
          "webhook" =>  null,
          "track_id" => null
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://modelslab.com/api/v6/realtime/text2img',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($payload),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            dd($error_msg);
        }


        curl_close($curl);

        $responseData = json_decode($response, true); // Assuming $response is your HTTP response object


        $promptInsert = 'App\\Models\\Prompt'::create([
            'prompt'=>$request->prompt,
        ]);


        foreach ($responseData['output'] as $key => $value) {

            $url = $value;
            $client = new Client();

            // Fetch the image
            $response = $client->get($url);

            // Get the file name from the URL
            $filename = basename($url);

            // Store the image in storage/app/public (adjust as needed)
            Storage::disk('images')->put($filename, $response->getBody());

            $imageInsert = 'App\\Models\\Image'::create([
                'prompt_id'=>$promptInsert->id,
                'source'=>$filename,
            ]);

        }


        // $imageInsert =

        return $responseData['output'];

        // return $imageUrl;

    }

    public function index(){

        $prompts = Prompt::with('images')->get();
        return view('main',compact('prompts'));

    }

    public function test() {


        $promptInsert = 'App\\Models\\Prompt'::create([
            'prompt'=>"This is the prompt for testing.",
        ]);

        $links = ["https://pub-3626123a908346a7a8be8d9295f44e26.r2.dev/temp/3f9d3a52-d4ac-483b-b868-e5f34de6b656-0.png",
        "https://pub-3626123a908346a7a8be8d9295f44e26.r2.dev/temp/3f9d3a52-d4ac-483b-b868-e5f34de6b656-1.png",
        "https://pub-3626123a908346a7a8be8d9295f44e26.r2.dev/temp/3f9d3a52-d4ac-483b-b868-e5f34de6b656-2.png",
        "https://pub-3626123a908346a7a8be8d9295f44e26.r2.dev/temp/3f9d3a52-d4ac-483b-b868-e5f34de6b656-3.png"];


        foreach ($links as $key => $value) {


            // Retrieve the image URL from the request
            $imageUrl = $value;


            $filename = uniqid() . '_' . pathinfo($imageUrl)['basename'];


                // Store the downloaded image in the storage directory
                $storedPath = Storage::put('public/uploads/images' . $filename, $imageUrl);

                // If you need to generate a URL for accessing the stored image publicly
                $url = Storage::url($storedPath);

                $imageInsert = 'App\\Models\\Image'::create([
                    'prompt_id'=>$promptInsert->id,
                    'source'=>$url,
                ]);

                return response()->json(['error' => 'success.']);

        }




    }

}
