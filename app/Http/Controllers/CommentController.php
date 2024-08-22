<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //データーベースへの保存処理のためのバリデーションチェック
        $validatedData = $request->validate([
            'content' => 'required|string|max:200',
            'review_id' => 'required|integer|exists:reviews,id'
        ]);

        //データーベースへの保存処理
        $comment = Comment::create([
            'content' => $validatedData['content'],
            'review_id' => $validatedData['review_id'],
            'user_id' => Auth::id(),
        ]);

        //コメントしたユーザー情報取得処理
        $comment->load('user');

        //フロントに返す処理
        return response()->json($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //ユーザー情報確認
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['massage' => '権限がありません'],401);
        }

        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $validatedData['content'],
        ]);

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(['message' => '正常にコメントを削除しました']);
    }
}
