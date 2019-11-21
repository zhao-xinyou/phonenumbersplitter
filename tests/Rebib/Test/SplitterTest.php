<?php
declare(strict_types=1);

namespace Rebib\Test;

use Rebib\Phonenumber\Splitter;
use PHPUnit\Framework\TestCase;

class SplitterTest extends TestCase
{

    public function testNormalize(): void
    {
        $phonenumbers = [
            '031234-5678' => '03-1234-5678',
            '11-0' => '110',
            '15-7' => '157',
            '00339873783' => '0033-9873783',
            '0091918293839' => '009191-8293839',
            '01558384-94' => '01558-3-8494',
            '08387372827828' => '08387-3-72827828',
            '077034948494' => '0770-34-948494',
            '01202514526262' => '0120-251-4526262',
            '054123456' => '054-123-456',
            '05078273831' => '050-7827-3831',
            '06078273831' => '06-0782-73831',
            '040782-73831' => '04-0782-73831',
            '031-234-5678' => '03-1234-5678',
            '0801-234-5678' => '080-1234-5678',
        ];

        $phonenumberSpliter = new Splitter();
        foreach ($phonenumbers as $phonenumber => $normalizedPhonenumber) {
            $provider = $phonenumberSpliter->parse($phonenumber);

            $this->assertEquals($normalizedPhonenumber,
                $provider->getNumberWithHyphen());
        }
    }
}
