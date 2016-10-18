<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class JujuProvider extends AbstractProvider
{
    /**
     * Returns the standardized job object
     *
     * @param array $payload
     *
     * @return \JobApis\Jobs\Client\Job
     */
    public function createJobObject($payload)
    {
        $job = new Job([
            'title' => $payload['title'],
            'name' => $payload['title'],
            'description' => $payload['description'],
            'url' => $payload['link'],
            'sourceId' => $payload['guid'],
            'javascriptAction' => 'onclick',
            'javascriptFunction' => $payload['onclick'],
            'location' => $payload['city'].', '.$payload['state'],
        ]);

        $job->setCompany($payload['company'])
            ->setState($payload['state'])
            ->setCity($payload['city'])
            ->setDatePostedAsString($payload['postdate']);

        return $job;
    }

    /**
     * Job response object default keys that should be set
     *
     * @return  string
     */
    public function getDefaultResponseFields()
    {
        return [
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
    }

    /**
     * Get format
     *
     * @return  string Currently only 'json' and 'xml' supported
     */
    public function getFormat()
    {
        return 'xml';
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'channel.item';
    }
}
