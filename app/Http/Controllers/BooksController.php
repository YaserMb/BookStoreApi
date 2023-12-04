<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\DeleteBookRequest;
use App\Http\Requests\EditBookRequest;
use App\Http\Resources\BooksCollection;
use App\Http\Resources\BooksResource;
use App\Jobs\ProcessCoverImage;
use Illuminate\Http\Request;
use App\Models\Books;
use Illuminate\Support\Facades\Process;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $books = Books::paginate(20);
        return new BooksCollection($books);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request)
    {
        $books =Books::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'cover' => $request->cover,
            'description' => $request->description,
            'published' => $request->published,
        ]);

        ProcessCoverImage::dispatch($books);
        return new BooksResource($books);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $query = $request->query('query');
        $books = Books::where('title', 'LIKE', "%{$query}%")
            ->orWhere('author', 'LIKE', "%{$query}%")
            ->orWhere('isbn', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(20);
        return new BooksCollection($books);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditBookRequest $request)
    {
        $book = Books::findOrFail($request->id);
        $book->updated([
            'title' => $request->title ? $request->title : $book->title,
            'author' => $request->author ? $request->author : $book->author,
            'isbn' => $request->isbn ? $request->isbn : $book->isbn,
            'cover' => $request->cover ? $request->cover : $book->cover,
            'description' => $request->description ? $request->description : $book->description,
            'published' => $request->published ? $request->published : $book->published,
        ]);

        if ($request->filled('cover')) {
            ProcessCoverImage::dispatch($book);
        }
        return new BooksResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteBookRequest $request)
    {
        $book = Books::findOrFail($request->id);
        $book->delete();

        return response()->json(null, 204);
    }
}
