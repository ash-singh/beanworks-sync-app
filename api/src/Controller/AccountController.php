<?php

namespace App\Controller;

use App\Account\Account;
use App\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * AccountController.
 *
 * @Route(path="/api/v1",name="api_")
 */
class AccountController extends AbstractController
{
    /**
     * @Route(path="/accounts", name="accounts", methods={"GET"})
     */
    public function getAccountList(Request $request, Account $account, User $userManager): JsonResponse
    {
        $token = $request->headers->get('api-token');

        if (null === $token) {
            return new JsonResponse([
                'status' => 'KO', 'message' => 'Please pass api-token in header',
            ], Response::HTTP_BAD_REQUEST);
        }

        ;
        if (null === ($user = $userManager->getUserFromToken($token))) {
            return new JsonResponse([
                'status' => 'KO', 'message' => 'Invalid token',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
                'status' => 'OK',
                'message' => 'Accounts fetched successfully',
            ] + $account->getAccountList($user),
            Response::HTTP_OK);
    }
}
