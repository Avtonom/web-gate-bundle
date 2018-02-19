<?php

namespace Avtonom\WebGateBundle\Monolog\Processor;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\User;

class UserProcessor
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function processRecord(array $record)
    {
        $user = ($this->tokenStorage->getToken()) ? $this->tokenStorage->getToken()->getUser() : null;

        if ($user instanceof User) {
            $record['extra']['user'] = $user->getUsername();
        } else {
            $record['extra']['user'] = null;
        }

        return $record;
    }
}