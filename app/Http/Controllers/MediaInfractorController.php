<?php

namespace App\Http\Controllers;

use App\Models\MediaInfractor;
use Illuminate\Http\Request;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class MediaInfractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mediaInfractor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $media = new MediaInfractor();



 // Validar la solicitud y obtener el archivo de imagen
            $request->validate([
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $image = $request->file('foto');

            // Generar un nombre único para la imagen
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $folder ='infractor';
            // Obtener la URL de la imagen almacenada en S3
            $imageUrl =Storage::disk('s3')->put($folder, $image, 'public');


            $media->url = 'https://s3-sw2-taller.s3.us-east-2.amazonaws.com/'.$imageUrl;

            $media->base64 = base64_encode($media->url);
        $media->save();
            // Haz algo con la URL, como guardarla en la base de datos o mostrarla al usuario

            return 'Imagen subida exitosamente. URL: ' . $imageUrl;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function identify()
    {
        return view('mediaInfractor.identify');
    }

    // Y ESTE ES PARA IDENTIFICAR UN USUARIO ENVIANDO UNA FOTO POR PARAMETRO

    public function identifyUser(Request $request)
    {


        $medias = MediaInfractor::all();

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');

            // Generar un nombre único para la imagen
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $folder ='infractor';
            // Obtener la URL de la imagen almacenada en S3
            $imageUrl =  Storage::disk('s3')->put($folder, $image, 'public');

            foreach ($medias as $media) {
                if ($media->url) {
                    $image1 = substr($imageUrl, 0, strlen($imageUrl));
                    $image2 = substr($media->url, 49, strlen($media->url));
                    $client = new RekognitionClient([
                        'region' => 'us-east-2',
                        'version' => 'latest',
                    ]);

                    $results = $client->compareFaces([
                        'SimilarityThreshold' => 80,
                        'SourceImage' => [
                            'S3Object' => [
                                'Bucket' => 's3-sw2-taller',
                                'Name' => $image1,
                            ],
                        ],
                        'TargetImage' => [
                            'S3Object' => [
                                'Bucket' => 's3-sw2-taller',
                                'Name' => $image2,
                            ],
                        ],
                    ]);

                    $resultLabels = $results->get('FaceMatches');

                    if (!empty($resultLabels)) {
                        return response()->json(['media' => $media], 200);
                    }
                }
            }
        }

        return response()->json([],204);
    }



}
