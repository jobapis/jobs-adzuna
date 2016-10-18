<?php namespace JobApis\Jobs\Client\Providers\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\JujuProvider;
use JobApis\Jobs\Client\Queries\JujuQuery;
use Mockery as m;

class JujuProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_SERVER['HTTP_USER_AGENT'] = uniqid();
        $_SERVER['REMOTE_ADDR'] = uniqid();

        $this->query = m::mock('JobApis\Jobs\Client\Queries\JujuQuery');

        $this->client = new JujuProvider($this->query);
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

        $query = new JujuQuery($options);

        $client = new JujuProvider($query);

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
     */
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('PARTNER_ID')) {
            $this->markTestSkipped('PARTNER_ID not set. Real API call will not be made.');
        }

        $keyword = 'engineering';

        $query = new JujuQuery([
            'k' => $keyword,
            'partnerid' => getenv('PARTNER_ID'),
            'ipaddress' => getHostByName(getHostName()),
        ]);

        $client = new JujuProvider($query);

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

    private function createXmlResponse()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <rss version="2.0">
                <channel>
                    <title>Juju </title>
                    <link>
                    http://api.juju.com/jobs?partnerid=3f6eacc5a03e03b4287c1a0b43ece6bb&amp;ipaddress=10.1.30.15&amp;useragent=Mozilla%2F5.0+%28Windows%3B+U%3B+Windows+NT+5.1%3B+en-US%3B+rv%3A1.7%29+Gecko%2F20040803+Firefox%2F0.9.3&amp;k=engineering&amp;jpp=10&amp;page=1&amp;highlight=0
                </link>
                    <description>
                    Juju - Search thousands of job sites at once for local jobs in your field.
                </description>
                    <language>en-us</language>
                    <totalresults>437219</totalresults>
                    <startindex>0</startindex>
                    <itemsperpage>10</itemsperpage>
                    <item>
                        <title>Director of Quality Assurance</title>
                        <zip></zip>
                        <city>Birmingham</city>
                        <county>Jefferson</county>
                        <state>AL</state>
                        <country>US</country>
                        <source>GlidePath.com</source>
                        <company>Milo&#39;s Tea Company</company>
                        <link>http://www.juju.com/jad/00000000lss51f?impression_id=AESwhPSWQKWXJ90_tqmllg&amp;partnerid=3f6eacc5a03e03b4287c1a0b43ece6bb&amp;channel=</link>
                        <onclick>juju_partner(this, \'235\');</onclick>
                        <guid isPermaLink="false">00000000lss51f</guid>
                        <postdate>07/29/15</postdate>
                        <description>…quality control personnel on a day-to-day basis. | Supports concurrent  engineering  efforts by participating in design development projects representing quality assurance</description>
                    </item>
                    <item>
                        <title>Director of Quality Assurance</title>
                        <zip></zip>
                        <city>Birmingham</city>
                        <county>Jefferson</county>
                        <state>AL</state>
                        <country>US</country>
                        <source>GlidePath.com</source>
                        <company>Milo&#39;s Tea Company</company>
                        <link>http://www.juju.com/jad/00000000lss51f?impression_id=AESwhPSWQKWXJ90_tqmllg&amp;partnerid=3f6eacc5a03e03b4287c1a0b43ece6bb&amp;channel=</link>
                        <onclick>juju_partner(this, \'235\');</onclick>
                        <guid isPermaLink="false">00000000lss51f</guid>
                        <postdate>07/29/15</postdate>
                        <description>…quality control personnel on a day-to-day basis. | Supports concurrent  engineering  efforts by participating in design development projects representing quality assurance</description>
                    </item>
                </channel>
            </rss>';
        return $xml;
    }
}
