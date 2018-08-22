<?php

namespace Tests\Concerns;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use FR3D\SwaggerAssertions\SchemaManager;
use FR3D\SwaggerAssertions\PhpUnit\AssertsTrait;

trait InteractsWithSwagger
{
    use AssertsTrait;

    protected $schemaManager;

    protected function initializeSwaggerManager()
    {
        $path = 'file://'.base_path('api-docs/v1/public.json');
        $this->schemaManager = SchemaManager::fromUri($path);
    }

    protected function assertSchemaMatch(
        string $path,
        array $data,
        string $method,
        int $expectedHttpCode = Response::HTTP_OK,
        string $query = ''
    ) {
        if (!$this->schemaManager) {
            $this->initializeSwaggerManager();
        }

        $this->assertRequestBodyMatch(
            $this->toJsonObject($data),
            $this->schemaManager,
            $path,
            $method
        );

        $this->assertResponseBodyMatch(
            json_decode($this->response->getContent()),
            $this->schemaManager,
            $path,
            $method,
            $expectedHttpCode
        );

        $this->assertEquals($expectedHttpCode, $this->response->getStatusCode());
    }

    /**
     * Assert GET request and response body matches swagger specification
     *
     * @param $path
     * @param int $expectedHttpCode
     */
    protected function assertGetSchemaMatch(
        string $path,
        array $queryData = [],
        int $expectedHttpCode = Response::HTTP_OK
    ) {
        $queryString = empty($queryData) ? '' : http_build_query($queryData);
        $this->response = $this->get($path.'?'.$queryString);
        $this->assertSchemaMatch($path, [], 'GET', $expectedHttpCode, $queryString);
    }

    /**
     * Assert POST request and response body matches swagger specification
     *
     * @param string $path
     * @param array $data
     * @param int $expectedHttpCode
     */
    protected function assertPostSchemaMatch(
        string $path,
        array $data,
        int $expectedHttpCode = Response::HTTP_CREATED,
        array $queryData = []
    ) {
        $queryString = empty($queryData) ? '' : http_build_query($queryData);
        if ($queryString !== '') {
            $this->get($path . '?' . $queryString);
        }
        $this->response = $this->postJson($path, $data);
        $this->assertSchemaMatch($path, $data, 'POST', $expectedHttpCode, $queryString);
    }

    /**
     * Assert PUT request and response body matches swagger specification
     *
     * @param string $path
     * @param array $data
     * @param int $expectedHttpCode
     */
    protected function assertPutSchemaMatch(
        string $path,
        array $data,
        int $expectedHttpCode = Response::HTTP_OK
    ) {
        $this->response = $this->putJson($path, $data);
        $this->assertSchemaMatch($path, $data, 'PUT', $expectedHttpCode);
    }

    /**
     * Assert PATCH request and response body matches swagger specification
     *
     * @param string $path
     * @param array $data
     * @param int $expectedHttpCode
     */
    protected function assertPatchSchemaMatch(
        string $path,
        array $data,
        int $expectedHttpCode = Response::HTTP_OK
    ) {
        $this->response = $this->putJson($path, $data);
        $this->assertSchemaMatch($path, $data, 'PATCH', $expectedHttpCode);
    }

    /**
     * Assert DELETE request and response body matches swagger specification
     *
     * @param string $path
     * @param int $expectedHttpCode
     */
    protected function assertDeleteSchemaMatch(
        string $path,
        int $expectedHttpCode = Response::HTTP_NO_CONTENT
    ) {
        $this->response = $this->deleteJson($path);
        $this->assertSchemaMatch($path, $data = [], 'DELETE', $expectedHttpCode);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function toJsonObject(array $data)
    {
        return json_decode(json_encode($data));
    }

    /**
     * @return array
     */    
    protected function getResponseArray()
    {
        return $this->response ? json_decode($this->response->getContent(), true) : [];
    }
}
