## Test your Laravel APIs easily with Open API (Swagger) docs

This makes it easy to build automated tests for your Laravel based APIs quickly and easily using Open API specification (FKA Swagger).

## How it works?

It implements simple [InteractsWithSwagger](tests/Concerns/InteractsWithSwagger.php) trait which uses [Maks3w/SwaggerAssertions](https://github.com/Maks3w/SwaggerAssertions) library and performs schema checks against an actual API responses making it very powerful and easy to build functional tests. 

## How was it implemented

Step by step implementation process:
* Initialized laravel app using laravel docker container (bitnami/laravel)
* [Commit 1](https://github.com/mark81/api-swagger-assertions-with-laravel/commit/daf9a6a5fda49806ba4cb68d20e42d501f4ed656) - Added Maks3w/SwaggerAssertions to dependencies 

* [Commit 2](https://github.com/mark81/api-swagger-assertions-with-laravel/commit/c1a326c79d56ac4b7ebc8947089235a0bd67b4c7) - OpenAPI definition of example Star Wars Episodes api
* [Commit 3](https://github.com/mark81/api-swagger-assertions-with-laravel/commit/c2f2ca9bc3752c186c80cf595e8d741b5c1b3425) - Star Wars API test, add this point test fails due to missing endpoint
* [Commit 4](https://github.com/mark81/api-swagger-assertions-with-laravel/commit/0ce0df3e84fe0e008864caca14b6b5c267a5cf13) - Star Wars API Endpoint which makes the test succeed


### Basic API test

Below PHPUnit test will perform an API call to `api/v1/swepisodes` endpoint and test response schema against swagger definition

```php
<?php

namespace Tests\Functional;

use Tests\TestCase;
use Tests\Concerns\InteractsWithSwagger;

class SWApiTest extends TestCase
{
    use InteractsWithSwagger;

    /**
     * @test
     */
    public function list_swepisodes()
    {
        $this->assertGetSchemaMatch('api/v1/swepisodes');
    }
}

```

An Example API response is as follows

```json
[
    {
        "id": 1,
        "title": "Episode IV: A New Hope."
    },
    {
        "id": 2,
        "title": "Episode V: The Empire Strikes Back."
    },
    {
        "id": 3,
        "title": "Episode VI: Return of the Jedi."
    },
    {
        "id": 4,
        "title": "Episode I: The Phantom Menace."
    },
    {
        "id": 5,
        "title": "Episode II: Attack of the Clones."
    },
    {
        "id": 6,
        "title": "Episode III: Revenge of the Sith."
    },
    {
        "id": 7,
        "title": "X-Wing."
    },
    {
        "id": 8,
        "title": "Rebel Assault."
    }
]
```

API definition for the above API using OpenAPI v2.0 schema

```json
{
    "SWEpisodes": {
        "get": {
            "summary": "Get list of star wars episodes",
            "description": "Get list of star wars episodes",
            "responses": {
                "200": {
                    "description": "List of episodes",
                    "schema": {
                        "$ref": "#/ListOfEpisodes"
                    }
                },
                "400": {
                    "description": "error",
                    "schema": {
                        "$ref": "definitions.json#/Error"
                    }
                }
            }
        }
    },
    "ListOfEpisodes": {
        "type": "array",
        "items": {
            "$ref": "#/Episode"
        }
    },    
    "Episode": {
        "type": "object",
        "required": [
            "id",
            "title"
        ],
        "properties": {
            "id": {
                "type": "integer",
                "description": "Unique identifier"
            },
            "title": {
                "type": "string",
                "description": "Episode title type"
            }
        }
    }
}
```

The above test will pass schema assertion test if both `id` and `title` match swagger type definition.

```
> vendor/bin/phpunit
PHPUnit 7.3.2 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 2 seconds, Memory: 12.00MB

OK (1 test, 3 assertions)
```

### More complex test

This test will assert if 7th (index 6) Star Wars episode is in Fact X-Wing.

```php
    /**
     * @test
     */
    public function list_swepisodes()
    {
        $this->assertGetSchemaMatch('api/v1/swepisodes');
        $response = $this->getResponseArray();
        $this->assertArrayHasKey(6, $response);
        $this->assertArrayHasKey('title', $response[6]);
        $this->assertEquals($response[6]['title'], "X-Wing.");
    }
```

```
> vendor/bin/phpunit
PHPUnit 7.3.2 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 1.94 seconds, Memory: 12.00MB

OK (1 test, 6 assertions)
```

## Example API test

### Prerequisites
To make use of this, you'll need
* [Docker Compose](https://docs.docker.com/compose/install/)
* [An API definition using OpenAPI 2.0](https://swagger.io/docs/specification/2-0/basic-structure/)

### Running a sample test

* Run docker-compose

```
docker-compose up
```

* Give it few minutes to download images and init containers
* Open a browser and access [http://localhost:3000/api/v1/swepisodes](http://localhost:3000/api/v1/swepisodes). This should return list of all SW Episodes
* Find your laravel docker container id using `docker ps` command

```
docker ps
```

* Run `phpunit` from docker container 

```
docker exec -it [container id] vendor/bin/phpunit
```

### Thats it
You're done!
