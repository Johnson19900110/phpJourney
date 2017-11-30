<?php

namespace App\Http\Controllers\App;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        try {
            // 分页获取所有文章
            $posts = Post::orderBy('id', 'desc')->paginate(15);

            return view('index', array(
                'posts' => $posts
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());
            abort(500);
        }
    }

    public function post($id)
    {
        try{
            $post = Post::find(intval($id));
            Post::increment('views', 1);
            return view('post')->with(compact('post'));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());
            abort(500);
        }

    }
}
