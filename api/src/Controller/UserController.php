<?php

namespace App\Controller;

use App\Login\Credentials;
use App\Login\Handler;
use App\Message\Event\LoggedIn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * UserController.
 *
 * @Route(path="/api/v1",name="api_")
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="/login", name="login", methods={"POST","HEAD"})
     */
    public function login(Request $request, Handler $loginhandler)
    {
        $postedData = json_decode($request->getContent());

        if (empty($postedData->email) || empty($postedData->password)) {
            return new JsonResponse(['status' => 'KO', 'message' => 'Please provide username and password'], Response::HTTP_BAD_REQUEST);
        }

        $credential = new Credentials($postedData->email, $postedData->password);
        $result = $loginhandler->authenticate($credential);

        if ($result instanceof LoggedIn) {
            return new JsonResponse([
                'status' => 'OK',
                'data' => ['token' => $result->token],
            ], Response::HTTP_OK);
        }

        return new JsonResponse([
            'status' => 'KO',
            'message' => $result->cause->getMessage(),
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route(path="/logout", name="logout")
     */
    public function logout(Request $request)
    {
        return new JsonResponse(['status' => 'success', 'data' => []], Response::HTTP_OK);
    }
}
