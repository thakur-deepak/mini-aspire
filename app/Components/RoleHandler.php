<?php

namespace App\Components\V2;

use Illuminate\Http\Request;

class RoleHandler
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

        return $this->validateAccess();
    }

    private function validateAccess(): bool
    {
        $is_admin_access = $this->isAdmin();
        if ($is_admin_access) {
            return true;
        }
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

        if (in_array($table, config('constants.user_models'))) {
            return (bool) ($this->request->user->id == $this->request->route('id'));
        }

        if ($table == config('constants.model.connections')) {
            return (bool) $table::whereId($this->request->route('id'))
            ->where(
                function ($q) {
                    $q->where(['sender_id' => $this->request->user->id])
                        ->orWhere(['receiver_id' => $this->request->user->id]);
                }
            )
            ->count();
        }
        return (bool) $table::whereId($this->request->route('id'))->whereUserId($this->request->user->id)->count();
    }

    private function isAdmin(): bool
    {
        return $this->request->user->role_id == config('constants.user_roles.admin');
    }
}
