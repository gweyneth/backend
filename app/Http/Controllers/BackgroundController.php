<?php

namespace App\Http\Controllers;

use App\Models\Background;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackgroundController extends Controller
{
    public function index()
    {
        $backgrounds = Background::latest()->get()->groupBy('section_name');
        return view('pages.backgrounds.index', compact('backgrounds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $path = $request->file('image')->store('uploads/backgrounds', 'public');

        Background::create([
            'section_name' => $request->section_name,
            'image_path' => $path,
            'is_active' => false, // Default tidak aktif
        ]);

        return redirect()->route('backgrounds.index')->with('success', 'Gambar background berhasil diunggah.');
    }

    public function setActive(Background $background)
    {
        // Nonaktifkan semua background lain di section yang sama
        Background::where('section_name', $background->section_name)
                  ->where('id', '!=', $background->id)
                  ->update(['is_active' => false]);

        // Aktifkan background yang dipilih
        $background->is_active = true;
        $background->save();

        return redirect()->route('backgrounds.index')->with('success', 'Background ' . $background->section_name . ' berhasil diubah.');
    }

    public function destroy(Background $background)
    {
        if ($background->image_path && Storage::disk('public')->exists($background->image_path)) {
            Storage::disk('public')->delete($background->image_path);
        }
        $background->delete();
        return redirect()->route('backgrounds.index')->with('success', 'Gambar background berhasil dihapus.');
    }
}
