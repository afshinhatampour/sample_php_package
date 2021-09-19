<?php


namespace Mci\Behsa;


/**
 * Class Response
 * @package Afs\Src
 */
class Response
{

    /**
     * @param $data
     * @param $message
     * @param $fromCache
     * @param $code
     * @return array
     */
    public function getSuccessResponse($data, $message,$fromCache, $code)
    {
        return [
            'cache_data' => $fromCache,
            'data' => $data,
            'message' => $message,
            'code' => $code
        ];
    }

    /**
     * @param $response
     * @return array
     */
    public function getExceptionResponse($response)
    {
        return [
            'data' => null,
            'message' => $response->getReasonPhrase(),
            'code' => $response->getStatusCode(),
        ];
    }
}