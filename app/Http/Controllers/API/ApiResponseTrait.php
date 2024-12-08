<?php

namespace App\Http\Controllers\API;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

trait ApiResponseTrait
{

    /**
     * Get http status code error list or single error code 
     * 
     * @var int
     */
    public static function getErrorCodeList($errorCode = null)
    {
        $list = [
            100 => 'CONTINUE',
            101 => 'SWITCHING_PROTOCOLS',
            102 => 'PROCESSING',
            103 => 'EARLY_HINTS',
            200 => 'OK',
            201 => 'CREATED',
            202 => 'ACCEPTED',
            203 => 'NON_AUTHORITATIVE_INFORMATION',
            204 => 'NO_CONTENT',
            205 => 'RESET_CONTENT',
            206 => 'PARTIAL_CONTENT',
            207 => 'MULTI_STATUS',
            208 => 'ALREADY_REPORTED',
            226 => 'IM_USED',
            300 => 'MULTIPLE_CHOICES',
            301 => 'MOVED_PERMANENTLY',
            302 => 'FOUND',
            303 => 'SEE_OTHER',
            304 => 'NOT_MODIFIED',
            305 => 'USE_PROXY',
            306 => 'RESERVED',
            307 => 'TEMPORARY_REDIRECT',
            308 => 'PERMANENT_REDIRECT',
            400 => 'BAD_REQUEST',
            401 => 'UNAUTHORIZED',
            402 => 'PAYMENT_REQUIRED',
            403 => 'FORBIDDEN',
            404 => 'NOT_FOUND',
            405 => 'METHOD_NOT_ALLOWED',
            406 => 'NOT_ACCEPTABLE',
            407 => 'PROXY_AUTHENTICATION_REQUIRED',
            408 => 'REQUEST_TIMEOUT',
            409 => 'CONFLICT',
            410 => 'GONE',
            411 => 'LENGTH_REQUIRED',
            412 => 'PRECONDITION_FAILED',
            413 => 'PAYLOAD_TOO_LARGE',
            414 => 'URI_TOO_LONG',
            415 => 'UNSUPPORTED_MEDIA_TYPE',
            416 => 'RANGE_NOT_SATISFIABLE',
            417 => 'EXPECTATION_FAILED',
            418 => 'I_AM_A_TEAPOT',
            421 => 'MISDIRECTED_REQUEST',
            422 => 'UNPROCESSABLE_ENTITY',
            423 => 'LOCKED',
            424 => 'FAILED_DEPENDENCY',
            425 => 'TOO_EARLY',
            426 => 'UPGRADE_REQUIRED',
            428 => 'PRECONDITION_REQUIRED',
            429 => 'TOO_MANY_REQUESTS',
            431 => 'REQUEST_HEADER_FIELDS_TOO_LARGE',
            451 => 'UNAVAILABLE_FOR_LEGAL_REASONS',
            500 => 'INTERNAL_SERVER_ERROR',
            501 => 'NOT_IMPLEMENTED',
            502 => 'BAD_GATEWAY',
            503 => 'SERVICE_UNAVAILABLE',
            504 => 'GATEWAY_TIMEOUT',
            505 => 'HTTP_VERSION_NOT_SUPPORTED',
            506 => 'VARIANT_ALSO_NEGOTIATES',
            507 => 'INSUFFICIENT_STORAGE',
            508 => 'LOOP_DETECTED',
            510 => 'NOT_EXTENDED',
            511 => 'NETWORK_AUTHENTICATION_REQUIRED',
        ];

        return $errorCode ? $list[$errorCode] : $list;
    }

    /**
     * Getter for statusCode
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Getter for errorCode
     *
     * @return mixed
     */
    public function getErrorCode()
    {
        return self::getErrorCodeList($this->statusCode);
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Respond with a Json Resource Item
     * 
     */
    protected function respondWithItem($item, mixed $resource)
    {
        return $resource::make($item);
    }

    /**
     * Respond with a Json Resource Collection
     * 
     */
    protected function respondWithCollection(Collection $collection, mixed $resource)
    {
        return $resource::collection($collection);
    }

    /**
     * Respond with a Json Resource Collection Paginated
     * 
     */
    protected function respondWithCollectionPaginated(mixed $collection, mixed $resource)
    {
        return $resource::collection($collection);
    }

    /**
     * Respond with a simple array
     * 
     */
    protected function respondWithArray($array, array $headers = [])
    {
        return Response::json($array, $this->statusCode, $headers);
    }

    /**
     * Respond with empty content
     * 
     */
    protected function respondWithNoContent()
    {
        return $this->setStatusCode(204)
                    ->respondWithArray(null);
    }

    /**
     * Respond with empty collection
     * 
     */
    protected function respondWithEmptyCollection()
    {
        return $this->setStatusCode(200)
                    ->respondWithArray([
                        "data" => [],
                        "meta" => []
                    ]);
    }

    /**
     * Respond with empty item
     * 
     */
    protected function respondItemEmpty()
    {
        return $this->setStatusCode(200)
                    ->respondWithArray([
                        "data" => null,
                    ]);
    }

    /**
     * Respond with an error
     * 
     */
    protected function respondWithError($message, $statusCode, $code = null)
    {
        return $this->setStatusCode($statusCode)
                    ->respondWithArray([
                        'code' => $code ? $code : $this->getErrorCode(),
                        'status' => $this->statusCode,
                        'message' => $message,
                    ]);
    }

    /**
     * Respond with a message
     * 
     */
    protected function respondWithMessage(array $messages, array $headers = [])
    {
        return $this->respondWithArray($messages, $headers);
    }

    /**
     * Generates a Response with a 201 HTTP header and a given message.
     *
     * @return  Response
     */
    public function respondCreated($message = ['OK'])
    {
        return $this->setStatusCode(201)
                    ->respondWithMessage($message);
    }

    /**
     * Generates a Response with a 201 HTTP header and a given message.
     *
     * @return  Response
     */
    public function respondDeleted($message = ['The server successfully processed the request, but is not returning any content.'])
    {
        return $this->setStatusCode(204)
                    ->respondWithMessage($message);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @return  Response
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->respondWithError($message, 403);
    }

    /**
     * Generates a Response with a 5xx HTTP header and a given message.
     *
     * @return  Response
     */
    public function errorInternalError($message = 'Internal Error', $statusCode = 500)
    {
        return $this->respondWithError($message, $statusCode);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @return  Response
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->respondWithError($message, 404);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @return  Response
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->respondWithError($message, 401);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @return  Response
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->respondWithError($message, 422);
    }

    /**
     * Just respond with json
     *
     * @return  Response
     */
    public function response(array $data, $headers = [], $statusCode = 200)
    {
        return $this->setStatusCode($statusCode)
                    ->respondWithArray($data, $headers);
    }

    /**
     * Get the authenticated user id
     *
     * @return mixed
     */
    public static function getAuthUserId($guard = null) {

        return Auth::guard($guard)->check() ? Auth::guard($guard)->user()->id : null;
    }


}