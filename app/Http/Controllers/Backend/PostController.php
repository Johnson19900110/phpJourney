<?php

namespace App\Http\Controllers\Backend;

use App\Post;
use App\PostsTag;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 1)
    {
        //
        try {
            $rows = $request->input('rows', 10);
            $posts = Post::ofCategory($request->input('category_id')+0)->ofTitle($request->input('q'))->paginate($rows);
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
        try {
            $user = $request->user();
            $current_time = date('Y-m-d H:i:s');
            $data = array(
                'title' => $request->input('title'),
                'category_id' => $request->input('category_id'),
                'markdown' => $markdown = $request->input('markdown', ''),
                'content' => (new \Parsedown())->text($markdown),
                'user_id' => $user->id,
                'ipaddress' => $request->ip(),
                'created_at' => $current_time,
                'updated_at' => $current_time,
            );

            $tags = $request->input('tags', []);
            DB::transaction(function () use ($data, $tags, $current_time) {
                $post_id = Post::insertGetId($data);
                if(!empty($tags)) {
                    foreach ($tags as $tag) {
                        if(!$tag_id = Tag::where('tags_flag', strtolower($tag))->value('id')) {
                            $tag_id = Tag::insertGetId(array(
                                'tags_name' => $tag,
                                'tags_flag' => strtolower($tag),
                                'created_at' => $current_time,
                                'updated_at' => $current_time,
                            ));
                        }

                        PostsTag::insert(array(
                            'post_id' => $post_id,
                            'tag_id' => $tag_id,
                        ));
                    }
                }
            });


            return response()->json(array(
                'status' => 0,
                'message' => '新增成功'
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());
            return response()->json(array(
                'status' => 1,
                'message' => '新增失败',
            ));
        }

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
        $id = $id+0;
        if($id <= 0) {
            return response()->json(array(
                'status' => 2,
                'message' => '数据获取失败',
            ));
        }

        try{
            $post = Post::find($id);

            $tags_ids = PostsTag::where('post_id', $post->id)->get();

            if(count($tags_ids)) {
                $ids = array();
                foreach ($tags_ids as $tags_id) {
                    $ids[] = $tags_id->tags_id;
                }

                $tags = Tag::whereIn('id', $ids)->get();
            }else {
                $tags = [];
            }

            return response()->json(array(
                'status' => 0,
                'data' => $post,
                'tags' => $tags,
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
        $id = $request->input('id')+0;

        try {
            $post = Post::find($id);
            $post->title = $request->input('title');
            $post->category_id = $request->input('category_id');
            $post->markdown = $marksown = $request->input('markdown');
            $post->content = (new \Parsedown())->text($marksown);

            $tags = $request->input('tags', []);

            DB::transaction(function () use ($post, $tags) {
                $post->update();
                PostsTag::where('post_id', $post->id)->delete();
                if(!empty($tags)) {
                    foreach ($tags as $tag) {
                        if(!$tag_id = Tag::where('tags_flag', strtolower($tag))->value('id')) {
                            $tag_id = Tag::insertGetId(array(
                                'tags_name' => $tag,
                                'tags_flag' => strtolower($tag),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ));
                        }

                        PostsTag::insert(array(
                            'post_id' => $post->id,
                            'tag_id' => $tag_id,
                        ));
                    }
                }
            });

            return response()->json(array(
                'status' => 0,
                'message' => '更新成功',
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());

            return response()->json(array(
                'status' => 1,
                'message' => '更新失败',
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
                Post::destroy($ids);
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
