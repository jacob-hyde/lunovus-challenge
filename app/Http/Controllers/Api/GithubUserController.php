<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GithubService;
use Illuminate\Http\Request;

class GithubUserController extends Controller
{
    public function __construct(private GithubService $githubService){}

    public function search(Request $request)
    {
        $handleSearchQuery = $request->query('q', '');
        $users = $this->githubService->searchUsers($handleSearchQuery, $request->query('page', 1));

        if ($users['items'][0]['login'] === $handleSearchQuery) { // Other users have handles that begin with taylorotwell, but if there is a direct match it would be the first item
            return redirect()->route('github.show', ['username' => $users['items'][0]['login']]);
        }

        return response()->json(['data' => ['users' => $users['items'], 'total' => $users['total_count']]]);
    }

    public function show(string $username)
    {
        $user = $this->githubService->getUser($username);
        $user['follower_users'] = $this->githubService->getUserFollowers($username, 1);

        return response()->json(['data' => $user]);
    }

    public function followers(string $username, Request $request)
    {
        $followers = $this->githubService->getUserFollowers($username, $request->query('page', 1));
        $nextPage = $request->query('page', 1) + 1;
        if ($nextPage > $request->query('total') / 10) {
            $nextPage = false;
        }

        return response()->json(['data' => ['followers' => $followers, 'next_page' => $nextPage]]);
    }
}
