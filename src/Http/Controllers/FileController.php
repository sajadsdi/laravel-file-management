<?php

namespace Sajadsdi\LaravelFileManagement\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\FileManagement;
use Sajadsdi\LaravelFileManagement\Http\Requests\UploadRequest;

class FileController extends Controller
{
    public function index(Request $request, FileRepositoryInterface $repository): Response|ResponseFactory
    {
        return response(['data' => $repository->index($request?->search, $request?->filter, $request?->sort, 20), 'message' => 'Success!'], 200);
    }

    public function single($id)
    {

    }

    /**
     * Update the specified resource.
     */
    public function update(int $id,$request)
    {

    }

    /**
     * Remove the specified resource.
     */
    public function destroy($id)
    {

    }

}
