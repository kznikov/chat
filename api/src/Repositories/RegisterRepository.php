<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Enums\Tables;

class RegisterRepository extends Repository
{

    public function addUser(array $data): bool
    {
        $stmt = $this->db
            ->prepare(
                "INSERT INTO 
                    " . Tables::Users->value . "
                SET
                    id = :id,
                    first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    password = :password,
                    active = 1
                    "
            );

        return $stmt->execute($data);
    }

}