<?php

namespace Sajadsdi\LaravelFileManagement\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\FileManagement;
use Sajadsdi\LaravelFileManagement\Http\Requests\GetIDRequest;
use Sajadsdi\LaravelFileManagement\Http\Requests\UpdateRequest;

class FileController extends Controller
{
    public function index(Request $request, FileRepositoryInterface $repository): Response|ResponseFactory
    {
        return response(['data' => $repository->index($request?->search, $request?->filter, $request?->sort, 20), 'message' => 'Success!'], 200);
    }

    public function single($id, FileManagement $fileManagement)
    {
        $error = "";

        try {
            $single = $fileManagement->single($id);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return response(['data' => [], 'message' => $error], 400);
        }

        return response(['data' => $single, 'message' => 'Success!'], 200);
    }

    /**
     * Move the specified resource to trash.
     */
    public function trash(GetIDRequest $request, FileManagement $fileManagement)
    {
        $error = "";

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
    public function restoreTrash(GetIDRequest $request, FileManagement $fileManagement)
    {
        $error = "";

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
     * Delete file.
     */
    public function delete(GetIDRequest $request, FileManagement $fileManagement)
    {
        $error = "";

        try {
            $fileManagement->delete($request->id);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return response(['data' => [], 'message' => $error], 400);
        }

        return response('', 204);
    }


    /**
     * Update file.
     */
    public function update(UpdateRequest $request, FileManagement $fileManagement)
    {
        $error = "";

        try {
            $fileManagement->update($request->id, $request->updates);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return response(['data' => [], 'message' => $error], 400);
        }

        return response(['data' => [], 'message' => 'Update file(s) in progress!'], 200);
    }

}
