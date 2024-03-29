<?php

use App\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Spatie\YamlFrontMatter\YamlFrontMatter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $posts = collect(File::files(resource_path('posts')))
        -> map (fn($file) => YamlFrontMatter::parseFile($file))
        -> map(fn($document) => new Post(
            $document->title,
            $document->excerpt,
            $document->date,
            $document->body(),
            $document->slug
        ));

//    $posts = array_map(function($file){
//        $document = YamlFrontMatter::parseFile($file);
//        return new Post(
//            $document->title,
//            $document->excerpt,
//            $document->date,
//            $document->body(),
//            $document->slug
//        );
//    }, $files);

//    foreach ($files as $file) {
//        $document = YamlFrontMatter::parseFile($file);
//        $posts[] = new Post(
//            $document->title,
//            $document->excerpt,
//            $document->date,
//            $document->body(),
//            $document->slug
//        );
//    }
//    return view('posts', [
//            'posts' => $posts
//            ]
//        );
//    dd($posts[0]->title);
//    $document = YamlFrontMatter::parseFile(
//        resource_path('/posts/my-third-post.html')
//    );
//
//    dd($document);

    return view('posts', [
            'posts' => Post::all()
            ]
        );
});

Route::get('posts/{post}', function($slug) {
    // Find a post by its slug and pass it to a view called "post"

    return view('post', ['post' => Post::find($slug)]);

//    if (! file_exists($path = __DIR__."/../resources/posts/{$slug}.html")){
////        abort(404);
//        return redirect('/');
//    }
//    $post = cache()->remember("posts.{$slug}", now()->addMonth(), fn()=>file_get_contents($path));
//
//    return view('post', ['post' => $post]);

})->where('post', '[A-z_\-]+');
