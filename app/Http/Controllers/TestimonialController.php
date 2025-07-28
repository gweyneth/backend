<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('pages.testimonials.index', compact('testimonials'));
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->is_enabled = !$testimonial->is_enabled;
        $testimonial->save();
        return response()->json(['success' => 'Status testimoni berhasil diubah.']);
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($testimonial->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($testimonial->photo);
        }
        $testimonial->delete();
        return response()->json(['success' => 'Testimoni berhasil dihapus.']);
    }
}
