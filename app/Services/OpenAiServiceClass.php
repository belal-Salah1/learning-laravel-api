<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use OpenAI\Factory;

class OpenAiServiceClass
{
    public function generatePromptFromImage( UploadedFile $image):string{

        $imageData= base64_encode(file_get_contents($image->getPathname()));
        $mineType = $image->getMimeType();

        $client = (new Factory())->withApiKey(config('services.openai.key'))->make();
        $response = $client->chat()->create([
            'model' =>'gpt-4o',
            'messages' =>[
                'role'=>'user',
                'content'=>[

                    [
                        'type'=>'text',
                        'text'=>'Analyze this image and genereate a detailed, descriptive pormpt that could be used to recreate a 
                        a similar image with ai image generation tools. the prompt should be comprehesive .describing the visual elements,style,composition,lighting,color,and any other relevent details. Make it detailed engough that someone could use it to generate a similar image.
                        you must preserve aspect ratio exact as the original has or very close to it.',
                    ],
                    [
                        'type'=>'image_url',
                        'image_url'=>[
                            'url'=>"data:$mineType;base64,$imageData"
                        ]
                    ]
                ]
            ]
        ]);

        return $response->choices[0]->message->content; 

    }
}
