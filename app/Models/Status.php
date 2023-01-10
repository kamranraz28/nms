<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Status extends Model
{
    const ACTIVE = 1;
    const PENDING = 2;
    const IN_ACTIVE = 0;
    const REJECTED = 98;
    const DELETED = 99;

    const LIST = [
        self::ACTIVE => 'active',
        self::PENDING => 'pending',
        self::IN_ACTIVE => 'inactive',
        self::REJECTED => 'rejected',
        self::DELETED => 'deleted'
    ];

    const EN = [
        0 => 'Inactive',
        1 => 'Active',
    ];
    const BN = [
        0 => 'নিষ্ক্রিয়',
        1 => 'চালু',
    ];

    const EN1 = [
        0 => 'NO',
        1 => 'Yes',
    ];
    const BN1 = [
        0 => 'না',
        1 => 'হ্যাঁ',
    ];

    const EN2 = [
        0 => 'Irregular',
        1 => 'Regular',
    ];
    const BN2 = [
        0 => 'অনিয়মিত',
        1 => 'নিয়মিত',
    ];

    const EN3 = [
        0 => 'Unapproved',
        1 => 'Approved',
    ];
    const BN3 = [
        0 => 'অননুমোদিত',
        1 => 'অনুমোদিত',
    ];
}