<?php

namespace App\Http\Controllers\App;

use App\Post;
use App\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected function mysqlLink(Array $data)
    {
        if(empty($data)) {
            return 'Params is empty';
        }
        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        $client->connect('0.0.0.0', 9508, 10) or die("Connect Pool Failure !");

        $client->send($data);
        //Blocking for wait result

        $data = $client->recv();

        $client->close();

        return $data;
    }

    public function test()
    {
        opcache_reset();
        try
        {
            $params = array(
                'table' => 'users',
                'type' => 'first'
            );
dd($params);
            $data = $this->mysqlLink($params);

            dd($data);
        }catch (ModelNotFoundException $exception) {
            dd($exception);
        }catch (\ErrorException $exception) {
            var_dump($exception->getMessage());
        }finally{
            echo 'Hi';
        }
    }
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

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function post(int $id)
    {
        try{
            $post = Post::find(intval($id));

            if(empty($post)) {
                throw new \Exception('很抱歉，页面找不到了', 404);
            }

            Post::where('id', intval($id))->increment('views', 1);
            return view('post')->with(compact('post'));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());
            abort($exception->getCode(), $exception->getMessage());
        }

    }

    /**
     * 按分类展示文章
     * @param $tags_flag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tags($tags_flag)
    {
        try {
            $posts = Tag::where('tags_flag', $tags_flag)->first()->posts()->paginate(15);

            if(empty($posts->items())) {
                throw new \Exception('很抱歉，页面找不到了', 404);
            }

            return view('index', array(
                'posts' => $posts
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());
            abort($exception->getCode(), $exception->getMessage());
        }

    }

    public function categories($category_id)
    {
        try {
            $posts = Post::where('category_id', $category_id + 0)->paginate(15);

            if(empty($posts->items())) {
                throw new \Exception('抱歉，页面找不到了', 404);
            }

            return view('index', array(
                'posts' => $posts
            ));
        }catch (\Exception $exception) {
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());

            abort($exception->getCode(), $exception->getMessage());
        }

    }
}
