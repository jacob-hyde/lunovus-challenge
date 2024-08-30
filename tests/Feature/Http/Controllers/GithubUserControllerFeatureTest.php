<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GithubUserControllerFeatureTest extends TestCase
{
    /**
     * Basic test to check if the search route is working
     *
     * @return void
     */
    public function test_it_can_search_users_and_redirect_to_exact_match(): void
    {
        $fakeResponse = [
            'total_count' => 1,
            'incomplete_results' => false,
            'items' => [
                [
                    'login' => 'taylorotwell',
                    'id' => 1,
                    'avatar_url' => 'https://avatars.githubusercontent.com/u/1?v=4',
                    'url' => 'https://api.github.com/users/taylorotwell',
                ],
            ],
        ];

        Http::fake([
            'https://api.github.com/search/users*' => Http::response($fakeResponse, 200),
        ]);

        $response = $this->getJson('/api/github/search?q=taylorotwell');

        $response->assertRedirect(route('github.show', ['username' => 'taylorotwell']));
    }

    /**
     * Basic test to check if the search route is working
     *
     * @return void
     */
    public function test_it_can_search_users_and_return_a_list(): void
    {
        $fakeResponse = [
            'total_count' => 2,
            'incomplete_results' => false,
            'items' => [
                [
                    'id' => 1,
                    'login' => 'john1',
                    'avatar_url' => 'https://avatars.githubusercontent.com/u/1?v=4',
                ],
                [
                    'id' => 2,
                    'login' => 'john2',
                    'avatar_url' => 'https://avatars.githubusercontent.com/u/2?v=4',
                ],
            ],
        ];

        Http::fake([
            'https://api.github.com/search/users*' => Http::response($fakeResponse, 200),
        ]);

        $response = $this->getJson('/api/github/search?q=john');

        $response->assertOk();
        $response->assertJson([
            'data' => $fakeResponse['items'],
            'total' => 2,
        ]);
    }

    /**
     * Basic test to check if the show route is working
     *
     * @return void
     */
    public function test_it_can_show_a_user_and_their_followers(): void
    {
        $username = 'johndoe';

        $fakeUserResponse = [
            'id' => 1,
            'login' => 'johndoe',
            'avatar_url' => 'https://avatars.githubusercontent.com/u/1?v=4',
        ];

        $fakeFollowersResponse = [
            [
                'id' => 2,
                'login' => 'janedoe',
                'avatar_url' => 'https://avatars.githubusercontent.com/u/2?v=4',
            ],
        ];

        Http::fake([
            'https://api.github.com/users/' . $username => Http::response($fakeUserResponse, 200),
            'https://api.github.com/users/' . $username . '/followers*' => Http::response($fakeFollowersResponse, 200),
        ]);

        $response = $this->getJson('/api/github/' . $username);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'login' => 'johndoe',
                'follower_users' => [
                    ['login' => 'janedoe'],
                ],
            ],
        ]);
    }

    /**
     * Basic test to check if the followers route is working
     *
     * @return void
     */
    public function test_it_can_fetch_followers_with_pagination(): void
    {
        $username = 'johndoe';
        $page = 1;

        $fakeFollowersResponse = [
            [
                'id' => 3,
                'login' => 'follower1',
                'avatar_url' => 'https://avatars.githubusercontent.com/u/3?v=4',
            ],
        ];

        Http::fake([
            'https://api.github.com/users/' . $username . '/followers?per_page=10&page=' . $page . '*' => Http::response($fakeFollowersResponse, 200),
        ]);

        $response = $this->getJson('/api/github/' . $username . '/followers?page=' . $page . '&total=20');

        $response->assertOk();
        $response->assertJson([
            'data' => $fakeFollowersResponse,
            'next_page' => 2,
        ]);
    }
}
