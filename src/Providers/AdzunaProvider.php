<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class AdzunaProvider extends AbstractProvider
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
            'url' => $payload['redirect_url'],
            'sourceId' => $payload['id'],
        ]);

        $job->setDatePostedAsString($payload['created'])
            ->setLongitude($payload['longitude'])
            ->setLatitude($payload['latitude'])
            ->setMinimumSalary($payload['salary_min'])
            ->setMaximumSalary($payload['salary_max']);

        if (isset($payload['company']) && isset($payload['company']['display_name'])) {
            $job->setCompany($payload['company']['display_name']);
        }

        if (isset($payload['location']) && isset($payload['location']['display_name'])) {
            $job->setLocation($payload['location']['display_name']);
        }

        if (isset($payload['category']) && isset($payload['category']['label'])) {
            $job->setOccupationalCategory($payload['category']['label']);
        }

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
            'description',
            'id',
            'redirect_url',
            'created',
            'longitude',
            'latitude',
            'salary_min',
            'salary_max',
        ];
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'results';
    }
}
