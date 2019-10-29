<?php
declare(strict_types=1);

namespace Rebib\Phonenumber;

use Symfony\Component\Yaml\Yaml;

class PhonenumberSplitter
{

    /**
     * Return the phone number with hyphen
     * 
     * @param string $phonenumber
     * @return PhonenumberProvider
     */
    public function parse(string $phonenumber): PhonenumberProvider
    {
        $patternFile = dirname(__FILE__).'/data/Pattern.yml';
        if (!file_exists($patternFile)) {
            return new PhonenumberProvider([$phonenumber]);
        }
        $patterns = Yaml::parseFile($patternFile);

        $newPhonenumber = preg_replace("/[^0-9]/", "", $phonenumber);

        foreach ($patterns as $p_name => $pattern) {
            $method = 'parse'.$p_name.'Number';
            if (!method_exists($this, $method)) {
                continue;
            }
            $provider = $this->{$method}($newPhonenumber, $pattern);
            if ($provider instanceof PhonenumberProvider) {
                return $provider;
            }
        }

        return new PhonenumberProvider([$phonenumber]);
    }

    /**
     *
     * @param string $phonenumber
     * @param array $pattern
     * @return PhonenumberProvider
     */
    private function parseUnfixedNumber(string $phonenumber, array $pattern): ?PhonenumberProvider
    {
        foreach ($pattern as $firstLlen => $prefixList) {
            foreach ($prefixList as $prefix => $secondLen) {
                $prefix = (string) $prefix;
                if (strpos($phonenumber, $prefix) !== 0) {
                    continue;
                }
                return new PhonenumberProvider([$prefix,
                    substr($phonenumber, $firstLlen, $secondLen),
                    substr($phonenumber, $firstLlen + $secondLen)]);
            }
        }
        return null;
    }

    /**
     *
     * @param string $phonenumber
     * @param array $pattern
     * @return PhonenumberProvider
     */
    private function parseFixedNumber(string $phonenumber, array $pattern): ?PhonenumberProvider
    {
        foreach ($pattern as $firstLlen => $prefixList) {
            foreach ($prefixList as $prefix) {
                $prefix = (string) $prefix;
                if (strpos($phonenumber, $prefix) !== 0) {
                    continue;
                }
                return new PhonenumberProvider([$prefix, substr($phonenumber,
                        $firstLlen)]);
            }
        }
        return null;
    }

    /**
     *
     * @param string $phonenumber
     * @param array $pattern
     * @return ?PhonenumberProvider
     */
    private function parseEmergencyNumber(string $phonenumber, array $pattern): ?PhonenumberProvider
    {
        $len = strlen($phonenumber);
        foreach ($pattern as $prefix => $length) {
            $prefix = (string) $prefix;
            if ($length != $len || strpos($phonenumber, $prefix) !== 0) {
                continue;
            }
            return new PhonenumberProvider([$phonenumber]);
        }
        return null;
    }

    /**
     *
     * @deprecated since version 1.1.5
     * @param string $phonenumber
     * @return \Rebib\Phonenumber\PhonenumberProvider
     */
    public function normalize(string $phonenumber): PhonenumberProvider
    {
        return $this->parse($phonenumber);
    }
}
