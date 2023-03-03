<?php

declare(strict_types=1);

namespace App\Security\Guard\Authenticator;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\SessionUnavailableException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class SessionAPIAuthenticator extends AbstractGuardAuthenticator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), '/api') && $request->cookies->has('PHPSESSID');
    }

    public function getCredentials(Request $request): array
    {
        return [
            'session' => unserialize($request->getSession()->get('_security_main')),
        ];
    }

    /**
     * @param mixed $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $session = $credentials['session'];

        if (!$session instanceof UsernamePasswordToken) {
            throw new NotFoundHttpException('Session not found');
        }

        /** @var User|null $user */
        $user = $session->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $this->userRepository->find($user->getId());
    }

    /**
     * @param mixed $credentials
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * @param mixed $providerKey
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}