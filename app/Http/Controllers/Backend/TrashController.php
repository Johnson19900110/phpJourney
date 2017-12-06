<?php

namespace App\Http\Controllers\Backend;

use App\Post;
use App\PostsTag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        try {
            $rows = $request->input('rows', 10);
            $posts = Post::onlyTrashed()->ofCategory($request->input('category_id')+0)->ofTitle($request->input('q'))->paginate($rows);
            foreach ($posts as $post) {
                $post->tags;
                $post->categories;
            }
            return response()->json(array(
                'status' => 0,
                'data' => $posts
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());

            return response()->json(array(
                'status' => 1,
                'message' => '数据获取失败'
            ));
        }
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
        if(!$request->filled('ids')) {
            return response()->json(array(
                'status' => 2,
                'message' => '缺少参数',
            ));
        }
        $ids = $request->input('ids');

        try{
            Post::onlyTrashed()->whereIn('id', $ids)->restore();

            return response()->json(array(
                'status' => 0,
                'message' => '恢复成功'
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());

            return response()->json(array(
                'status' => 1,
                'message' => '恢复失败'
            ));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        if(!$request->filled('ids')) {
            return response()->json(array(
                'status' => 2,
                'message' => '缺少参数',
            ));
        }
        $ids = $request->input('ids');

        try {
            DB::transaction(function () use($ids) {
                Post::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                PostsTag::whereIn('post_id', $ids)->delete();
            });

            return response()->json(array(
                'status' => 0,
                'message' => '删除成功',
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());

            return response()->json(array(
                'status' => 1,
                'message' => '删除失败',
            ));
        }
    }
}
