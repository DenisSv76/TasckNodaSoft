<?php

namespace Manager;

use Gateway\User as UserGateway;
use Gateway\Connect;

class User
{
    const limit = 10;

    private UserGateway $userGateway;

    public function __constructor(UserGateway $userGateway) {
        $this->userGateway = $userGateway;
    }

    /**
     * Возвращает пользователей старше заданного возраста.
     * @return UserGateway[]
     */
    public function getUsersByAge(int $ageFrom): array
    {
        return $this->userGateway->getUsersByAge($ageFrom);
    }

    /**
     * Возвращает пользователей по списку имен.
     * @param string[]
     * @return UserGateway[]
     */
    public function getUsersByNames(array $names): array
    {
        $users = [];
        foreach ($names as $name) {
            $users[] = $this->userGateway->getUserByName($name);
        }

        return $users;
    }

    /**
     * Добавляет пользователей в базу данных.
     * @param mixed[]
     * @return int[]
     */
    public function addUsers(array $users): array
    {
        $ids = [];
        Connect::getInstance()->beginTransaction();
        foreach ($users as $user) {
            try {
                $this->userGateway->addUser($user['name'], $user['lastName'], $user['age']);
                Connect::getInstance()->commit();
                $ids[] = UserGateway::getInstance()->lastInsertId();
            } catch (\Exception $e) {
                Connect::getInstance()->rollBack();
            }
        }

        return $ids;
    }
}