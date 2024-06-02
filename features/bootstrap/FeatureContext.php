<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

class FeatureContext implements Context
{
    private Client $client;
    private ?ResponseInterface $response = null;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:9000',
            'http_errors' => false,
        ]);
    }

    /**
     * @Given I send a :method request to :url with body:
     */
    public function iSendARequestToWithBody(string $method, string $url, PyStringNode $string): void
    {
        $body = json_decode($string->getRaw(), true);
        $this->response = $this->client->request($method, $url, [
            'json' => $body,
        ]);
    }

    /**
     * @Given I send a :method request to :url
     */
    public function iSendARequestTo(string $method, string $url): void
    {
        $this->response = $this->client->request($method, $url);
    }

    /**
     * @Then the response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe(int $statusCode): void
    {
        if ($this->response === null) {
            throw new Exception('No response received');
        }

        if ($this->response->getStatusCode() !== $statusCode) {
            throw new Exception(
                'Actual status code is ' . $this->response->getStatusCode()
            );
        }
    }

    /**
     * @Then the response should be empty
     */
    public function theResponseShouldBeEmpty(): void
    {
        if ($this->response === null) {
            throw new Exception('No response received');
        }

        $body = (string) $this->response->getBody();
        if (!empty($body)) {
            throw new Exception(
                'Response body is not empty: ' . $body
            );
        }
    }

    /**
     * @Then the response body should be:
     */
    public function theResponseBodyShouldBe(PyStringNode $expectedResponse)
    {
        $actualResponse = (string) $this->response->getBody();
        Assert::assertJsonStringEqualsJsonString(
            $expectedResponse->getRaw(),
            $actualResponse,
            "Expected response body to be {$expectedResponse->getRaw()} but got $actualResponse"
        );
    }
}
