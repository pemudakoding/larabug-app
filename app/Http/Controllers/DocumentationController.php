<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\DocumentationCategory;

class DocumentationController extends Controller
{
    public function index()
    {
        return redirect()->route('docs.show', ['general', 'welcome']);
    }

    public function show(DocumentationCategory $category, Documentation $item)
    {
        $categories = DocumentationCategory::whereHas('items')->with('items')->get();

        return view('frontend.documentation.show')
            ->with(compact('categories', 'category', 'item'));
    }
}
