<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishRatingController extends Controller
{
    /**
     * Rate a dish.
     *
     * @param Request $request
     * @param int $dishId
     * @return JsonResponse
     */
    public function rateDish(Request $request, int $dishId): JsonResponse
    {

        // Validate the request
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // Find the dish
        $dish = Dish::findOrFail($dishId);

        // Check if the user is rating their own dish
        if ($dish->user_id === auth()->id()) {
            return response()->json(['error' => 'You cannot rate your own dish.'], 422);
        }

        // Bonus: Check if the user is Sméagol and deny rating
        if (auth()->user()->nickname === 'Sméagol') {
            return response()->json(['error' => 'Sméagol cannot rate dishes.'], 422);
        }

        // Check if the user has already rated this dish
        $existingRating = Rating::where('user_id', auth()->id())
            ->where('dish_id', $dishId)
            ->first();

        if ($existingRating) {
            return response()->json(['error' => 'You have already rated this dish.'], 422);
        }

        // Create a new rating
        $rating = new Rating([
            'user_id' => auth()->id(),
            'dish_id' => $dishId,
            'rating' => $request->input('rating'),
        ]);

        // Save the rating
        $rating->save();

        // Optionally, you can update the average rating for the dish
        $dish->update(['average_rating' => $dish->averageRating()]);

        return response()->json(['message' => 'Dish rated successfully.']);
    }

    /**
     * Get ratings for a dish.
     *
     * @param int $dishId
     * @return JsonResponse
     */
    public function getRatings(int $dishId): JsonResponse
    {
        try {
            $dish = Dish::findOrFail($dishId);
            $ratings = $dish->ratings;

            return response()->json(['data' => $ratings]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Dish not found.'], 404);
        }
    }

    /**
     * Display the specified dish.
     *
     * @param Dish $dish
     * @return JsonResponse
     */
    public function show(Dish $dish): JsonResponse
    {
        return response()->json(['data' => $dish]);
    }

}
