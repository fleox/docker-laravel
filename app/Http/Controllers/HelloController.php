<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function hello(Request $request, string $name)
    {

        /*
        Post::create([
            'title' => 'MON TITRE 2',
            'slug' => "mon_titre_2",
            'content' => 'le contenu de mon article'
        ]);
        */

        /*
        Category::create([
            'name' => 'Categorie 1',
        ]);

        Category::create([
            'name' => 'Categorie 2',
        ]);
        */


        //$posts = Post::all();

        $posts = Post::where('slug', 'mon_titre')->with('category')->get();

        return view('hello', [
            'name' => $name,
            'posts' => $posts,
        ]);
    }
}
