<?php
namespace App\Http\Controllers\Api\V1\Gateway;

use Illuminate\Http\Request;
use App\Models\Server\Device;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Security\KeyGenerator;
use App\Http\Controllers\ApiController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class GatewayController extends ApiController
{

    public function __construct()
    {
        $this->middleware('server')->only('checkAuth');
    }

    /**
     * checkCredentials
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Security\KeyGenerator $keyGenerator
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function checkCredentials(Request $request, KeyGenerator $keyGenerator)
    {
        $token = $request->header('Authorization');
        if (!empty($token) && $keyGenerator->decrypt($token)) {
            return $this->message(__("Credentials han been verified successfully"), 200);
        }

        throw new ReportError(__("Invalid credentials"), 401);
    }

    /**
     * check auth
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Device $device
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function checkAuth(Request $request, Device $device)
    {
        $device = $device->find($request->header('X-Device-ID') ?? null);
        Log::info("request : {$request->header('Authorization')}  |   id {$request->header('X-Device-ID')}");
        if ($device && $device->id) {
            return response("", 200);
        }

        throw new ReportError("Unauthorized", 401);
    }
}
