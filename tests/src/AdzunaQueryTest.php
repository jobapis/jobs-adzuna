<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\AdzunaQuery;
use Mockery as m;

class AdzunaQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new AdzunaQuery();
    }

    public function testItAddsDefaultAttributes()
    {
        $this->assertEquals('1', $this->query->get('page'));
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'http://api.adzuna.com/v1/api/jobs',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('what', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    public function testItReturnsFalseIfRequiredAttributesMissing()
    {
        $this->assertFalse($this->query->isValid());
    }

    public function testItReturnsTrueIfRequiredAttributesPresent()
    {
        $this->query->set('country', uniqid());
        $this->query->set('app_id', uniqid());
        $this->query->set('app_key', uniqid());

        $this->assertTrue($this->query->isValid());
    }

    public function testItCanAddAttributesToUrl()
    {
        $this->query->set('app_id', uniqid());
        $url = $this->query->getUrl();
        $this->assertContains('app_id=', $url);
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItThrowsExceptionWhenSettingInvalidAttribute()
    {
        $this->query->set(uniqid(), uniqid());
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItThrowsExceptionWhenGettingInvalidAttribute()
    {
        $this->query->get(uniqid());
    }

    public function testItSetsAndGetsValidAttributes()
    {
        $attributes = [
            'country' => uniqid(),
            'what' => uniqid(),
            'where' => uniqid(),
            'distance' => rand(1,10),
            'app_id' => uniqid(),
            'app_key' => uniqid(),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }
    }
}
