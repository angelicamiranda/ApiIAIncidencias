<?php

namespace App\Http\Controllers;

use App\Models\MediaInfractor;
use Illuminate\Http\Request;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Validation\Rules\Unique;

class MediaInfractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medias = MediaInfractor::all();

        return response()->json(['data' => $medias], 200);
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

        $media->nombre = $request->nombre ;

 // Validar la solicitud y obtener el archivo de imagen

            $image64 = $request->foto;
            $imagedec = base64_decode($image64);
            $image = uniqid().'.jpg';

            // Generar un nombre único para la imagen
           // $imageName = time() . '.' . $image->getClientOriginalExtension();
            $folder ='infractor/'.$image;
            // Obtener la URL de la imagen almacenada en S3
            $imageUrl =Storage::disk('s3')->put($folder, $imagedec, 'public');


            $media->url = 'https://s3-sw2-taller.s3.us-east-2.amazonaws.com/'.$folder;


        $media->save();
            // Haz algo con la URL, como guardarla en la base de datos o mostrarla al usuario

            return response()->json(['url' => $media->url], 200);
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

        if (!empty($request->foto)) {
            $image64 = $request->foto;
            $imagedec = base64_decode($image64);
            $image = uniqid().'.jpg';

            // Generar un nombre único para la imagen
           // $imageName = time() . '.' . $image->getClientOriginalExtension();
            $folder ='infractor/'.$image;
            // Obtener la URL de la imagen almacenada en S3
            $imageUrl =Storage::disk('s3')->put($folder, $imagedec, 'public');
            foreach ($medias as $media) {
                if ($media->url) {
                    $image1 = $folder;
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
