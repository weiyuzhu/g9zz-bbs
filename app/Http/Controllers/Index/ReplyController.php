<?php

namespace App\Http\Controllers\Index;

use App\Http\Requests\Index\ReplyRequest;
use App\Services\Index\ReplyService;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ReplyController extends Controller
{
    protected $replyService;
    protected $request;

    public function __construct(ReplyService $replyService,Request $request)
    {
        $this->replyService = $replyService;
        $this->request = $request;
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paginate = $this->replyService->paginate();
        $resource = new Collection($paginate,new ReplyTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginate));
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param ReplyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ReplyRequest $request)
    {
        $result =  $this->replyService->store($request);
        $resource = new Item($result,new ReplyTransformer());
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param $hid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($hid)
    {
        $result = $this->replyService->hidFind($hid);
        $resource = new Item($result,new ReplyTransformer());
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param ReplyRequest $request
     * @param $hid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ReplyRequest $request, $hid)
    {
        $this->replyService->hidUpdate($request,$hid);
        $resource = new Item($this->replyService->hidFind($hid),new ReplyTransformer());
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param $hid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($hid)
    {
        $result = $this->replyService->hidDelete($hid);
        if ($result) return $this->response();
    }

    /**
     * @param $hid
     * @return \Illuminate\Http\JsonResponse
     */
    public function block($hid)
    {
        $result = $this->replyService->blockReply($hid);
        $resource = new Item($result,new ReplyTransformer());
        $this->setData($resource);
        return $this->response();
    }
}
