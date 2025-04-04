<?php

declare(strict_types=1);

namespace App\Lib\Shift4Client;

use App\Lib\Shift4Client\Contract\Exception\Shift4ClientException;
use App\Lib\Shift4Client\Contract\Request\CreateChargeRequest;
use App\Lib\Shift4Client\Contract\Response\CreateChargeResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly class Shift4Client
{
    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private SerializerInterface $serializer,
    ) {
    }

    public function createCharge(CreateChargeRequest $createChargeRequest): CreateChargeResponse
    {
        $requestBody = $this->serializer->serialize($createChargeRequest, JsonEncoder::FORMAT);

        $request = $this->requestFactory->createRequest('POST', '/charges');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withHeader('Accept', 'application/json');
        $request = $request->withBody(
            $this->streamFactory->createStream($requestBody)
        );

        $responseBody = $this->sendRequest($request);

        return $this->serializer->deserialize($responseBody, CreateChargeResponse::class, JsonEncoder::FORMAT);
    }

    private function sendRequest(RequestInterface $request): string
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new Shift4ClientException($e->getMessage(), $e->getCode(), $e);
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new Shift4ClientException(
                Response::$statusTexts[$response->getStatusCode()] ?? 'unknown status',
                $response->getStatusCode()
            );
        }

        return (string) $response->getBody();
    }
}
