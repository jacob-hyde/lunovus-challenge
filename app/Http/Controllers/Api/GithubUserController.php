<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GithubUserFollowerResource;
use App\Http\Resources\GithubUserResource;
use App\Services\GithubService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GithubUserController extends Controller
{
    public function __construct(private GithubService $githubService){}

    public function search(Request $request): JsonResponse|RedirectResponse
    {
        // I should deal if this is empty by returning a 400 response, but the front-end takes care of it for now
        $handleSearchQuery = $request->query('q', '');
        $users = $this->githubService->searchUsers($handleSearchQuery, $request->query('page', 1));

        // Other users have handles that begin with taylorotwell, but if there is a direct match it would be the first item
        if ($users['items'][0]['login'] === $handleSearchQuery) {
            return redirect()->route('github.show', ['username' => $users['items'][0]['login']]);
        }

        return GithubUserResource::collection($users['items'])
            ->additional(['total' => $users['total_count']])
            ->response()
            ->setStatusCode(Response::HTTP_OK);

    }

    public function show(string $username): JsonResponse
    {
        $user = $this->githubService->getUser($username);
        $user['follower_users'] = $this->githubService->getUserFollowers($username, 1);

        return (new GithubUserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function followers(string $username, Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $followers = $this->githubService->getUserFollowers($username, $page);
        $nextPage = $request->query('total') / 10 > $page ? $page + 1 : false;

        return GithubUserFollowerResource::collection(collect($followers))->additional(['next_page' => $nextPage])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
