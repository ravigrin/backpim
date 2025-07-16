<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Ldap;

use Pim\Domain\Repository\Pim\AuthorizationInterface;

final class AuthorizationRepository implements AuthorizationInterface
{
    /**
     * @var string
     */
    private const TARGET_URL = 'ldap://users.integraaal.com';

    /**
     * @var int
     */
    private const TARGET_PORT = 389;

    /**
     * @var string
     */
    private const DOMAIN = '@users.integraaal.com';

    public function login(string $username, string $password): bool
    {
        $ldap = ldap_connect(self::TARGET_URL, self::TARGET_PORT);

        if ($ldap) {
            $bind = ldap_bind($ldap, $username . self::DOMAIN, $password);
            if ($bind) {
                return true;
            }
        }

        return false;
    }
}
