<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static select(string $string)
 */
class Category extends Model
{
    use HasFactory;

    public function section(){
        return $this->belongsTo('App\Models\Section','section_id')->select('id','name');
    }
    public function parentcategory(){
        return $this->belongsTo('App\Models\Category','parent_id')->select('id','category_name');
    }
    public function subcategories(){
        return $this->belongsTo('App\Models\Category','parent_id')->where('status',1);
    }

    public static function  categoryDetails($url){
        $categoryDetails = Category::select('id','category_name','url','description')->with(['subcategories'=>
        function($query){
            $query->select('id','parent_id','category_name','url','description');
        }])->where('url',$url)->first()->toArray();

        $carIds = array();
        $catIds[] = $categoryDetails['id'];

        if($categoryDetails['parent_id']==0){
            $brandcrumbs = '';
        }
        else{
            $parentCategory = Category::select('category_name','url')->where('id',$categoryDetails['parent_id'])->
                first()->toArray();
            $brandcrumbs ='';
        }





        foreach ($categoryDetails['subcategories'] as $key => $subcat){
            $catIds[] = $subcat['id'];
        }
        $resp = array('catIds'=>$catIds, 'categoryDetails'=>$categoryDetails);
        return $resp;
    }
}
