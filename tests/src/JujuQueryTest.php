<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\JujuQuery;
use Mockery as m;

class JujuQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Set up server variables for testing
        $_SERVER['HTTP_USER_AGENT'] = uniqid();
        $_SERVER['REMOTE_ADDR'] = uniqid();

        $this->query = new JujuQuery();
    }

    public function testItAddsDefaultAttributes()
    {
        $this->assertEquals($_SERVER['HTTP_USER_AGENT'], $this->query->get('useragent'));
        $this->assertEquals($_SERVER['REMOTE_ADDR'], $this->query->get('ipaddress'));
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'http://api.juju.com/jobs',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('k', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    public function testItReturnsFalseIfRequiredAttributesMissing()
    {
        $this->assertFalse($this->query->isValid());
    }

    public function testItReturnsTrueIfRequiredAttributesPresent()
    {
        $this->query->set('partnerid', uniqid());

        $this->assertTrue($this->query->isValid());
    }

    public function testItCanAddAttributesToUrl()
    {
        $url = $this->query->getUrl();
        $this->assertContains('ipaddress=', $url);
        $this->assertContains('useragent=', $url);
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
            'k' => uniqid(),
            'l' => uniqid(),
            'c' => uniqid(),
            'partnerid' => uniqid(),
            'highlight' => 0,
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }
    }
}
