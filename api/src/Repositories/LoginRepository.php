<?php

declare(strict_types = 1);

namespace App\Repositories;

use PDO;
use App\Enums\Tables;

class LoginRepository extends Repository
{

    public function getUserByEmail(string $email): array
    {
        $stmt = $this->db
            ->prepare(
                "SELECT 
                    * 
                FROM 
                    " . Tables::Users->value . "
                WHERE 
                    email = :email AND
                    active = 1
                    "
            );

        $stmt->execute([
            'email' => $email,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function updateUserToken(string $userId, string $token): bool
    {
        $stmt = $this->db
            ->prepare(
                "UPDATE " . Tables::Users->value . "
                SET 
                    token = :token
                WHERE
                    id = :id"
            );

        return $stmt->execute([
            'id'    => $userId,
            'token' => $token,
        ]);
    }

}