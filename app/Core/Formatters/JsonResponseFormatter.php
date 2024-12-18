<?php

namespace App\Core\Formatters;

/**
 * Class JsonResponseFormatter based on [[\App\Core\Formatters\CoreResponseFormatter]]
 *
 * Working with data instance of
 *      Illuminate\Database\Eloquent\Collection,
 *      Illuminate\Support\Collection,
 *      Illuminate\Pagination\LengthAwarePaginator,
 *      Illuminate\Support\Collection,
 *      Array
 *
 * The following is an example of using JsonResponseFormatter
 * ```php
 *      $data1 = collect([1 => 'testValue']);
 *      $data2 = [1 => 'testValue2'];
 *      $data3 = ['1' => 'testValue3'];
 *
 *      $formatter = $this->formatter
 *          ->addData($data1)
 *          ->addData($data2, 'settings.test')
 *          ->addData($data2, 'test');
 *
 *      //$this->>formatter->clearData();// you may clear current data from formatter context
 *
 *      return response()->json(
 *         $formatter->formatAnswer($this->formatter::SUCCESS_CODE)
 *      );
 * ```
 * Response result
 *  {
 *      "status": 200,
 *      "data": {
 *          "0": "testValue",
 *          "settings": {
 *              "test": {
 *                  "1": "testValue2"
 *              }
 *          },
 *          "test": {
 *              "1": "testValue2"
 *              }
 *          }
 *  }
 *
 * @package App\Core\Formatters
 */
class JsonResponseFormatter extends CoreResponseFormatter
{
    /**
     * Prepare response data for frontend
     * @return array|mixed
     */
    protected function prepareResponse()
    {
        $response = ['status' => $this->responseStatus];
        return ($this->responseStatus == self::VALIDATION_ERROR_CODE) ?
            array_merge($response, $this->getResponseData()) :
            array_merge($response, ['data' => $this->getResponseData()]);

    }
}
