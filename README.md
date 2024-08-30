# Lunovus Developer (PHP) Code Check

## Installation
### Pre Reqs
1. PHP
2. Composer
3. Docker
4. Node v16+

### Steps to Install
1. Clone this repo
2. Run `composer install`
3. Copy .env.example to .env
3. Create the frontend bundle by either: `npm run dev` or `npm run build`
4. Start Docker: `./vendor/bin/sail up`
5. Navigate to `127.0.0.1` or `localhost`

## Notes
As this was a code inspection/challenge, I did not do everything the way I would do it in a production environment for time reasons. Here are a few things to note:

- I used doc blocks in the GithubService, but ideally would have them in the controller as well.
- The frontend data would likely could instead use a store to make it easier for the use of multiple components.
- Logic within Index.jsx (which could be named something like GithubFollowersSearch.jsx) could have been seperated out more per component with the help of a store.
- More PHPUnit tests could have been written
- Frontend tests could have been written
- I would likely move some of the logic from the controllers to actions where it is more than two lines per controller method.
