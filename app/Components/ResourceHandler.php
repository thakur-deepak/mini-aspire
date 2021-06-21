<?php

namespace App\Components\V2;

use Illuminate\Http\Request;

class ResourceHandler
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validate(): bool
    {
        if (! $this->request) {
            return false;
        }

        return $this->exists();
    }

    private function exists(): bool
    {
        $all_resources = [
                            'products' => config('constants.model.product'),
                            'exhibitors' => config('constants.model.ldn_user'),
                            'trade-guests' => config('constants.model.ldn_user'),
                            'buyers' => config('constants.model.user'),
                            'users' => config('constants.model.user'),
                            'invite-trade-guests' => config('constants.model.invite-trade-guest'),
                            'connections' => config('constants.model.connections'),
                            'opportunities' => config('constants.model.opportunities'),
                        ];
        $request_resource = explode('/', $this->request->path());
        $table = $all_resources[$request_resource[2]];

        return (bool) $table::whereId($this->request->route('id'))->count();
    }
}
