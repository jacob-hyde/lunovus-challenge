<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GithubUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'login' => $this['login'],
            'avatar_url' => $this['avatar_url'],
            'followers' => $this->when(isset($this['followers']), function () {
                return $this['followers'];
            }),
            'follower_users' => $this->when(isset($this['follower_users']), function () {
                return GithubUserResource::collection(collect($this['follower_users']));
            }),
        ];
    }
}
