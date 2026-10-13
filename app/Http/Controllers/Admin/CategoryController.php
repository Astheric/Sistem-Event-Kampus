<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display master categories list (FR-04).
     */
    public function index(): View
    {
        $categories = Category::withCount('events')->orderBy('name', 'asc')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store new category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Category::where('slug', $slug)->exists()) {
            $slug = $slug.'-'.time();
        }

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'icon' => $validated['icon'] ?? '📌',
        ]);

        AuditLogger::log('category.created', 'Category', $category->id, ['name' => $category->name]);

        return back()->with('success', 'Kategori '.$category->name.' berhasil ditambahkan!');
    }

    /**
     * Update category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        $category->update($validated);

        AuditLogger::log('category.updated', 'Category', $category->id, ['name' => $category->name]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Delete category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->events()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa event.');
        }

        $name = $category->name;
        $category->delete();

        AuditLogger::log('category.deleted', 'Category', $category->id, ['name' => $name]);

        return back()->with('success', 'Kategori '.$name.' berhasil dihapus.');
    }
}
