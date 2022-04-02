<?php
namespace App\Classes\KanbanBoard;

use Github\Client;
use Symfony\Component\HttpClient\HttplugClient;

class GithubClient
{
    private $client;
    private $milestone_api;
    private $account;

    public function __construct($token, $account)
    {
        $this->account = $account;
        $this->client = Client::createWithHttpClient(new HttplugClient());
        $this->client->authenticate($token, null, Client::AUTH_CLIENT_ID);
        $this->milestone_api = $this->client->api('issues')->milestones();
    }

    public function milestones($repository)
    {
        return $this->milestone_api->all($this->account, $repository);
    }

    public function issues($repository, $milestone_id)
    {
        $issue_parameters = [
            'milestone' => $milestone_id,
            'state' => 'all'
        ];
        return $this->client->api('issue')->all($this->account, $repository, $issue_parameters);
    }
    
}
