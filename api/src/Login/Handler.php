<?php

namespace App\Login;

use App\Document\AuthToken;
use App\Document\User;
use App\Login\Exception\BadCredentialsException;
use App\Message\Event\LoggedIn;
use App\Message\Event\LoginFailed;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class Handler
{
    /** @var DocumentRepository */
    private $userRepository;

    /** @var RequestStack */
    private $requestStack;

    public function __construct(
        DocumentRepository $userRepository,
        RequestStack $requestStack
    ) {
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
    }

    public function authenticate(Credentials $credentials)
    {
        /** @var User|null $user */
        $user = $this->userRepository->findOneBy([
            'email' => $credentials->getEmail(),
        ]);

        try {
            if (null === $user) {
                throw new BadCredentialsException(\sprintf('User “%s” not found.', $credentials->getEmail()));
            }

            if ($user->getPassword() != Password::encrypt($credentials->getPassword())) {
                throw new BadCredentialsException('Invalid password.');
            }
        } catch (BadCredentialsException $ex) {
            return new LoginFailed($credentials, $ex, $user);
        }

        return new LoggedIn($credentials, $user->getUserId());
    }

    public function logout(Request $request, Response $response, AuthToken $authToken): void
    {
    }
}
