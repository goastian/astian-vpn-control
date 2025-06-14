<?php
namespace App\Repositories\Security;

use Illuminate\Http\Request;
use App\Models\Security\KeyGenerator;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;

class KeyGeneratorRepository
{
    use JsonResponser;

    public $model;

    public function __construct(KeyGenerator $keyGenerator)
    {
        $this->model = $keyGenerator;
    }

    /**
     * Validate token
     * @param \Illuminate\Http\Request $request
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function validateToken(Request $request)
    {
        $token = $request->header('Authorization');

        if (!empty($token) && $this->model->decrypt($token)) {
            return $this->message(__("Credentials han been verified successfully"), 200);
        }

        throw new ReportError(__("Invalid credentials"), 401);
    }
}
