<?php

/**
 * @param string $message
 * @return array
 * @throws Exception
 */
function parseYandexPaymentConfirmMessage (string $message)
{
    $matches = [];

    preg_match("/(?P<account>[0-9]{11,20})/i", $message, $matches);
    $account = $matches['account'] ?? null;

    $message = str_replace($account, '', $message);

    preg_match("/(?P<amount>[0-9,\.]+[0-9]+)/i", $message, $matches);
    $amount = $matches['amount'] ? ($matches['amount']) : null;

    $message = str_replace($amount, '', $message);

    preg_match("/(?P<password>[0-9]+)/i", $message, $matches);
    $password = $matches['password'] ?? null;

    if (!$password || !$amount || !$account) {
        throw new \Exception('Ошибка парсинга платженого подтверждения, нужно обновить код функции parseYandexPaymentConfirmMessage!');
    }

    $amount = str_replace('.' , '', $amount);
    $amount = str_replace(',' , '.', $amount);
    $amount = (float) $amount;

    if (!$amount) {
        throw new \Exception('Не удалось преобразовать сумму списания, нужно обновить код функции parseYandexPaymentConfirmMessage!');
    }

    return [$password, $amount, $account];
}

$exampleMessage = "
    Перевод на счет 4100175017397   \n
    Спишется 123,62р.   \n
    Пароль: 4399   \n
    ";

[$password, $amount, $account] = parseYandexPaymentConfirmMessage($exampleMessage);
var_dump([
    'password' => $password,
    'amount' => $amount,
    'account' => $account,
]);
