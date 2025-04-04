<?php

declare(strict_types=1);

namespace App\Lib\AciClient;

use App\Lib\AciClient\Contract\Exception\AciClientException;
use App\Lib\AciClient\Contract\Request\CreatePaymentRequest;
use App\Lib\AciClient\Contract\Response\CreatePaymentResponse;
use App\Util\ArrayUtil;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class AciClient
{
    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private NormalizerInterface $normalizer,
        private SerializerInterface $serializer,
    ) {
    }

    public function createPayment(CreatePaymentRequest $createPaymentRequest): CreatePaymentResponse
    {
        $requestBody = $this->normalizer->normalize($createPaymentRequest);

        $request = $this->requestFactory->createRequest('POST', '/v1/payments');
        $request = $request->withHeader('Accept', 'application/json');
        $request = $request->withBody(
            $this->streamFactory->createStream(
                http_build_query(
                    ArrayUtil::flattenArray($requestBody)
                )
            )
        );

        $responseBody = $this->sendRequest($request);

        return $this->serializer->deserialize($responseBody, CreatePaymentResponse::class, JsonEncoder::FORMAT);
    }

    private function sendRequest(RequestInterface $request): string
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new AciClientException($e->getMessage(), $e->getCode(), $e);
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new AciClientException(
                Response::$statusTexts[$response->getStatusCode()] ?? 'unknown status',
                $response->getStatusCode()
            );
        }

        return (string) $response->getBody();
    }
}
