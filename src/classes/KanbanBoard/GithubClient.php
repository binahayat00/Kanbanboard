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
        $this->client = $this->setClient();
        $this->milestone_api = $this->setMilestoneApi($token);
    }

    private function setClient(){
        return Client::createWithHttpClient(new HttplugClient());
    }

    private function setMilestoneApi($token){
        $this->client->authenticate($token, null, Client::AUTH_CLIENT_ID);
        return $this->client->api('issues')->milestones();
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
