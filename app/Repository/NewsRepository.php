<?php

namespace App\Repository;

use App\Contracts\CRUDRepositoryInterface;
use App\Helpers\GlobalFunction;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class NewsRepository implements CRUDRepositoryInterface
{
    protected $news;
    protected $model;
    public function __construct(News $news)
    {
        $this->news = $news;
        $this->model = News::query();
    }

    public function _findBySlug($slug)
    {
        $news = $this->model->where('slug', $slug)->first();
        if (!$news){
           throw new \Exception('News not fond',404);
        }
        return $news;
    }
    public function create(Request $request)
    {

        $news = new News();
        $news->title = $request->input('title');
        $news->category_id = $request->input('category_id');
        $news->slug = GlobalFunction::makeSlug($this->model,$request->input('title'));
        $news->content = $request->input('content');
        $news->author_id = auth()->user()->id;
        if($request->hasFile('image')){
            $news->image = GlobalFunction::storeSingleImage($request->file('image'),'news');
        }
        $news->save();
        return new NewsResource($news);
    }

    public function read()
    {
       $cache = Redis::get('news_fetch');
        if(request()->search || request()->page){
            $this->model->where('title','like','%'.request()->search.'%')->orWhere('content','like','%'.request()->search.'%');
            $news = NewsResource::collection($this->model->with('category')->paginate(10))->response()->getData(true);
        }
        else if ($cache){
            $news = json_decode($cache,true);
        }
        else{
            $news = NewsResource::collection($this->model->with('category')->paginate(10))->response()->getData(true);
            Redis::set('news_fetch',json_encode($news));
        }
        return $news;
    }

    public function findBySlug($slug)
    {
        $cache = Redis::get('news_fetch_'.$slug);
        if($cache){
            $news = json_decode($cache,true);
        }
        else{
            $news =  new NewsResource($this->_findBySlug($slug));
            Redis::set('news_fetch_'.$slug,json_encode($news));
        }
        return $news;
    }

    public function update(Request $request,$slug)
    {
        Redis::del('news_fetch');
        Redis::del('news_fetch_'.$slug);
        $news = $this->_findBySlug($slug);
        $news->title = $request->input('title');
        $news->category_id = $request->input('category_id');
        $news->slug = GlobalFunction::makeSlug($this->model,$request->input('title'));
        $news->content = $request->input('content');
        $news->save();
        return new NewsResource($news);
    }

    public function delete ($slug){
       Redis::del('news_fetch');
       Redis::del('news_fetch_'.$slug);
       $news = $this->_findBySlug($slug);
         if (!$news){
              throw new \Exception('News not found',404);
         }
        $news->delete();
    }

    public function createComment (Request $request){
        Redis::del('news_fetch_'.$request->news_slug);
        $news = $this->_findBySlug($request->news_slug);
        $news->comments()->create([
            'user_id' => auth()->user()->id,
            'content' => $request->content
        ]);
        $news = new NewsResource($news);
        Redis::set('news_fetch_'.$request->news_slug,json_encode($news));
        return $news;
    }

}
