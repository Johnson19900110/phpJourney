<?php

namespace App\Http\Controllers\App;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
//        try {
            $this->validate($request, array(
                'post_id' => 'required',
                'name' => 'required',
                'email' => 'required|email',
            ),array(
                'email' => '邮箱不合法'
            ));

            $comment = new Comment();
            $comment->parent_id = $request->input('parent_id', 0);
            $comment->post_id = $request->input('post_id');
            $comment->name = $request->input('name', '');
            $comment->email = $request->input('email', '');
            $comment->ip = $request->ip();
            $comment->markdown = $markdown = $request->input('markdown', '');
            $comment->content = (new \Parsedown())->text($markdown);

            $comment->save();

            return response()->json(array(
                'status' => 0,
                'message' => '数据获取成功',
                'comment' => $comment,
            ));
//        }catch (\Exception $exception) {
//            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());
//
//            return response()->json(array(
//                'status' => 1,
//                'message' => '数据获取失败',
//            ));
//        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $id = intval($id);
        try{
            $comments = Comment::where('post_id', $id)->get();

            return response()->json(array(
                'status' => 0,
                'comments' => $comments,
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());

            return response()->json(array(
                'status' => 1,
                'message' => '数据获取失败',
            ));
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
