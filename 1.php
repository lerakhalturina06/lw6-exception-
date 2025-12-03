<?php

// Классы исключений
class InvalidAmountException extends Exception {
    public function __construct($message = "Сумма должна быть положительным числом") {
        parent::__construct($message);
    }
}

class InsufficientFundsException extends Exception {
    public function __construct($message = "Недостаточно средств на счете") {
        parent::__construct($message);
    }
}

// Класс BankAccount
class BankAccount {
    private float $balance;
    
    /**
     * Конструктор
     * @param float $initialBalance Начальный баланс
     * @throws InvalidAmountException Если начальный баланс отрицательный
     */
    public function __construct(float $initialBalance = 0.0) {
        if ($initialBalance < 0) {
            throw new InvalidAmountException("Начальный баланс не может быть отрицательным");
        }
        $this->balance = $initialBalance;
    }
    
    /**
     * Получить текущий баланс
     * @return float Текущий баланс
     */
    public function getBalance(): float {
        return $this->balance;
    }
    
    /**
     * Пополнить счет
     * @param float $amount Сумма для пополнения
     * @throws InvalidAmountException Если сумма неположительная
     */
    public function deposit(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidAmountException("Сумма для пополнения должна быть положительной");
        }
        $this->balance += $amount;
    }
    
    /**
     * Снять средства со счета
     * @param float $amount Сумма для снятия
     * @throws InvalidAmountException Если сумма неположительная
     * @throws InsufficientFundsException Если недостаточно средств
     */
    public function withdraw(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidAmountException("Сумма для снятия должна быть положительной");
        }
        
        if ($amount > $this->balance) {
            throw new InsufficientFundsException(
                "Попытка снять {$amount}, но на счете только {$this->balance}"
            );
        }
        
        $this->balance -= $amount;
    }
    
    /**
     * Строковое представление счета
     * @return string Информация о счете
     */
    public function __toString(): string {
        return "Баланс счета: " . number_format($this->balance, 2) . " ₽";
    }
}

// Демонстрационный код
function main() {
    echo "=== Демонстрация работы банковского счета ===\n\n";
    
    try {
        // 1. Создание счета
        echo "1. Создаем банковский счет с начальным балансом 1000 ₽\n";
        $account = new BankAccount(1000.0);
        echo $account . "\n\n";
        
        // 2. Успешное пополнение
        echo "2. Пополняем счет на 500 ₽\n";
        $account->deposit(500.0);
        echo $account . "\n\n";
        
        // 3. Успешное снятие
        echo "3. Снимаем 300 ₽\n";
        $account->withdraw(300.0);
        echo $account . "\n\n";
        
        // 4. Попытка снять больше, чем есть на счете
        echo "4. Пытаемся снять 2000 ₽\n";
        try {
            $account->withdraw(2000.0);
        } catch (InsufficientFundsException $e) {
            echo "Ошибка: " . $e->getMessage() . "\n";
            echo $account . "\n\n";
        }
        
        // 5. Попытка пополнить отрицательной суммой
        echo "5. Пытаемся пополнить счет на -100 ₽\n";
        try {
            $account->deposit(-100.0);
        } catch (InvalidAmountException $e) {
            echo "Ошибка: " . $e->getMessage() . "\n";
            echo $account . "\n\n";
        }
        
        // 6. Попытка снять нулевую сумму
        echo "6. Пытаемся снять 0 ₽\n";
        try {
            $account->withdraw(0.0);
        } catch (InvalidAmountException $e) {
            echo "Ошибка: " . $e->getMessage() . "\n";
            echo $account . "\n\n";
        }
        
        // 7. Создание счета с отрицательным начальным балансом
        echo "7. Пытаемся создать счет с отрицательным балансом (-500 ₽)\n";
        try {
            $invalidAccount = new BankAccount(-500.0);
        } catch (InvalidAmountException $e) {
            echo "Ошибка при создании счета: " . $e->getMessage() . "\n\n";
        }
        
        // 8. Еще несколько операций
        echo "8. Выполняем дополнительные операции:\n";
        echo "   - Снимаем 200 ₽\n";
        $account->withdraw(200.0);
        echo "   " . $account . "\n";
        
        echo "   - Пополняем на 1000 ₽\n";
        $account->deposit(1000.0);
        echo "   " . $account . "\n";
        
        echo "   - Снимаем 500 ₽\n";
        $account->withdraw(500.0);
        echo "   " . $account . "\n";
        
        echo "\n=== Финальный результат ===\n";
        echo $account . "\n";
        
    } catch (Exception $e) {
        echo "Неожиданная ошибка: " . $e->getMessage() . "\n";
    }
}

// Запуск демонстрации
main();