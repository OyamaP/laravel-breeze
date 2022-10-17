<?php

namespace App\Constants;

class Common
{
    const PRODUCT_ADD = '1';
    const PRODUCT_SUB = '2';
    const PRODUCT_PAY_PROCESS = '3';
    const PRODUCT_PAY_CANCEL = '4';

    const PRODUCT_LIST = [
        'add' => self::PRODUCT_ADD,
        'sub' => self::PRODUCT_SUB,
        'paying' => self::PRODUCT_PAY_PROCESS,
        'cancel' => self::PRODUCT_PAY_CANCEL,
    ];

    const ORDER_RECOMMEND = '0';
    const ORDER_HIGHER = '1';
    const ORDER_LOWER = '2';
    const ORDER_LATOR = '3';
    const ORDER_OLDER = '4';

    const SORT_ORDER = [
        'recommend' => self::ORDER_RECOMMEND,
        'higherPrice' => self::ORDER_HIGHER,
        'lowerPrice' => self::ORDER_LOWER,
        'later' => self::ORDER_LATOR,
        'older' => self::ORDER_OLDER,
    ];

}
