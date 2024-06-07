<?php

namespace App\Http\Controllers\Opportunities;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GetPhotoUrlController;

class CategoryController extends Controller
{
    // Retrieve all categories from the database
    public function index()
    {
            // Retrieve all categories from the database
            $categories = Category::all();

            // Transform the categories data, presumably to add photo URLs
            GetPhotoUrlController::transform($categories);

            // Return a JSON response with the categories data
            return response()->json([
                'status' => true,
                'data' => $categories
            ], 200);
    }


    // Search method not in used by the frontend 
    public function search(Request $request)
    {
        // Get the search query from the request input
        $query = $request->input('search');

        // Search for categories where the name is like the query (case-insensitive partial match)
        $categoriesSearch = Category::where('name', 'like', "%$query%")->get();
        
        // Transform the search results, presumably to add photo URLs
        GetPhotoUrlController::transform($categoriesSearch);

        // Return a JSON response with the search results
        return response()->json([
            'status' => true,
            'data' => $categoriesSearch
        ], 200);
    }
}
