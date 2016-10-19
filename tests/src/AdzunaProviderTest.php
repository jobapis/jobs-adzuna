<?php namespace JobApis\Jobs\Client\Providers\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\AdzunaProvider;
use JobApis\Jobs\Client\Queries\AdzunaQuery;
use Mockery as m;

class AdzunaProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = m::mock('JobApis\Jobs\Client\Queries\AdzunaQuery');

        $this->client = new AdzunaProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            'title',
            'description',
            'id',
            'redirect_url',
            'created',
            'longitude',
            'latitude',
            'salary_min',
            'salary_max',
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEquals('results', $this->client->getListingsPath());
    }

    public function testItCanCreateJobObjectFromPayload()
    {
        $payload = $this->createJobArray();

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['title'], $results->getTitle());
        $this->assertEquals($payload['title'], $results->getName());
        $this->assertEquals($payload['description'], $results->getDescription());
        $this->assertEquals($payload['redirect_url'], $results->getUrl());
    }

    /**
     * Integration test for the client's getJobs() method.
     */
    public function testItCanGetJobs()
    {
        $options = [
            'what' => uniqid(),
            'where' => uniqid(),
            'app_key' => uniqid(),
            'app_id' => uniqid(),
            'country' => 'gb',
            'page' => rand(1,2),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new AdzunaQuery($options);
        $url = $query->getUrl();

        $client = new AdzunaProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobs = $this->createResponse();

        $guzzle->shouldReceive('get')
            ->with($url, [])
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn($jobs);

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(1, $results);
    }

    /**
     * Integration test with actual API call to the provider.
     */
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('APP_ID') || !getenv('APP_KEY')) {
            $this->markTestSkipped('APP_ID and APP_KEY not set. Real API call will not be made.');
        }

        $keyword = 'engineering';

        $query = new AdzunaQuery([
            'what' => $keyword,
            'country' => 'gb',
            'app_id' => getenv('APP_ID'),
            'app_key' => getenv('APP_KEY'),
            'page' => '1',
        ]);

        $client = new AdzunaProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);

        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    private function createJobArray()
    {
        return [
            'title' => uniqid(),
            'description' => uniqid(),
            'id' => uniqid(),
            'redirect_url' => uniqid(),
            'created' => date('Y-m-d'),
            'longitude' => uniqid(),
            'latitude' => uniqid(),
            'salary_min' => uniqid(),
            'salary_max' => uniqid(),
            'company' => uniqid(),
        ];
    }

    private function createResponse()
    {
        return '{
            "results": [
            {
              "company": {
                "display_name": "New Company",
                "__CLASS__": "Adzuna::API::Response::Company"
              },
              "salary_min": 7200,
              "salary_max": 7200,
              "location": {
                "display_name": "West Park, Leeds",
                "__CLASS__": "Adzuna::API::Response::Location",
                "area": [
                  "UK",
                  "Yorkshire And The Humber",
                  "West Yorkshire",
                  "Leeds",
                  "West Park"
                ]
              },
              "description": "Brief overview of the role As an LDD Group ICT <strong>Helpdesk</strong> Apprentice, you’ll be responsible for helping to resolve ICT issues from our IT supported clients. You will gain hands ...",
              "category": {
                "__CLASS__": "Adzuna::API::Response::Category",
                "tag": "it-jobs",
                "label": "IT Jobs"
              },
              "created": "2016-10-17T12:34:53Z",
              "longitude": -1.610642,
              "redirect_url": "https://www.adzuna.co.uk/jobs/details/450083776?se=2U39RbWwSMCzePgKdqXgBg&utm_medium=api&utm_source=e662cf9f&v=79C1258A5598A385040CDDF4F7735AA022327F0D",
              "latitude": 53.838855,
              "__CLASS__": "Adzuna::API::Response::Job",
              "adref": "eyJhbGciOiJIUzI1NiJ9.eyJpIjoiNDUwMDgzNzc2IiwicyI6IjJVMzlSYld3U01DemVQZ0tkcVhnQmcifQ.MAYp8D5zYWYWItHSytC9ePCDND2nZLAOETB0VnrZFa8",
              "salary_is_predicted": "0",
              "contract_time": "full_time",
              "id": "450083776",
              "title": "ICT <strong>Helpdesk</strong> Apprentice",
              "contract_type": "permanent"
            }
            ],
            "count": 5611,
            "mean": 25758.42,
            "__CLASS__": "Adzuna::API::Response::JobSearchResults"
        }';
    }
}
