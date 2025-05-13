<?php

namespace Src\Auth;

interface IdentityInterface
{
    // Попытка аутентификации по учетным данным
    public function attemptIdentity(array $credentials): ?self;

    // Поиск пользователя по ID
    public function findIdentity(int $id): ?self;

    // Получение ID пользователя
    public function getId(): int;
}