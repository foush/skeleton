<?php
namespace Application\View\Helper;

class Money extends Base
{
    public function __invoke($amount)
    {
        setlocale(LC_MONETARY, 'en_US');

        return money_format('%(#0n', floatval(preg_replace('/[^\d|\.|\-]/', '', $amount)));
    }

}
