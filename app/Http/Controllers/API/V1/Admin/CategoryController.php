<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CreateCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Repository\CategoryRepository;

class CategoryController extends Controller
{
    protected $response;
    public function createCategory(CreateCategoryRequest $request, CategoryRepository $categoryRepository){
        try{
        $category = $categoryRepository->create($request);
        $this->response = $category;
        event(new UserLogging($request->user(), 'create',$request->ip(), 'success', 'category has been created',  Category::class));
        return ResponseFormatter::success($this->response, 'Category created successfully', 201,'single');
        } catch (\Exception $e){
            event(new UserLogging($request->user(), 'create',$request->ip(), 'failed', $e->getMessage(),  Category::class));
            return ResponseFormatter::error(null, $e->getMessage(),400);
        }
    }

    public function getAllCategory(CategoryRepository $categoryRepository){
        try{
        $categories = $categoryRepository->read();
        $this->response = $categories;

        return ResponseFormatter::success($this->response, 'Category retrieved successfully', 200,'collection');
        } catch (\Exception $e){
            return ResponseFormatter::error(null, $e->getMessage(),$e->getCode());
        }
    }

    public function getCategoryBySlug($slug,CategoryRepository $categoryRepository){
        try{
        $category = $categoryRepository->findBySlug($slug);
        $this->response = $category;
        return ResponseFormatter::success($this->response, 'Category retrieved successfully', 200,'single');
        } catch (\Exception $e){
            return ResponseFormatter::error(null, $e->getMessage(),$e->getCode());
        }
    }

    public function updateCategoryBySlug (UpdateCategoryRequest $request, CategoryRepository $categoryRepository,$slug){
        try{
        $category = $categoryRepository->update($request,$slug);
        $this->response = $category;
        event(new UserLogging($request->user(), 'update',$request->ip(), 'success', 'category has been updated',  Category::class));
        return ResponseFormatter::success($this->response, 'Category updated successfully', 200,'single');
        } catch (\Exception $e){
            event(new UserLogging($request->user(), 'update',$request->ip(), 'failed', $e->getMessage(),  Category::class));
            return ResponseFormatter::error(null, $e->getMessage(),$e->getCode());
        }
    }

    public function deleteCategoryBySlug ($slug,CategoryRepository $categoryRepository){
        try{
        $category = $categoryRepository->delete($slug);
        $this->response = $category;
        event(new UserLogging(request()->user(), 'delete',request()->ip(), 'success', 'category has been deleted',  Category::class));
        return ResponseFormatter::success($this->response, 'Category deleted successfully', 200,'single');
        } catch (\Exception $e){
            event(new UserLogging(request()->user(), 'delete',request()->ip(), 'failed', $e->getMessage(),  Category::class));
            return ResponseFormatter::error(null, $e->getMessage() ,$e->getCode());
        }
    }

}
