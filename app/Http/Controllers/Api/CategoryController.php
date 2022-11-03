<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Finder\CategoryFinder;
use App\Libs\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $finder = new CategoryFinder();
        $finder->setAccessControl($this->getAccessControl());

        if(isset($request->group_by))
            $finder->setGroup($request->group_by);

        if(isset($request->order_by))
            $finder->setOrderBy($request->order_by);

        if(isset($request->order_type))
            $finder->setOrderType($request->order_type);

        if(isset($request->parent_id))
            $finder->setParentId($request->parent_id);
        else
            $finder->setParentId(null);

        if(isset($request->paginate)) {
            $finder->usePagination($request->paginate);

            if(isset($request->page))
                $finder->setPage($request->page);

            if(isset($request->per_page))
                $finder->setPerPage($request->per_page);
        }

        $paginator = $finder->get();
        $data = [];

        if(!$finder->isUsePagination()) {
            foreach ($paginator as $item)
                $data[] = $item;
        } else {
            foreach ($paginator->items() as $item)
                $data[] = $item;

            foreach($paginator->toArray() as $key => $value)
                if($key != 'data')
                    $data['pagination'][$key] = $value;
        }

        $response = new Response;
        return $response->json($data, "success get {$request->group_by} data");
    }

    public function indexPublic(Request $request)
    {
        $response = new Response;

        $fields = $request->all();
        $rules = [
            'group_by' => ['nullable', 'string', 'in:industries'],
        ];

        $validator = Validator::make($fields, $rules);
        if($validator->fails())
            return $this->response->json(null, $validator->errors(), 422);

        $finder = new CategoryFinder();

        if(isset($request->group_by))
            $finder->setGroup($request->group_by);

        if(isset($request->order_by))
            $finder->setOrderBy($request->order_by);

        if(isset($request->order_type))
            $finder->setOrderType($request->order_type);

        if(isset($request->parent_id))
            $finder->setParentId($request->parent_id);
        else
            $finder->setParentId(null);

        if(isset($request->paginate)) {
            $finder->usePagination($request->paginate);

            if(isset($request->page))
                $finder->setPage($request->page);

            if(isset($request->per_page))
                $finder->setPerPage($request->per_page);
        }

        $paginator = $finder->get();
        $data = [];

        if(!$finder->isUsePagination()) {
            foreach ($paginator as $item)
                $data[] = $item;
        } else {
            foreach ($paginator->items() as $item)
                $data[] = $item;

            foreach($paginator->toArray() as $key => $value)
                if($key != 'data')
                    $data['pagination'][$key] = $value;
        }

        $response = new Response;
        return $response->json($data, "success get {$request->group_by} data");
    }
}
