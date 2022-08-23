<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
//use Illuminate\Support\Facades\Route;
use App\Models\Category;

class ProductsController extends Controller
{
    public function listing(){
        $url = Route::getFacadeRoot()->current()->uri();

        $categoryCount = Category::where(['url'=>$url,'status'=>1])->count();
        if($categoryCount>0){
            echo "Category exits"; die;
        }
        else{
            abort(404);
        }

    }
}
