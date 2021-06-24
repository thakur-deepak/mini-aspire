<?php

namespace App\Modules\Api\V1\Controllers;

use App\Http\Requests\Loan;
use App\Http\Requests\LoanApprove;
use App\Repositories\Loan\LoanInterface;
use Illuminate\Http\JsonResponse;

class LoanController extends ApiController
{
    private LoanInterface $loan;

    public function __construct(LoanInterface $loan)
    {
        $this->loan = $loan;
    }

    public function store(Loan $request): JsonResponse
    {
        if ($this->loan->checkUser($request->validated()['user_id'])) {
            return $this->sendJsonResponse([], __('messages.error.already_applied'), 401);
        }

        return $this->sendJsonResponse($this->loan->create($request->validated()), __('messages.success.created'), 201);
    }

    public function approve(LoanApprove $request): JsonResponse
    {
        return $this->sendJsonResponse($this->loan->update($request->validated()), __('messages.success.updated'), 201);
    }
}
