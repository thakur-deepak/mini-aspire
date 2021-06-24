<?php

namespace App\Modules\Api\V1\Controllers;

use App\Formatter\OutputInterface;
use App\Http\Requests\User\Register;
use App\Repositories\User\UserInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UserController extends ApiController
{
    private UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function signup(Register $request): JsonResponse
    {
        return $this->sendJsonResponse($this->user->create($request->validated()), __('messages.success.created'), 201);
    }



    private function getRegistrationStep($id)
    {
        $registration_step = $this->input['registration_step'] ?? null;
        $saved_registration_step = $this->user->getRegistrationStepById($id)['registration_step'];
        if ($registration_step != ($saved_registration_step + 1)) {
            unset($this->input['registration_step']);
        }
        if (empty($registration_step) || (! is_numeric($registration_step))) {
            $this->input['registration_step'] = $this->user->getRegistrationStepById($id)['registration_step'] + 1;
            $registration_step = $this->input['registration_step'];
        }
        return $registration_step;
    }

    private function checkRegistrationStep($id, $role_id)
    {
        $registration_step = $this->getRegistrationStep($id);
        if (
            ($role_id == Config::get('constants.USER_ROLE.EMPLOYER')
            && $registration_step > Config::get('constants.EMPLOYER_PROFILE_STEP.PASSWORD'))
        ) {
            $this->showBadRequestError(['id' => $id], __('messages.error.unprocessable_entity'), 422);
        }
        if (
            ($role_id == Config::get('constants.USER_ROLE.PROFESSIONAL')
            && $registration_step > Config::get('constants.PROFILE_STEP.PASSWORD'))
        ) {
            unset($this->input['registration_step']);
        }
        return $registration_step;
    }

    public function update(Request $request, $id)
    {
        $this->input = $request->all();
        $role_id = $this->user->getRoleId($id)['role_id'];
        $registration_step = $this->checkRegistrationStep($id, $role_id);

        switch ($role_id) {
            case Config::get('constants.USER_ROLE.PROFESSIONAL'):
                $static_data = UserValidation::getValidationRulesAndSuccessMessage($registration_step, $id);
                break;

            case Config::get('constants.USER_ROLE.EMPLOYER'):
                $static_data = UserValidation::getValidationRulesAndSuccessMessageForEmployer($registration_step, $id);
                break;

            default:
                $this->showBadRequestError(['id' => $id], __('messages.user.not_found'), 404);
                break;
        }

        $this->validateRequestInputs($static_data['validation_rules']);

        if (
            $registration_step == Config::get('constants.REGISTRATION_STEP.EXPERIENCE')
            && ($role_id == Config::get('constants.USER_ROLE.PROFESSIONAL'))
        ) {
            $this->addUserReference($id);
        }

        if (isset($this->input['email'])) {
            $this->input['email'] = strtolower($this->input['email']);
        }
        $this->input['xero_contact_id'] = $this->user->getXeroContactId($id)['xero_contact_id'];
        $this->input = (new XeroAPI())->addContact($this->input, $registration_step, $role_id);
        $this->input = UserComponent::resetRemeberToken($this->input, $registration_step, $role_id);

        $this->updateUser($id);

        UserComponent::triggerWelcomeEmail($id, $role_id, $registration_step);

        $response_data = $this->createJsonResponse(['id' => $id], $static_data['success_message']);
        return new JsonResponse($response_data, 200);
    }

    private function updateUser($id)
    {
        try {
            $this->user->update($id, $this->input);
        } catch (QueryException $exception) {
            $this->showBadRequestError([], $exception->getMessage(), 422);
        } catch (ModelNotFoundException $exception) {
            $this->showBadRequestError([], $exception->getMessage(), 404);
        }
        return true;
    }

    private function validateRequestInputs($rules)
    {
        $rules['registration_step'] = 'numeric|max:' . Config::get('constants.PROFILE_STEP.PASSWORD');
        $validator = Validator::make($this->input, $rules, __('messages.validation'));

        if ($validator->fails()) {
            $this->showBadRequestError($validator->errors(), __('messages.error.validation'), 400);
        }

        $this->input = UserComponent::removeExtraInputs($this->input, array_keys($rules));
        return true;
    }

    private function addUserReference($id)
    {
        $errors = [];
        DB::beginTransaction();
        $this->reference->deleteByUserId($id);
        foreach (json_decode($this->input['references'], true) as $key => $reference) {
            $reference['user_id'] = $id;
            if ($reference['first_name'] || $reference['studio_name'] || $reference['email']) {
                $saved = $this->reference->create($reference);
                if ($saved->hasErrors()) {
                    $errors[$key] = $saved->getErrors();
                    break;
                }
            }
        }
        DB::commit();
        if (! empty($errors)) {
            $this->showBadRequestError($errors, __('messages.error.validation'), 400);
        }
        unset($this->input['references']);
        return true;
    }

    public function verifyEmail(Request $request)
    {
        $this->input = $request->all();
        if (empty($this->input['token'])) {
            $this->updateValidationErrors(['token' => __('messages.user.token_required')]);
            return $this->showBadRequest();
        }

        $user = $this->user->findUserByRememberToken($this->input['token']);

        if (empty($user)) {
            $this->setForbidden(['error' => __('messages.user.token_expired')]);
            return $this->showBadRequest();
        }

        $access_token = new AccessTokenHandler($request, $user->access_token);
        $this->user->update($user->id, ['access_token' => $access_token->get()]);
        $latest_token = $access_token->getToken() ?? null;
        $user_data = UserComponent::formatData($user, $latest_token);
        $response_data = $this->createJsonResponse($user_data, __('messages.user.email_verification_success'));
        return new JsonResponse($response_data, 200);
    }

    public function wellbeingListing(Request $request)
    {
        $this->input = $request->all();
        $validation_rules = [
            'limit' => 'numeric|min:1',
            'offset' => 'numeric',
            'active' => 'nullable|boolean',
        ];
        $this->validateRequestInputs($validation_rules);
        $limit = $this->input['limit'] ?? Config::get('constants.USER.LIMIT');
        $offset = $this->input['offset'] ?? Config::get('constants.USER.OFFSET');

        $user_data   = $this->getListing($limit, $offset);
        $response_data = $this->createJsonResponse($user_data, __('messages.success.listing'));
        return new JsonResponse($response_data, 200);
    }

    private function getListing($limit, $offset)
    {
        if (! empty($this->input['active']) && $this->input['active']) {
            return $this->user->getActiveListing($limit, $offset);
        }
        return $this->user->getInactiveListing($limit, $offset);
    }

    public function details($id)
    {
        $this->input['id'] = $id;
        $validator = Validator::make(['user_id' => $id], ['user_id' => 'numeric|exists:users,id']);
        if ($validator->fails()) {
            $this->showBadRequestError(
                [
                'error' => __('messages.user.not_found'),
                ],
                __('messages.error.not_found'),
                404
            );
        }
        $user_data = $this->user->getUserDetail($id);
        $user_component = new UserComponent();
        $user_data->insurance =  $user_component->setDocumentsUrls(json_decode($user_data->insurance));
        $user_data->qualification =  $user_component->setDocumentsUrls(json_decode($user_data->qualification));
        $user_data =  $user_component->setPaymentDetails($user_data);
        $response_data = $this->createJsonResponse($user_data, __('messages.success.listing'));
        return new JsonResponse($response_data, 200);
    }

    public function verify($id)
    {
        Artisan::queue('email:send', ['user' => $id, '-t' => Config::get('constants.EMAIL_TYPE.VERIFY')]);
        $user_data = ['email_verified_at' => date(Config::get('constants.DEFAULT_DATETIME_FORMAT'))];
        $this->user->update($id, $user_data);
        $user_data['id'] = $id;
        $response_data = $this->createJsonResponse($user_data, __('messages.success.queued'));
        return new JsonResponse($response_data, 200);
    }

    public function professionalsList()
    {
        $list = $this->user->getProfessionalsList();
        $response_data = $this->createJsonResponse($list, __('messages.success.listing'));
        return new JsonResponse($response_data, 200);
    }
}
