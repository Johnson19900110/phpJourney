<?php

namespace App\Http\Controllers\Backend;

use App\Comment;
use App\Mail\UserLogin;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend/index');
    }

    public function statistical(Request $request)
    {
        $data = array();

//        posts: 0,
//        comments: 0,
//                    post_trash: 0,
//                    recent_posts:[]
        try {
            $data['posts'] = Post::count();
            $data['comments'] = Comment::count();
            $data['post_trash'] = Post::onlyTrashed()->count();
            $data['recent_posts'] = Post::orderBy('created_at', 'DESC')->limit(5)->get();

            return response()->json(array(
                'status' => 0,
                'message' => '数据获取失败',
                'data' => $data,
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());

            return response()->json(array(
                'status' => 1,
                'message' => '数据获取失败',
            ));
        }


    }
}
