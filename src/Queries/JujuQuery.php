<?php namespace JobApis\Jobs\Client\Queries;

class JujuQuery extends AbstractQuery
{
    /**
     * partnerid
     *
     * Your assigned Publisher ID. This is given to you when signing up.
     *
     * @var string
     */
    protected $partnerid;

    /**
     * ipaddress
     *
     * The IP Address of the end-user
     *
     * @var string
     */
    protected $ipaddress;

    /**
     * useragent
     *
     * The User-Agent of the end-user
     *
     * @var string
     */
    protected $useragent;

    /**
     * k
     *
     * The query. This is in the same format as a basic search.
     *
     * @var string
     */
    protected $k;

    /**
     * l
     *
     * The location. This can be a state, county, city, or zip code. Using multiple locations in one
     * query is not supported.
     *
     * @var string
     */
    protected $l;

    /**
     * c
     *
     * The category within which to limit results. To retrieve from any of several specified categories,
     * specify ','-joined category names. Full list here: http://www.juju.com/publisher/spec/#categories
     *
     * @var string
     */
    protected $c;

    /**
     * r
     *
     * The radius, in miles, around the search location. The default is 20 and the maximum is 100.
     *
     * @var integer
     */
    protected $r;

    /**
     * order
     *
     * The order in which to return results. Choices are: relevance, date, distance. The default is relevance.
     *
     * @var string
     */
    protected $order;

    /**
     * days
     *
     * The number of days back to search. Default is 90.
     *
     * @var integer
     */
    protected $days;

    /**
     * jpp
     *
     * The number of jobs per page to return with each request. The maximum is 20, which is also the default.
     *
     * @var integer
     */
    protected $jpp;

    /**
     * page
     *
     * The page of results to return. Page numbers start at 1, the default.
     *
     * @var integer
     */
    protected $page;

    /**
     * channel
     *
     * The channel name used to track performance for multiple sites.
     *
     * @var string
     */
    protected $channel;

    /**
     * highlight
     *
     * By default, results will be highlighted with HTML bolding. Set this flag to 0 to turn highlighting off.
     *
     * @var boolean
     */
    protected $highlight;

    /**
     * startindex
     *
     * If you are using API results as backfill on one page of results, use this flag to 'skip' jobs from the top
     * of further API results, because you've already shown them in backfill. The minimum (and default) is 1, which
     * indicates that results should start on the first job. Simple paging should be implemented with the page and
     * jpp parameters. If you are unsure, you probably want to use page and jpp.
     *
     * @var integer
     */
    protected $startindex;

    /**
     * session
     *
     * This parameter should be uniquely associated with a particular user. It can be an anonymized persistent or
     * session cookie for web requests, or an anonymized contact id for email. Juju currently uses this internally
     * for testing new algorithms. If you cannot or do not wish to provide this parameter, it's fine to omit it.
     *
     * @var string
     */
    protected $session;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'http://api.juju.com/jobs';
    }

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->k;
    }

    /**
     * Default parameters
     *
     * @var array
     */
    protected function defaultAttributes()
    {
        return [
            'ipaddress' => $this->userIp(),
            'useragent' => $this->userAgent(),
        ];
    }

    /**
     * Required parameters
     *
     * @return array
     */
    protected function requiredAttributes()
    {
        return [
            'partnerid',
            'ipaddress',
            'useragent',
        ];
    }

    /**
     * Return the user agent from server
     *
     * @return  string
     */
    protected function userAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * Return the IP address from server
     *
     * @return  string
     */
    protected function userIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }
}
