<?php

namespace Gateway;

use Manager\User as UserManager;
use Gateway\Connect;
use PDO;

class User
{
    /**
     * Возвращает список пользователей старше заданного возраста.
     * @return mixed[]
     */
    public function getUsersByAge(int $ageFrom): array
    {
        $stmt = Connect::getInstance()->prepare("SELECT id, name, lastName, from, age, settings FROM Users WHERE age > {$ageFrom} LIMIT " . UserManager::limit);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        foreach ($rows as $row) {
            $settings = json_decode($row['settings']);
            $users[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'lastName' => $row['lastName'],
                'from' => $row['from'],
                'age' => $row['age'],
                'key' => $settings['key'],
            ];
        }

        return $users;
    }

    /**
     * Возвращает пользователя по имени.
     * @return array
     */
    public function getUserByName(string $name): array
    {
        $stmt = Connect::getInstance()->prepare("SELECT id, name, lastName, from, age, settings FROM Users WHERE name = {$name}");
        $stmt->execute();
        $user_by_name = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'id' => $user_by_name['id'],
            'name' => $user_by_name['name'],
            'lastName' => $user_by_name['lastName'],
            'from' => $user_by_name['from'],
            'age' => $user_by_name['age'],
        ];
    }

    /**
     * Добавляет пользователя в базу данных.
     */
    public function addUser(string $name, string $lastName, int $age): string
    {
        $sth = Connect::getInstance()->prepare("INSERT INTO Users (name, lastName, age) VALUES (:name, :age, :lastName)");
        $sth->execute([':name' => $name, ':age' => $age, ':lastName' => $lastName]);

        return Connect::getInstance()->lastInsertId();
    }
}