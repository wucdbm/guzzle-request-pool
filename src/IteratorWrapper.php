<?php

/*
 * This file is part of the Wucdbm\GuzzleHttp package.
 *
 * Copyright (c) Martin Kirilov <wucdbm@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\GuzzleHttp;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;

class IteratorWrapper implements \Iterator {

    /** @var ClientInterface */
    protected $client;

    /** @var array */
    protected $options;

    /** @var \Iterator */
    protected $iterator;

    /** @var PromiseInterface[] */
    protected $promises = [];

    public function __construct(ClientInterface $client, array $options, $requests) {
        $this->client = $client;
        $this->options = $options;
        if ($requests instanceof \Iterator) {
            $this->iterator = $requests;
        } elseif (is_array($requests)) {
            $this->iterator = new \ArrayIterator($requests);
        } else {
            $this->iterator = new \ArrayIterator([$requests]);
        }
    }

    public function current() {
        $key = $this->key();

        if (!isset($this->promises[$key])) {
            $rfn = $this->iterator->current();

            if ($rfn instanceof RequestInterface) {
                $this->promises[$key] = $this->client->sendAsync($rfn, $this->options);
            } elseif (is_callable($rfn)) {
                $this->promises[$key] = $rfn($this->options);
            } else {
                throw new \InvalidArgumentException('Each value yielded by '
                    .'the iterator must be a Psr7\Http\Message\RequestInterface '
                    .'or a callable that returns a promise that fulfills '
                    .'with a Psr7\Message\Http\ResponseInterface object.');
            }
        }

        return $this->promises[$key];
    }

    public function next() {
        $this->iterator->next();
    }

    public function key() {
        return $this->iterator->key();
    }

    public function valid() {
        return $this->iterator->valid();
    }

    public function rewind() {
        $this->iterator->rewind();
        $this->promises = [];
    }
}
