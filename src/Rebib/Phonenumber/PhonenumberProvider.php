<?php

namespace Rebib\Phonenumber;

class PhonenumberProvider
{
    /**
     *
     * @var array
     */
    private $phonenumber;

    /**
     * 
     * @param array $number
     */
    public function __construct(array $number)
    {
        $this->phonenumber = $number;
    }

    /**
     * Return the phone nubmer with hyphen
     * 
     * @return string
     */
    public function getNumberWithHyphen(): string
    {
        return $this->getNumber('-');
    }

    /**
     * Return the phone nubmer without hyphen
     * 
     * @return string
     */
    public function getNumberWithoutHyphen(): string
    {
        return $this->getNumber('');
    }

    /**
     * Return the array of phone number
     * 
     * @return array
     */
    public function toArray(): array
    {
        return $this->phonenumber;
    }

    /**
     *
     * @param string $separator
     * @return string
     */
    private function getNumber(string $separator): string
    {
        return implode($separator, $this->toArray());
    }
}
