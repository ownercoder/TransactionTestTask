<?php
namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use App\Exceptions\CashOutBalanceException;

/**
 * Class ModelUser
 *
 * @package App\Models
 */
class ModelUser extends Model
{
    /**
     * Получает пользователя по логину
     *
     * @param string $login Логин пользователя
     *
     * @return \stdClass
     */
    public function getUser($login)
    {
        $pdo = Database::instance()->getPdo();
        $statement = $pdo->prepare('SELECT * FROM `users` WHERE `login` = :username LIMIT 1');
        $statement->execute(['username' => $login]);
        return $statement->fetchObject();
    }

    /**
     * Выполняет списание с баланса пользователя
     *
     * @param \stdClass $user
     * @param double $cashOutSum
     *
     * @return double
     * @throws CashOutBalanceException
     */
    public function cashOutUserBalance($user, $cashOutSum)
    {
        $pdo = Database::instance()->getPdo();
        $pdo->beginTransaction();

        $statement = $pdo->prepare('SELECT `balance` FROM `users` WHERE `id` = :id FOR UPDATE');
        $statement->execute(['id' => $user->id]);
        $currentBalance = doubleval($statement->fetchColumn(0));

        $errorMessage = '';

        if ($cashOutSum > $currentBalance) {
            $errorMessage = 'You cannot cash out cashOutSum greater than you have on balance';
        } elseif ($cashOutSum <= 0) {
            $errorMessage = 'Please enter greater than 0';
        }

        if (!empty($errorMessage)) {
            $pdo->rollBack();
            throw new CashOutBalanceException($errorMessage);
        }

        $newBalance = $currentBalance - $cashOutSum;

        $statement = $pdo->prepare('UPDATE `users` SET `balance` = :balance WHERE `id` = :id');
        $statement->execute(['balance' => $newBalance, 'id' => $user->id]);
        $pdo->commit();
        return $newBalance;
    }
}
