<?php

namespace App\Core\Formatters;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * Class CoreResponseFormatter
 *
 * @property integer $responseStatus
 * @property null | array $responseData
 * @property  $responseDataKey
 *
 * @package App\Core\Formatters
 */
abstract class CoreResponseFormatter
{
    /** RESPONSE CODES */
    public const SUCCESS_CODE = 200;
    public const NO_CONTENT_CODE = 204;
    public const SERVER_ERROR_CODE = 500;
    public const SERVICE_UNAVAILABLE_CODE = 503;
    public const VALIDATION_ERROR_CODE = 422;
    public const UNAUTHORIZED_CODE = 401;
    public const FORBIDDEN_CODE = 403;
    public const NOT_FOUND_CODE = 404;
    public const BAD_REQUEST_CODE = 400;
    public const LOCKED_CODE = 423;

    public $responseStatus = self::SUCCESS_CODE;

    /** Response data */
    protected $responseData = [];
    /**
     * @return mixed
     */
    abstract protected function prepareResponse();

    /**
     * Format response answer with prepared response data
     *
     * @param $responseStatus
     * @return mixed
     */
    public function formatAnswer($responseStatus = null)
    {
        if ($responseStatus) {
            $this->responseStatus = $responseStatus;
        }
        return $this->prepareResponse();
    }

    /**
     * Get prepared response data
     * @return array|null
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * Add additional data to current context data
     * @param $data
     * @param null $key
     * @return CoreResponseFormatter
     */
    public function addData($data, $key = null): CoreResponseFormatter
    {
        $data = $this->transformToArray($data);

        if (is_null($key)) {
            $this->responseData = array_merge($this->responseData, $data);

            return $this;
        }

        if (Arr::has($this->responseData, $key)) {
            $mergedData = array_merge(data_get($this->responseData, $key, []), $data);
            data_set($this->responseData, $key, $mergedData);

            return $this;
        }

        data_set($this->responseData, $key, $data);

        return $this;
    }

    /**
     * Add error to data
     * @param string | array $errorText
     * @param string $field
     * @return CoreResponseFormatter
     */
    public function addError(string | array $errorText, string $field = null)
    {
        $this->responseStatus = self::VALIDATION_ERROR_CODE;
        if (is_array($errorText)) {
            return $this->addData($errorText, 'errors');
        }

        return $this->addData(($field) ? [$field => [$errorText]] : [$errorText], 'errors');
    }

    /**
     * Clear response data
     */
    public function clearData()
    {
        $this->responseData = [];
        $this->responseStatus = self::SUCCESS_CODE;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->responseStatus;
    }

    /**
     * Transform input data to array
     * @param $data
     * @return array
     */
    private function transformToArray($data)
    {
        if (
            $data instanceof Collection ||
            $data instanceof Model ||
            $data instanceof LengthAwarePaginator ||
            $data instanceof SupportCollection
        ) {
            $data = $data->toArray();
        }

        if (
            $data instanceof ResourceCollection ||
            $data instanceof AnonymousResourceCollection ||
            $data instanceof JsonResource
        ) {
            $data = $data->response()->getData(true, 2048);
        }

        return $data;
    }


}
