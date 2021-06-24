<?php

namespace App\Modules\Api\V1\Controllers;

use App\Http\Requests\Repayment;
use App\Repositories\Loan\LoanInterface;
use App\Repositories\Repayment\RepaymentInterface;
use Illuminate\Http\JsonResponse;

class RepaymentController extends ApiController
{
    private RepaymentInterface $repayment;

    public function __construct(RepaymentInterface $repayment)
    {
        $this->repayment = $repayment;
    }

    public function repayment(Repayment $request): JsonResponse
    {
        return $this->sendJsonResponse($this->repayment->create($request->validated()), __('messages.success.created'), 201);
    }
}
