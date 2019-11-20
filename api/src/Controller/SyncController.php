<?php

namespace App\Controller;

use App\Message\Sync\Pipeline as PipelineMessage;
use App\MessageHandler\Sync\PipelineHandler;
use App\Sync\Pipeline;
use App\Sync\PipelineLog;
use App\User\User;
use App\Xero\Vendor;
use Doctrine\ODM\MongoDB\MongoDBException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * SyncController.
 *
 * @Route(path="/api/v1/pipeline",name="api_")
 */
class SyncController extends AbstractController
{
    /**
     * @Route(path="", name="create", methods={"POST"})
     */
    public function index(Vendor $account, Pipeline $pipelineManager, Request $request, User $userManager, LoggerInterface $logger, PipelineHandler $pipelinehandler)
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

        try {
            $pipeline = $pipelineManager->createPipeline($user, Pipeline::OPERATION_XERO_SYNC);

            $logger->info('Initialized Pipeline Pushing to Queue', [
                'user' => $pipeline->getUser()->getUserId(),
                'operation' => $pipeline->getOperation(),
                'pipeline_id' => $pipeline->getPipelineId(),
            ]);
            $this->dispatchMessage(new PipelineMessage($pipeline));
            //$pipelinehandler(new PipelineMessage($pipeline));
        } catch (MongoDBException $mongoDBException) {
            return new JsonResponse(['status' => 'KO', 'message' => 'Sync is initialized failed'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'OK', 'message' => 'Sync is initiated successfully', 'data' => $pipeline->toArray()], Response::HTTP_CREATED);
    }

    /**
     * @Route(path="", name="get_list", methods={"GET"})
     */
    public function getPipelines(Pipeline $pipelineManager, Request $request, User $userManager)
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

        try {
            $pipelines = $pipelineManager->getPipelineList($user);
        } catch (MongoDBException $mongoDBException) {
            return new JsonResponse(['status' => 'KO', 'message' => 'Failed to fetch sync list'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'OK', 'message' => 'Pipelines fetched successfully', 'count' => count($pipelines), 'data' => $pipelines], Response::HTTP_OK);
    }

    /**
     * @Route(path="/logs/{pipelineId}", name="get_logs", methods={"GET"})
     */
    public function getPipelineLogs(PipelineLog $pipelineLog, Request $request, User $userManager, string $pipelineId): JsonResponse
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

        try {
            $pipelineLogs = $pipelineLog->getLogs($pipelineId);
        } catch (MongoDBException $mongoDBException) {
            return new JsonResponse(['status' => 'KO', 'message' => 'Failed to fetch pipline logs'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'OK', 'message' => 'Pipeline logs fetched successfully', 'count' => count($pipelineLogs), 'data' => $pipelineLogs], Response::HTTP_OK);
    }
}
