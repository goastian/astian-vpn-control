<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Security\KeyGenerator;
use Elyerr\ApiResponse\Exceptions\ReportError;

class KeyGeneratorController extends Controller
{
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
        Log::info($token);
        if (!empty($token) && $keyGenerator->verifyAndDecryptToken($token)) { 
            return $this->message(__("Credentials han been verified successfully"), 200);
        }

        throw new ReportError(__("Invalid credentials"), 401);
    }
}
