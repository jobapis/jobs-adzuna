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
        echo json_encode($payload); exit;
        /*
         * Actual response:
         * 
            {
              "company": {
                "display_name": "YOOX NET A PORTER GROUP",
                "__CLASS__": "Adzuna::API::Response::Company",
                "canonical_name": "YOOX NET-A-PORTER GROUP"
              },
              "longitude": -0.233646,
              "adref": "eyJhbGciOiJIUzI1NiJ9.eyJzIjoieVk5dFp1aXFRdFd6UXFMYXVxOTZodyIsImkiOiI0MTAyNTU4ODkifQ.9W9KUZUwtdLr018KLQ-pet3ccdeQJqKNndTyN1wJ7aA",
              "redirect_url": "https://www.adzuna.co.uk/jobs/land/ad/410255889?se=yY9tZuiqQtWzQqLauq96hw&utm_medium=api&utm_source=e662cf9f&v=E295773E9F10D7401F66C231DC4E474A91F59174",
              "salary_min": 42535.81,
              "__CLASS__": "Adzuna::API::Response::Job",
              "title": "DevOps <strong>Engineer</strong>",
              "description": "Role purpose: We’re busy changing the way we do our technical operations and looking at the way we build our platform to use virtualisation and cloud approaches more effectively. We’re recruiting enthusiastic individuals with a passion for the DevOps approach to help us. This will involve automating everything, working closely with development teams and looking at new ways of doing things such as utilizing Cloud technologies. Working in a DevOps capacity means you’ll need some strong Linux syst…",
              "category": {
                "tag": "unknown",
                "__CLASS__": "Adzuna::API::Response::Category",
                "label": "Unknown"
              },
              "location": {
                "area": [
                  "UK",
                  "London",
                  "West London",
                  "Shepherd's Bush"
                ],
                "__CLASS__": "Adzuna::API::Response::Location",
                "display_name": "Shepherd's Bush, West London"
              },
              "id": "410255889",
              "created": "2016-07-13T09:27:12Z",
              "latitude": 51.508245,
              "salary_max": 42535.81,
              "salary_is_predicted": "1",
              "city": null,
              "state": null,
              "country": null,
              "source": null,
              "link": null,
              "onclick": null,
              "guid": null,
              "postdate": null
            }
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
        */
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
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'results';
    }
}
