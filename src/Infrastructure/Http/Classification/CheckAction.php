<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Classification;

use App\Application\Actions\Action;
use App\Application\UseCase\ClassificationHandler;
use App\Application\UseCase\ClassificationRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

/** new */
final class CheckAction extends Action
{
    public function __construct(
        private readonly ClassificationHandler $handler,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $body = $this->getFormData();
        $classificationRequest = new ClassificationRequest(
            $body['channel'],
            $body['customer_tier'],
            $body['country'],
            $body['subject'],
            $body['tags'],
            $body['customer_age_days'],
        );

        $classificationResponse = ($this->handler)($classificationRequest);

        return $this->respondWithData($classificationResponse->toArray());
    }
}
