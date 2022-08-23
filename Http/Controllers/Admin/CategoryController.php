<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Section;
use Session;

class CategoryController extends Controller
{
    public function categories() {
       Session::put('page','categories');
       $categories =Category::with(['section','parentcategory'])->get()->toArray;

        return view('admin.categories.categories')->with(compact('categories'));
    }

    public function updateCategoryStatus(Request$request){
        if($request->ajax()){
            $data = $request->all();
            if($data['status']=="Active"){
                $status = 0;
            }
            else{
                $status = 1;
            }
            Category::where('id',$data['category_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status, 'category_id'=>$data['category_id']]);
        }
    }

    public function addEditCategory( Request $request,$id=null){
        Session::put('page','categories');
        if($id==""){

            $title = "Add Category";
            $category = new Category;
            $message = "added successfully";
        }
        else{
            $title = "Edit Category";
            @$category = find($id);
            $message = "update successfully";
        }
        if($request->isMethod('post')){
            $data = $request->all();

            $category->section_id = $data['section_id'];
            $category->parent_id = $data['parent_id'];
            $category->category_name = $data['category_name'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->meta_title = $data['meta_title'];
            $category->status =1;
            $category->save();
            return redirect('admin/categories')->with('success_message',$message);
        }


        $getSections = Section::get()->toArray();

        return view('admin.categories.add_edit_category')->with(compact('title','category','getSections'));

    }
}
