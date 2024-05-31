<?php

namespace Sajadsdi\LaravelFileManagement\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\FileManagement;

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
    public function update(string $id, Request $request)
    {

    }

    /**
     * Move the specified resource to trash.
     */
    public function trash(Request $request ,FileManagement $fileManagement)
    {
        $error  = "";

        try {
            $fileManagement->trash($request->id);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return response(['data' => [], 'message' => $error], 400);
        }

        return response(['data' => [], 'message' => 'Trashing file(s) in progress!'], 200);
    }

    /**
     * Move the specified resource from trash to old path.
     */
    public function restoreTrash(Request $request ,FileManagement $fileManagement)
    {
        $error  = "";

        try {
            $fileManagement->restoreTrash($request->id);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return response(['data' => [], 'message' => $error], 400);
        }

        return response(['data' => [], 'message' => 'restoring trashed file(s) in progress!'], 200);
    }

    /**
     * Remove the specified resource.
     */
    public function delete(string $id)
    {

    }

}
