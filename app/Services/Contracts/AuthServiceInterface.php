<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface AuthServiceInterface
{

    /**
     * 
     * @param Request $request
     */
    public function infoFromToken(Request $request);
}
