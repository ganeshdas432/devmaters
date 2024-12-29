<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return inertia('settings/Pages/Index', ['pages' => $pages]);
    }

    public function create()
    {
        return inertia('settings/Pages/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Page::create($validated);

        return redirect()->back()->with('success', 'Page created successfully!');
    }


    public function edit(Page $page)
    {
        return inertia('Pages/Edit', ['page' => $page]);
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Find the page by ID
        $page = Page::find($id);

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        // Update the page's data
        $page->title = $validated['title'];
        $page->content = $validated['content'];
        $page->save();

        // Respond with the updated page data
        return response()->json([
            'message' => 'Page updated successfully',
            'page' => $page,
        ]);
    }
    public function destroy($id)
    {
        $page = Page::find($id);

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $page->delete();

        return response()->json(['message' => 'Page deleted successfully']);
    }

    public function getPageByTitle($title)
    {
        // Search for page by title, case insensitive
        $page = Page::where('title', 'like', '%' . $title . '%')->first();

        // If page is found, return it, else return a 404 response
        if ($page) {
            return response()->json($page);
        }

        return response()->json(['message' => 'Page not found'], 404);
    }
}
