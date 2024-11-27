<?php

namespace App\Enum;

enum PaymentType: string
{
    case ATM = 'ATM';
    case DIRECT_DEBIT = 'Direct Debit';
    case CARD_PAYMENT = 'Card Payment';
    case BANK_CREDIT = 'Bank Credit';
    case STANDING_ORDER = 'Standing Order';
}
