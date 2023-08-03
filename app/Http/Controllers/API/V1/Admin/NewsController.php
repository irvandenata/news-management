<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Events\UserLogging;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\CreateNewsRequest;
use App\Http\Requests\Admin\News\UpdateNewsRequest;
use App\Models\News;
use App\Repository\NewsRepository;

class NewsController extends Controller
{
    protected $response;
    public function createNews(CreateNewsRequest $request, NewsRepository $newsRepository){
        try{
        $news = $newsRepository->create($request);
        $this->response = $news;
        event(new UserLogging($request->user(), 'create',$request->ip(), 'success', 'news has been created',  News::class));
        return ResponseFormatter::success($this->response, 'News created successfully', 201,'single');
        } catch (\Exception $e){
            event(new UserLogging($request->user(), 'create',$request->ip(), 'failed', $e->getMessage(),  News::class));
            return ResponseFormatter::error(null, $e->getMessage(),400);
        }
    }

    public function getAllNews(NewsRepository $newsRepository){
        try{
        $news = $newsRepository->read();
        $this->response = $news;
        return ResponseFormatter::success($this->response, 'News retrieved successfully', 200,'paginate');
        } catch (\Exception $e){
            return ResponseFormatter::error(null, $e->getMessage(),$e->getCode());
        }
    }

    public function getNewsBySlug($slug,NewsRepository $newsRepository){
        try{
        $news = $newsRepository->findBySlug($slug);
        $this->response = $news;
        return ResponseFormatter::success($this->response, 'News retrieved successfully', 200,'single');
        } catch (\Exception $e){
            return ResponseFormatter::error(null, $e->getMessage(),$e->getCode());
        }
    }

    public function updateNewsBySlug (UpdateNewsRequest $request, NewsRepository $newsRepository,$slug){
        try{
        $news = $newsRepository->update($request,$slug);
        $this->response = $news;
        event(new UserLogging($request->user(), 'update',$request->ip(), 'success', 'news has been updated',  News::class));
        return ResponseFormatter::success($this->response, 'News updated successfully', 200,'single');
        } catch (\Exception $e){
            event(new UserLogging($request->user(), 'update',$request->ip(), 'failed', $e->getMessage(),  News::class));
            return ResponseFormatter::error(null, $e->getMessage(),$e->getCode());
        }
    }

    public function deleteNewsBySlug ($slug,NewsRepository $newsRepository){
        try{
        $news = $newsRepository->delete($slug);
        $this->response = $news;
        event(new UserLogging(request()->user(), 'delete',request()->ip(), 'success', 'news has been deleted',  News::class));
        return ResponseFormatter::success($this->response, 'News deleted successfully', 200,'single');
        } catch (\Exception $e){
            event(new UserLogging(request()->user(), 'delete',request()->ip(), 'failed', $e->getMessage(),  News::class));
            return ResponseFormatter::error(null, $e->getMessage() ,$e->getCode());
        }
    }

}
