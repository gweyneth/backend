<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Post;
use App\Models\Karyawan;
use App\Models\Testimonial;
use App\Models\Background;

use Illuminate\Http\Request;

class InterfaceController extends Controller
{

public function beranda()
{
     $perusahaan = Perusahaan::first();
     $produks = Produk::with('kategori')->inRandomOrder()->take(3)->get();
     $testimonials = Testimonial::where('is_enabled', true)->latest()->get();
     $posts = Post::with('user')->where('status', 'published')->latest()->take(3)->get();
     $heroBackground = Background::where('section_name', 'hero')->where('is_active', true)->first();

     return view('interface.beranda', compact('perusahaan', 'produks', 'testimonials', 'posts', 'heroBackground'));
}

    public function shop()
    {
        $perusahaan = Perusahaan::first();
        $produks = Produk::latest()->get();
        $heroBackground = Background::where('section_name', 'hero')->where('is_active', true)->first();
        

        return view('interface.shop', compact('perusahaan', 'produks', 'heroBackground'));
    }

    public function blogIndex()
    {
        $perusahaan = Perusahaan::first();
        $posts = Post::where('status', 'published')->latest()->paginate(6);
      
        $testimonials = Testimonial::where('is_enabled', true)->latest()->get();
        $heroBackground = Background::where('section_name', 'hero')->where('is_active', true)->first();
        return view('interface.blog-index', compact('perusahaan', 'posts', 'testimonials', 'heroBackground'));
    }

    public function blogShow($slug)
    {
        $perusahaan = Perusahaan::first();
        $post = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('interface.blog-show', compact('perusahaan', 'post'));
    }

    public function about()
    {
        $perusahaan = Perusahaan::first();
        $karyawan = Karyawan::inRandomOrder()->take(4)->get();
        $heroBackground = Background::where('section_name', 'hero')->where('is_active', true)->first();

        return view('interface.about', compact('perusahaan', 'karyawan', 'heroBackground'));
    }

    public function services()
    {
        $perusahaan = Perusahaan::first();

        $testimonials = Testimonial::where('is_enabled', true)->latest()->take(5)->get();
        $heroBackground = Background::where('section_name', 'hero')->where('is_active', true)->first();

        return view('interface.services', compact('perusahaan', 'testimonials', 'heroBackground'));
    }

    public function contact()
    {
        $perusahaan = Perusahaan::first();
        $heroBackground = Background::where('section_name', 'hero')->where('is_active', true)->first();
        return view('interface.contact', compact('perusahaan', 'heroBackground'));
    }


    public function testimonialsIndex()
    {
        $perusahaan = Perusahaan::first();
        $testimonials = Testimonial::where('is_enabled', true)->latest()->get();
        $heroBackground = Background::where('section_name', 'hero')->where('is_active', true)->first(); // Diubah dari paginate() ke get()
        return view('interface.testimonials', compact('perusahaan', 'testimonials', 'heroBackground'));
    }


    public function storeTestimonial(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'content' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('uploads/testimonials', 'public');
        }

        Testimonial::create($validatedData);

        return redirect()->back()->with('testimonial_success', 'Terima kasih! Testimoni Anda telah kami terima dan akan ditinjau oleh tim kami.');
    }
}
