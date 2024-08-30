<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GithubService
{
    /**
     * Search for users on GitHub
     *
     * @param string $query
     * @param int $page
     * @return array
     */
    public function searchUsers(string $query, int $page): array
    {
        $response = Http::get('https://api.github.com/search/users', [ // Ideally added as a .env variable for the base URL
            'q' => $query,
            'page' => $page,
        ]);

        return $response->json();
    }

    /**
     * Get a user from GitHub
     *
     * @param string $username
     * @return array
     */
    public function getUser(string $username): array
    {
        $response = Http::get("https://api.github.com/users/{$username}");

        return $response->json();
    }

    /**
     * Get a user's followers from GitHub
     *
     * @param string $username
     * @param int $page
     * @return array
     */
    public function getUserFollowers(string $username, int $page): array
    {
        $response = Http::get("https://api.github.com/users/{$username}/followers", [
            'per_page' => 10, // This per_page could easily come from a service config file
            'page' => $page,
        ]);

        return $response->json();
    }
}
