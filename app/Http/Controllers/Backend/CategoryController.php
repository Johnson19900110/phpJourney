<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
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
            if($request->has('rows')) {
                $rows = $request->input('rows')+0;
                $data = Category::paginate($rows);
            }else {
                $data = Category::get();
            }

            return response()->json(array(
                'status' => 0,
                'data' => $data
            ));
        }catch (Exception $e) {
            return response()->json(array(
                'status' => 1,
                'message' => '获取失败'
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
        // 新增分类
        $this->validate($request, array(
            'name' => 'required',
            'nickname' => 'required',
            'parent' => 'required'
        ));

        try {
            Category::create(array(
                'name' => $request->input('name'),
                'nickname' => $request->input('nickname'),
                'description' => $request->input('description'),
                'parent' => $request->input('parent')+0,
            ));

            return response()->json(array(
                'status' => 0,
                'message' => '新增成功'
            ));
        }catch (\Exception $e) {
            return response()->json(array(
                'status' => 1,
                'message' => '新增失败'
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
        try {
            $category = Category::find($id);

            return response()->json(array(
                'status' => 0,
                'category' => $category
            ));
        }catch(\Exception $e) {
            return response()->json(array(
                'status' => 1,
                'message' => '数据获取失败'
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
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->nickname = $request->nickname;
        $category->parent = $request->parent;
        if( !$category->description ) {
            $category->description = $request->description;
        }
        try {
            $category->save();

            return response()->json(array(
                'status' => 0,
                'message' => '数据更新成功'
            ));
        }catch (\Exception $e) {
            return response()->json(array(
                'status' => 1,
                'message' => '数据更新失败'
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
        if( !$request->has('Ids') ) {
            $Ids = [$id];
        }else {
            $Ids = $request->input('Ids');
        }

        try {
            Category::destroy($Ids);

            return response()->json(array(
                'status' => 0,
                'message' => '删除成功'
            ));
        }catch (\Exception $e) {
            return response()->json(array(
                'status' => 2,
                'message' => '删除失败'
            ));
        }
    }
}
