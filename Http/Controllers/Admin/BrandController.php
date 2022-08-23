<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Session;

class BrandController extends Controller
{
    public function brands(){
        Session::put('page','brands');
        $brands= Brand::get()->toArray();
        return view('admin.brands.brands')->with(compact('brands'));
    }
    public function updateBrandStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            if($data['status']=="Active"){
                $status=0;
            }
            else{
                $status = 1;
            }
            Brand::where('id',$data['Brand_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status, 'Brand_id'=>$data['Brand_id']]);
        }
    }

    public function deleteBrand($id){
        Brand::where('id',$id)->delete();
        $message = "Delete Successfully";
        return redirect()->back()->with('success_message',$message);
    }

    public function addEditBrand(Request $request, $id=null){
        Brand::put('page','brands');
        if($id==""){
            $title = "Add Brand";
            $Brand = new Brand;
            $message ="Successfuly";
        }
        else{
            $title ="Edit";
            $Brand = Brand::find($id);
            $message ="Successfuly";
        }

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;


            $rules = [
                'Brand_name' => 'required|numeric',
            ];
            $customMessages= [
                'Brand_name.required' => 'Name is required',
                'Brand_name.regex' =>'',
            ];


            $this->validate($request,$rules,$customMessages);

            $Brand->name =$data['Brand_name'];
            $Brand->status =1;
            $Brand->save();

            return redirect('admin/brands')->with('success_message',$message);
        }

        return view('admin.brands.add_Brand')->with(compact('title','Brand'));

    }
}
