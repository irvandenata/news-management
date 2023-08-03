<?php

namespace App\Repository;

use App\Contracts\CRUDRepositoryInterface;
use App\Helpers\GlobalFunction;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepository implements CRUDRepositoryInterface
{
    protected $category;
    protected $model;
    public function __construct(Category $category)
    {
        $this->category = $category;
        $this->model = Category::query();
    }

    public function create(Request $request)
    {
        $category = new Category();
        $category->name = $request->input('name');
        $category->slug = GlobalFunction::makeSlug($this->model,$request->input('name'));
        $category->save();
        return new CategoryResource($category);
    }

    public function read()
    {
        $category = CategoryResource::collection($this->model->with('news')->paginate(10));
        return $category;
    }

    public function findBySlug($slug)
    {
        $category = $this->model->where('slug', $slug)->first();
        if (!$category){
           throw new \Exception('Category not fond',404);
        }
        return $category;
    }

    public function update(Request $request,$slug)
    {
        $category = $this->findBySlug($slug);
        if (!$category){
           throw new \Exception('Category not fond',404);
        }
        $category->name = $request->input('name');
        $category->slug = GlobalFunction::makeSlug($this->model,$request->input('name'));
        $category->save();
        return new CategoryResource($category);
    }

    public function delete ($slug){
       $category = $this->findBySlug($slug);
         if (!$category){
              throw new \Exception('Category not found',404);
         }
        $category->delete();
    }

}
