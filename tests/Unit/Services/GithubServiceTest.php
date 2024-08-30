<?php

namespace Tests\Unit\Services;
use App\Services\GithubService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GithubServiceTest extends TestCase
{
    protected GithubService $githubService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->githubService = new GithubService();
    }

    /**
     * Basic test to check if the service can search users on GitHub
     *
     * @return void
     */
    public function test_it_can_search_users_on_github()
    {
        $query = 'john';
        $page = 1;

        $fakeResponse = [
            'total_count' => 1,
            'incomplete_results' => false,
            'items' => [
                [
                    'login' => 'johndoe',
                    'id' => 1,
                    'avatar_url' => 'https://avatars.githubusercontent.com/u/1?v=4',
                    'url' => 'https://api.github.com/users/johndoe',
                ],
            ],
        ];

        Http::fake([
            'https://api.github.com/search/users*' => Http::response($fakeResponse, 200),
        ]);

        $response = $this->githubService->searchUsers($query, $page);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('total_count', $response);
        $this->assertArrayHasKey('items', $response);
        $this->assertEquals('johndoe', $response['items'][0]['login']);
    }

    /**
     * Basic test to check if the service can get a user from GitHub
     *
     * @return void
     */
    public function test_it_can_get_a_user_from_github()
    {
        $username = 'johndoe';

        $fakeResponse = [
            'login' => 'johndoe',
            'id' => 1,
            'avatar_url' => 'https://avatars.githubusercontent.com/u/1?v=4',
            'url' => 'https://api.github.com/users/johndoe',
        ];

        Http::fake([
            'https://api.github.com/users/' . $username => Http::response($fakeResponse, 200),
        ]);

        $response = $this->githubService->getUser($username);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('login', $response);
        $this->assertEquals('johndoe', $response['login']);
    }

    /**
     * Basic test to check if the service can get user followers from GitHub
     *
     * @return void
     */
    public function test_it_can_get_user_followers_from_github()
    {
        $username = 'johndoe';
        $page = 1;

        $fakeResponse = [
            [
                'login' => 'janedoe',
                'id' => 2,
                'avatar_url' => 'https://avatars.githubusercontent.com/u/2?v=4',
                'url' => 'https://api.github.com/users/janedoe',
            ],
        ];

        Http::fake([
            'https://api.github.com/users/' . $username . '/followers*' => Http::response($fakeResponse, 200),
        ]);

        $response = $this->githubService->getUserFollowers($username, $page);

        $this->assertIsArray($response);
        $this->assertEquals('janedoe', $response[0]['login']);
    }
}
