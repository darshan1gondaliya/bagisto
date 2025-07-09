<?php

namespace Wontonee\Delhivery\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Wontonee\Delhivery\Http\Requests\DelhiveryRequest;

class DelhiveryController extends Controller
{
    protected $_config;

    public function __construct()
    {
        $this->_config = request('_config');
    }

    public function index()
    {
        return view($this->_config['view']);
    }

    public function create()
    {
        return view($this->_config['view']);
    }

    public function store(DelhiveryRequest $request)
    {
        $data = $request->all();

        core()->setConfigData([
            'sales' => [
                'carriers' => [
                    'delhivery' => [
                        'active' => $data['active'],
                        'title' => $data['title'],
                        'api_url' => $data['api_url'],
                        'api_token' => $data['api_token'],
                        'client_name' => $data['client_name'],
                        'warehouse_pincode' => $data['warehouse_pincode'],
                        'account_id' => $data['account_id'],
                        'debug' => isset($data['debug']) ? true : false
                    ]
                ]
            ]
        ]);

        session()->flash('success', trans('delhivery::app.admin.system.delhivery.config-save'));

        return redirect()->route($this->_config['redirect']);
    }
}