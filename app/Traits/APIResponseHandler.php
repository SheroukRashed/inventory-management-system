<?php

namespace App\Traits;

trait APIResponseHandler
{
    /**
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'success'=> true, 
			'message' => $message, 
			'data' => $data
		], $code);
	}

	/**
	 * @param string|null $message
	 * @param mixed $code
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function errorResponse($message = null, $code)
	{
		return response()->json([
			'success'=> false,
			'message' => $message,
			'data' => null
		], $code);
	}
}