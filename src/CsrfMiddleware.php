<?php

namespace benliev\Middleware;

use benliev\Middleware\Exceptions\InvalidCsrfException;
use benliev\Middleware\Exceptions\NoCsrfException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Middleware for CSRF protection
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\csrf
 */
class CsrfMiddleware implements MiddlewareInterface
{

    /**
     * @var array|\ArrayAccess $session Session
     */
    private $session;

    /**
     * @var string $sessionKey session key for store tokens
     */
    private $sessionKey;

    /**
     * @var string $formKey form key contains the token
     */
    private $formKey;

    /**
     * @var int $limit limit the number of token to store in the session
     */
    private $limit;

    /**
     * CSRFMiddleware constructor.
     * @param array|\ArrayAccess $session
     * @param int $limit limit the number of token to store in the session
     * @param string $sessionKey session key for store tokens
     * @param string $formKey form key contains the token
     */
    public function __construct(
        &$session,
        int $limit = 50,
        string $sessionKey = 'csrf_tokens',
        string $formKey = '_csrf'
    ) {
        $this->testSession($session);
        $this->session = &$session;
        $this->sessionKey = $sessionKey;
        $this->formKey = $formKey;
        $this->limit = $limit;
    }

    /**
     * @inheritdoc
     * @throws InvalidCsrfException
     * @throws NoCsrfException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        if (in_array($request->getMethod(), ['PUT', 'POST', 'DELETE'])) {
            $params = $request->getParsedBody() ?: [];
            if (!array_key_exists($this->formKey, $params)) {
                throw new NoCsrfException();
            } elseif (!in_array($params[$this->formKey], $this->getTokens())) {
                throw new InvalidCsrfException();
            }
            $this->removeToken($params[$this->formKey]);
        }
        return $delegate->process($request);
    }

    /**
     * Generate and store a random token
     * @return string
     */
    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(16));
        $this->setTokens($this->limitTokens(
            array_merge($this->getTokens(), [$token])
        ));
        return $token;
    }

    /**
     * Test if the session acts as an array
     * @param $session
     * @throws \TypeError
     */
    private function testSession($session): void
    {
        if (!is_array($session) && !$session instanceof \ArrayAccess) {
            throw new \TypeError('Session is not an array');
        }
    }

    /**
     * @return array
     */
    private function getTokens() : array
    {
        return $this->session[$this->sessionKey] ?? [];
    }

    /**
     * @param array $tokens
     */
    private function setTokens(array $tokens)
    {
        $this->session[$this->sessionKey] = $tokens;
    }

    /**
     * Remove a token from session
     * @param string $token
     */
    private function removeToken(string $token): void
    {
        $this->setTokens(array_filter(
            $this->getTokens(),
            function ($t) use ($token) {
                return $token !== $t;
            }
        ));
    }

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    /**
     * @return string
     */
    public function getFormKey(): string
    {
        return $this->formKey;
    }

    /**
     * Limit the number of tokens
     * @param array $tokens
     * @return array
     */
    private function limitTokens(array $tokens): array
    {
        if (count($tokens) > $this->limit) {
            array_shift($tokens);
        }
        return $tokens;
    }
}
