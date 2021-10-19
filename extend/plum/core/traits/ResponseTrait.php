<?php

namespace plum\core\traits;

use plum\core\Trace;
use plum\enum\ResponseEnum;

trait ResponseTrait
{
    /**
     * 响应数据
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年09月30日 19:34
     */
    public function renderSuccess($data = [], $msg = 'SUCCESS')
    {
        $code = ResponseEnum::SUCCESS;
        $response = compact('code','data','msg');
        if(app()->isDebug()){
            $response['trace'] = (new Trace())->traceDebug();
        }
        return json($response);
    }

    /**
     * 响应分页数据
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年09月30日 19:34
     */
    public function renderPage($data = [], $msg = 'SUCCESS')
    {
        $code = ResponseEnum::SUCCESS;
        if(is_array($data)){
            $data = [
                'list'       => $data['data'],
                'pagination' => [
                    'page'  => $data['current_page'],
                    'size'  => $data['per_page'],
                    'total' => $data['total'],
                ]
            ];
        }else{
            $data = [
                'list'       => $data->getCollection()->toArray()?:[],
                'pagination' => [
                    'page'  => $data->currentPage(),
                    'size'  => $data->listRows(),
                    'total' => $data->total(),
                ]
            ];
        }
        $response = compact('code','data','msg');
        if(app()->isDebug()){
            $response['trace'] = (new Trace())->traceDebug();
        }
        return json($response);
    }
}
