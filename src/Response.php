<?php


namespace Afs\Src;


/**
 * Class Response
 * @package Afs\Src
 */
class Response
{

    /**
     * @param $data
     * @param $message
     * @param $code
     * @return array
     */
    public function getSuccessResponse($data, $message, $code)
    {
        return [
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