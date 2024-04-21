<?php

namespace Sajadsdi\LaravelFileManagement\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Sajadsdi\LaravelFileManagement\FileManagement;
use Sajadsdi\LaravelFileManagement\Http\Requests\UploadRequest;

class UploadController extends Controller
{
    public function upload(UploadRequest $request, FileManagement $fileManagement): Response|ResponseFactory
    {
        $upload = [];
        $error  = "";

        try {
            $upload = $fileManagement->upload($request->file('file'));
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return response(['data' => [], 'message' => $error], 400);
        }

        return response(['data' => $upload, 'message' => 'Upload Success!'], 201);
    }

}
