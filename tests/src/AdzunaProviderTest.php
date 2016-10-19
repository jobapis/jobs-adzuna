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
        $_SERVER['HTTP_USER_AGENT'] = uniqid();
        $_SERVER['REMOTE_ADDR'] = uniqid();

        $this->query = m::mock('JobApis\Jobs\Client\Queries\AdzunaQuery');

        $this->client = new AdzunaProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            'title',
            'company',
            'city',
            'state',
            'country',
            'source',
            'link',
            'onclick',
            'guid',
            'postdate',
            'description',
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEquals('channel.item', $this->client->getListingsPath());
    }

    public function testItCanCreateJobObjectFromPayload()
    {
        $payload = $this->createJobArray();

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['title'], $results->getTitle());
        $this->assertEquals($payload['title'], $results->getName());
        $this->assertEquals($payload['description'], $results->getDescription());
        $this->assertEquals($payload['company'], $results->getCompanyName());
        $this->assertEquals($payload['link'], $results->getUrl());
    }

    /**
     * Integration test for the client's getJobs() method.
     */
    public function testItCanGetJobs()
    {
        $options = [
            'k' => uniqid(),
            'l' => uniqid(),
            'partnerid' => uniqid(),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new AdzunaQuery($options);

        $client = new AdzunaProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobs = $this->createXmlResponse();

        $guzzle->shouldReceive('get')
            ->with($query->getUrl(), [])
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn($jobs);

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(2, $results);
    }

    /**
     * Integration test with actual API call to the provider.
     * @group real
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
            'company' => uniqid(),
            'description' => uniqid(),
            'link' => uniqid(),
            'postdate' => '2015-07-'.rand(1,31),
            'city' => uniqid(),
            'state' => uniqid(),
            'country' => uniqid(),
            'source' => uniqid(),
            'onclick' => uniqid(),
            'guid' => uniqid(),
        ];
    }
}
