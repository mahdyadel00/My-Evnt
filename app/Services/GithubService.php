<?php

namespace App\Services;

use GuzzleHttp\Client;

class GithubService
{
    protected $client;
    protected $token;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.github.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('GITHUB_TOKEN'),
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);
    }

    public function getRepository($owner, $repo)
    {
        $response = $this->client->get("repos/{$owner}/{$repo}");
        return json_decode($response->getBody(), true);
    }

    public function createIssue($owner, $repo, $title, $body)
    {
        $response = $this->client->post("repos/{$owner}/{$repo}/issues", [
            'json' => [
                'title' => $title,
                'body' => $body,
            ],
        ]);
        return json_decode($response->getBody(), true);
    }
}
