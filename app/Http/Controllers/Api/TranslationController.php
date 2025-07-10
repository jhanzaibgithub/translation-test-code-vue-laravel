<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Translation;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Translation Management API",
 *     version="1.0.0",
 *     description="API documentation for the Translation Management System",
 *     @OA\Contact(
 *         email="your-email@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */

class TranslationController extends Controller
{
   /**
 * @OA\Get(
 *     path="/api/translations",
 *     tags={"Translations"},
 *     summary="Get translations",
 *     security={{"passport": {}}},
 *     @OA\Parameter(
 *         name="page", in="query", required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Success")
 * )
 */
    public function index(Request $request)
{
    $query = Translation::query();

    if ($request->filled('key')) {
        $query->where('key', 'like', '%' . $request->key . '%');
    }

    if ($request->filled('value')) {
        $query->where('value', 'like', '%' . $request->value . '%');
    }

    if ($request->filled('tag')) {
        $query->where('tag', $request->tag);
    }

    $perPage = $request->per_page == 'all' ? $query->count() : $request->per_page;

    return response()->json([
        'data' => $query->paginate($perPage)
    ]);
}


    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'key' => 'required|string',
                'value' => 'required|string',
                'locale' => 'required|string|max:5',
                'tag' => 'nullable|string',
            ]);

            $translation = Translation::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Translation created successfully',
                'data' => $translation,
            ], 201);
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to create translation');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $translation = Translation::findOrFail($id);
            $translation->update($request->only(['key', 'value', 'locale', 'tag']));

            return response()->json([
                'status' => true,
                'message' => 'Translation updated successfully',
                'data' => $translation,
            ]);
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to update translation');
        }
    }

  
    public function show($id)
    {
        try {
            $translation = Translation::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Translation found',
                'data' => $translation,
            ]);
        } catch (\Throwable $e) {
            return $this->errorResponse('Translation not found', 404);
        }
    }

  
    public function destroy($id)
    {
        try {
            $translation = Translation::findOrFail($id);
            $translation->delete();

            return response()->json([
                'status' => true,
                'message' => 'Translation deleted successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to delete translation');
        }
    }

   
   public function export(Request $request)
{
    try {
        $query = Translation::query();

        if ($request->filled('tag')) {
            $query->where('tag', $request->tag);
        }

        $translations = $query->select('locale', 'key', 'value')
            ->get()
            ->groupBy('locale')
            ->map(function ($items) {
                return $items->pluck('value', 'key');
            });

        return response()->json([
            'status' => true,
            'message' => 'Translations exported successfully',
            'data' => $translations,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to export translations',
            'error' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Reusable error response method
     */
    private function errorResponse($message = 'Something went wrong', $code = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $code);
    }
    public function getTags()
{
    $tags = Translation::whereNotNull('tag')
        ->distinct()
        ->pluck('tag');

    return response()->json([
        'tags' => $tags
    ]);
}
public function all()
{
    $translations = Translation::orderBy('id', 'desc')->get();

    return response()->json(['data' => $translations]);
}

}
