<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Events\UserLogging;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\News\CreateCommentRequest;
use App\Models\News;
use App\Repository\NewsRepository;

class NewsController extends Controller
{
    protected $response;

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

    public function createCommentByUser (CreateCommentRequest $request, NewsRepository $newsRepository){
        try{
        $news = $newsRepository->createComment($request);
        $this->response = $news;
        event(new UserLogging($request->user(), 'create',$request->ip(), 'success', 'comment has been created',  News::class));
        return ResponseFormatter::success($this->response, 'Comment created successfully', 201,'single');
        } catch (\Exception $e){
            event(new UserLogging($request->user(), 'create',$request->ip(), 'failed', $e->getMessage(),  News::class));
            return ResponseFormatter::error(null, $e->getMessage(),400);
        }
    }


}
