<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Authorization is now handled by the 'role' middleware in web.php
    // for all methods in this controller.

    /**
     * Guardar un nuevo post (noticia o evento).
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:noticia,evento',
            'image_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'event_date' => 'nullable|date|required_if:category,evento',
            'event_location' => 'nullable|string|max:255',
        ], [
            'title.required' => 'El título es obligatorio.',
            'content.required' => 'El contenido es obligatorio.',
            'category.required' => 'Debes seleccionar una categoría.',
            'category.in' => 'La categoría debe ser "noticia" o "evento".',
            'event_date.required_if' => 'La fecha es obligatoria para eventos.',
            'image_url.url' => 'La URL de la imagen no es válida.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'Formatos permitidos: JPG, PNG, WEBP.',
        ]);

        $finalData = $validated;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            // Use relative URL to avoid APP_URL localhost/127.0.0.1 mismatch issues
            $finalData['image_url'] = '/storage/' . $path;
        }

        // Quitamos 'image' para que no cause ruido en el create si existe
        unset($finalData['image']);

        Post::create([
            ...$finalData,
            'user_id' => Auth::id(),
            'is_published' => true,
        ]);

        return back()->with('success', '✅ Publicación creada exitosamente y ya es visible en la página principal.');
    }

    /**
     * Eliminar un post (solo el autor o admin puede hacerlo).
     */
    public function destroy(Post $post)
    {

        $user = Auth::user();

        // Admin puede eliminar cualquier post; manager solo los suyos
        if ($user->role === 'manager' && $post->user_id !== $user->id) {
            abort(403, 'Solo puedes eliminar tus propias publicaciones.');
        }

        $post->delete();

        return back()->with('success', '🗑️ Publicación eliminada correctamente.');
    }

    /**
     * Alternar visibilidad (publicado / borrador).
     */
    public function toggle(Post $post)
    {

        $post->update(['is_published' => !$post->is_published]);

        $status = $post->is_published ? 'publicada' : 'ocultada';
        return back()->with('success', "Publicación {$status} exitosamente.");
    }
}
