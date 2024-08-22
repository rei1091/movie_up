<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($media_type, $media_id)
    {
        $reviews = Review::with('user')
            ->where('media_type', $media_type)
            ->where('media_id', $media_id)
            ->get();

        return response()->json($reviews);
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
    public function store(Request $request)
    {
        $review = $request->input('content');

        $validatedDate = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer',
            'media_type' => 'required|string',
            'media_id' => 'required|integer',
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'content' => $validatedDate['content'],
            'rating' => $validatedDate['rating'],
            'media_type' => $validatedDate['media_type'],
            'media_id' => $validatedDate['media_id'],
        ]);

        $review->load('user');
        return response()->json($review);
    }

    /**
     * Display the specified resource.
     */
    public function show(review $review)
    {
        $review->load('user', 'comments.user');

        return response()->json($review);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, review $review)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer',
        ]);

        $review->update([
            'content' => $validatedData['content'],
            'rating' => $validatedData['rating'],
        ]);

        return response()->json($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(review $review)
    {
        $review->delete();

        return response()->json(['message' => '正常にレビューを削除しました']);
    }
}
