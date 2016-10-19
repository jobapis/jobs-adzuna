<?php namespace JobApis\Jobs\Client\Queries;

class AdzunaQuery extends AbstractQuery
{
    /**
     * country
     *
     * ISO 8601 country code of the relevant country. Allowed choices:
     * gb
     * au
     * br
     * ca
     * de
     * fr
     * in
     * nl
     * pl
     * ru
     * za
     *
     * @var string
     */
    protected $country;

    /**
     * app_id
     *
     * Application ID, supplied by Adzuna.
     *
     * @var string
     */
    protected $app_id;

    /**
     * app_key
     *
     * Application key, supplied by Adzuna
     *
     * @var string
     */
    protected $app_key;

    /**
     * page
     *
     * Page number
     *
     * @var integer
     */
    protected $page;

    /**
     * results_per_page
     *
     * The number of results to include on each page of search results.
     *
     * @var integer
     */
    protected $results_per_page;

    /**
     * what
     *
     * The keywords to search for. Use space or comma characters to separate multiple keywords.
     *
     * @var string
     */
    protected $what;

    /**
     * what_and
     *
     * The keywords to search for, all keywords must be found.
     *
     * @var string
     */
    protected $what_and;

    /**
     * what_phrase
     *
     * An entire phrase which must be found in the description or title.
     *
     * @var string
     */
    protected $what_phrase;

    /**
     * what_or
     *
     * Keywords to search for. Use space or comma characters to separate multiple keywords.
     *
     * @var string
     */
    protected $what_or;

    /**
     * what_exclude
     *
     * Keywords to exclude from the search. Use space or comma characters to separate multiple keywords.
     *
     * @var string
     */
    protected $what_exclude;

    /**
     * title_only
     *
     * Keywords to find, but only in the title. Use space or comma characters to separate multiple keywords.
     *
     * @var string
     */
    protected $title_only;

    /**
     * location0
     *
     * The locationN fields may be used to describe a location, in a similar form to that
     * returned in a Adzuna::API::Response::Location object. For example,
     * "location0=UK&location1=South East England&location2=Surrey" will perform a search
     * over the county of Surrey.
     *
     * @var string
     */
    protected $location0;

    /**
     * location1
     *
     * See location0 above.
     *
     * @var string
     */
    protected $location1;

    /**
     * location2
     *
     * See location0 above.
     *
     * @var string
     */
    protected $location2;

    /**
     * location3
     *
     * See location0 above.
     *
     * @var string
     */
    protected $location3;

    /**
     * location4
     *
     * See location0 above.
     *
     * @var string
     */
    protected $location4;

    /**
     * location5
     *
     * See location0 above.
     *
     * @var string
     */
    protected $location5;

    /**
     * location6
     *
     * See location0 above.
     *
     * @var string
     */
    protected $location6;

    /**
     * location7
     *
     * See location0 above.
     *
     * @var string
     */
    protected $location7;

    /**
     * where
     *
     * The geographic centre of the search. Place names or postal codes may be used.
     *
     * @var string
     */
    protected $where;

    /**
     * distance
     *
     * The distance in kilometres from the centre of the place described by the 'where' parameter.
     * Defaults to 10km.
     *
     * @var string
     */
    protected $distance;

    /**
     * max_days_old
     *
     * The age of the oldest advertisment in days that will be returned.
     *
     * @var string
     */
    protected $max_days_old;

    /**
     * category
     *
     * The category tag, as returned by the "category" endpoint.
     *
     * @var string
     */
    protected $category;

    /**
     * sort_direction
     *
     * The order of search results (ascending or descending).
     *
     * @var string
     */
    protected $sort_direction;

    /**
     * sort_by
     *
     * The ordering of the search results.
     *
     * @var string
     */
    protected $sort_by;

    /**
     * salary_min
     *
     * The minimum salary we wish to get results for.
     *
     * @var string
     */
    protected $salary_min;

    /**
     * salary_max
     *
     * The maximum salary we wish to get results for.
     *
     * @var string
     */
    protected $salary_max;

    /**
     * salary_include_unknown
     *
     * When using salary_min and/or salary_max set this to "1", to include jobs with unknown salaries in results.
     *
     * @var boolean
     */
    protected $salary_include_unknown;

    /**
     * full_time
     *
     * If set to "1", only full time jobs will be returned.
     *
     * @var boolean
     */
    protected $full_time;

    /**
     * part_time
     *
     * If set to "1", only part time jobs will be returned.
     *
     * @var boolean
     */
    protected $part_time;

    /**
     * contract
     *
     * If set to "1", only contract jobs will be returned.
     *
     * @var boolean
     */
    protected $contract;

    /**
     * permanent
     *
     * If set to "1", only permanent jobs will be returned.
     *
     * @var boolean
     */
    protected $permanent;

    /**
     * company
     *
     * The canonical company name. This may be returned in a Adzuna::API::Response::Company object
     * when a job is returned. A full list of allowed terms in not available through the API.
     *
     * @var string
     */
    protected $company;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'http://api.adzuna.com/v1/api/jobs';
    }

    /**
     * Get keyword
     *
     * @return string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->what;
    }

    /**
     * Get url
     *
     * @return  string
     */
    public function getUrl()
    {
        return $this->getBaseUrl().'/'.$this->country.'/search/'.$this->page.'/'.$this->getQueryString();
    }

    /**
     * Default parameters
     *
     * @var array
     */
    protected function defaultAttributes()
    {
        return [
            'page' => '1',
        ];
    }

    /**
     * Gets the attributes to use for this API's query
     *
     * @var array
     */
    protected function getQueryAttributes()
    {
        $attributes = get_object_vars($this);
        unset($attributes['country']);
        unset($attributes['page']);
        return $attributes;
    }

    /**
     * Required parameters
     *
     * @return array
     */
    protected function requiredAttributes()
    {
        return [
            'country',
            'app_id',
            'app_key',
            'page',
        ];
    }
}
