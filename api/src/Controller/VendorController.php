<?php

namespace App\Controller;

use App\User\User;
use App\Vendor\Vendor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * VendorController.
 *
 * @Route(path="/api/v1",name="api_")
 */
class VendorController extends AbstractController
{
    /**
     * @Route(path="/vendors", name="vendors")
     */
    public function getContactList(Vendor $vendor, Request $request, User $userManager): JsonResponse
    {
        $token = $request->headers->get('api-token');

        if (null === $token) {
            return new JsonResponse([
                'status' => 'KO', 'message' => 'Please pass api-token in header',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (null === ($user = $userManager->getUserFromToken($token))) {
            return new JsonResponse([
                'status' => 'KO', 'message' => 'Invalid token',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Vendors Fetched Succssfully',
        ] + $vendor->getVendorList($user), Response::HTTP_OK);
    }
}
