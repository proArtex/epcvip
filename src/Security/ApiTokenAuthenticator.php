<?php declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    public function getCredentials(Request $request)
    {
        $authToken = $request->headers->get('Authorization');

        if (!preg_match('@^Bearer ([a-z0-9]+)$@', $authToken, $matches)) {
            throw new AuthenticationException("Token is invalid");
        }

        return ['token' => $matches[1]];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername('api_user');
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($user->getPassword() !== $credentials['token']) {
            throw new AuthenticationException('Token is invalid');
        }

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
