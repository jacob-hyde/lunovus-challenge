<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GithubService
{
    public function searchUsers(string $query, int $page): array
    {
        $response = Http::get('https://api.github.com/search/users', [ // Ideally added as a .env variable for the base URL
            'q' => $query,
            'page' => $page,
        ]);

        return $response->json();
    }
    public function getUser(string $username): array
    {
        $response = Http::get("https://api.github.com/users/{$username}");

        return $response->json();
    }

    public function getUserFollowers(string $username, int $page): array
    {
        $response = Http::get("https://api.github.com/users/{$username}/followers", [
            'per_page' => 10,
            'page' => $page,
        ]);

        return $response->json();
    }
}
