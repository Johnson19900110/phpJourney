<?php

namespace App\Http\Controllers\App;

use App\Http\Resources\UserResource;
use App\Mail\UserLogin;
use App\Notifications\InvoicePaid;
use App\Post;
use App\Tag;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    public function redirect()
    {
        $query = http_build_query([
            'client_id' => 3,
            'redirect_uri' => 'http://phpjourney.test/auth/callback',
            'response_type' => 'code',
            'scope' => '',
        ]);

        return redirect('http://permission.test/oauth/authorize?'.$query);
    }

    public function authCallback(Request $request){
        $http = new Client();

        $response = $http->post('http://permission.test/oauth', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => '3',  // your client id
                'client_secret' => '3j9yagQtIcgGLuAhe6YkN6WrEM6ufQP25Ll3Hapu',   // your client secret
                'redirect_uri' => 'http://phpjourney.test/auth/callback',
                'code' => $request->code,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }


    protected function mysqlLink(Array $data)
    {
        if(empty($data)) {
            return 'Params is empty';
        }
        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        $client->connect('0.0.0.0', 9508, 10) or die("Connect Pool Failure !");

        $client->send(json_encode($data));
        //Blocking for wait result

        $data = $client->recv();

        //$client->close();

        return unserialize($data);
    }

    public function test()
    {
        Cache::tags(['people', 'artists'])->put('John', 'John', 3);
        Cache::tags(['people', 'authors'])->put('Anne', 'Anne', 3);

        dd(Cache::tags(['people', 'artists'])->get('John'));
        $user = User::find(1);

//        $user->notify(new InvoicePaid());

//        $res = Mail::to($user)->send(new UserLogin($user));

        /*Mail::send('测试邮件', [], function($message) {
            $message->to('18301790572@qq.com')->subject('测试邮件');;
        });*/

        $user->posts;
        return new UserResource($user);
        $params = array(
            'table' => 'users',
            'type' => 'get'
        );

        $data = $this->mysqlLink($params);

        dd($data);
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
            dd($exception->getMessage());
            Log::info(__CLASS__ . '->' . __FUNCTION__ . ' Line:' . $exception->getLine() . ' ' . $exception->getMessage());
            abort(500);
        }
    }

    public function search(Request $request)
    {
        $q = $request->get('q', false);

        $posts = [];
        if($q !== false) {
            $posts = Post::search($q)->paginate();
        }

        return view('index', compact('posts', 'q'));
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
