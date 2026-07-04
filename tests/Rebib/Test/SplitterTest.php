<?php
declare(strict_types=1);

namespace Rebib\Test;

use Rebib\Phonenumber\Splitter;
use PHPUnit\Framework\TestCase;

final class SplitterTest extends TestCase
{
    public function testNormalize(): void
    {
        $phonenumbers = $this->normalizedPhoneNumberDataProvider();

        $phonenumberSplitter = new Splitter();
        foreach ($phonenumbers as $label => $example) {
            [$phonenumber, $normalizedPhonenumber] = $example;
            $provider = $phonenumberSplitter->parse($phonenumber);

            $this->assertSame(
                $normalizedPhonenumber,
                $provider->getNumberWithHyphen(),
                $label
            );
        }
    }

    public function testGetNumberWithoutHyphen(): void
    {
        $phonenumberSplitter = new Splitter();
        $provider = $phonenumberSplitter->parse('031-234-5678');

        $this->assertSame('0312345678', $provider->getNumberWithoutHyphen());
        $this->assertSame(['03', '1234', '5678'], $provider->toArray());
    }

    public function testFallbackReturnsDigitsOnlyForUnknownNumber(): void
    {
        $phonenumberSplitter = new Splitter();
        $provider = $phonenumberSplitter->parse('abc-111-2222-3333');

        $this->assertSame('11122223333', $provider->getNumberWithHyphen());
        $this->assertSame('11122223333', $provider->getNumberWithoutHyphen());
        $this->assertSame(['11122223333'], $provider->toArray());
    }

    private function normalizedPhoneNumberDataProvider(): array
    {
        return [
            'mobile with missing hyphen' => ['031234-5678', '03-1234-5678'],
            'emergency' => ['11-0', '110'],
            'emergency-short' => ['15-7', '157'],
            'global-internal' => ['00339873783', '0033-9873783'],
            'ip-phone' => ['0091918293839', '009191-8293839'],
            'special-with-digits' => ['01558384-94', '01558-3-8494'],
            'special-long' => ['08387372827828', '08387-3-72827828'],
            'fukuoka-special' => ['077034948494', '0770-34-948494'],
            'free-dial' => ['01202514526262', '0120-251-4526262'],
            'mobile-local' => ['054123456', '054-123-456'],
            'ip-mobile' => ['05078273831', '050-7827-3831'],
            'non-generic' => ['06078273831', '060-782-73831'],
            'with-area-hyphen' => ['040782-73831', '04-0782-73831'],
            'standard-format' => ['031-234-5678', '03-1234-5678'],
            'other-mobile' => ['0801-234-5678', '080-123-45678'],
        ];
    }
}
