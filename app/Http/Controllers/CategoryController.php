<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index', [
            'categories' => Category::withCount('events')->get()
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        
        return back()->with('success', 'Category created!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted!');
    }

}