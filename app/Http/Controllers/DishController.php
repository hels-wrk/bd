<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Display a paginated list of dishes.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search');

        $query = Dish::query();

        if ($searchTerm) {
            $query->search($searchTerm);
        }

        $dishes = $query->paginate($perPage);

        return response()->json(['data' => $dishes]);
    }

    /**
     * Display the specified dish.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $dish = Dish::findOrFail($id);
            return response()->json(['data' => $dish]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Dish not found.'], 404);
        }
    }

    /**
     * Store a newly created dish in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:dishes',
            'description' => 'required',
            'image' => 'required',
            'price' => 'required|numeric',
        ]);

        $dish = Dish::create($validatedData);

        return response()->json(['message' => 'Dish created successfully', 'data' => $dish], 201);
    }

    /**
     * Update the specified dish in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:dishes,name,' . $id,
            'description' => 'required',
            'image' => 'required',
            'price' => 'required|numeric',
        ]);

        $dish = Dish::findOrFail($id);
        $dish->update($validatedData);

        return response()->json(['message' => 'Dish updated successfully', 'data' => $dish]);
    }

    /**
     * Remove the specified dish from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $dish = Dish::findOrFail($id);
        $dish->delete();

        return response()->json(['message' => 'Dish deleted successfully']);
    }

}
