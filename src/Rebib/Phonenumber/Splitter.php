<?php
declare(strict_types=1);

namespace Rebib\Phonenumber;

use Symfony\Component\Yaml\Yaml;

class Splitter
{

    /**
     * Return the phone number with hyphen
     * 
     * @param string $phonenumber
     * @return Provider
     */
    public function parse(string $phonenumber): Provider
    {
        $patternFile = dirname(__FILE__).'/data/Pattern.yml';
        if (!file_exists($patternFile)) {
            return new Provider([$phonenumber]);
        }
        $patterns = Yaml::parseFile($patternFile);

        $newPhonenumber = preg_replace("/[^0-9]/", "", $phonenumber);

        foreach ($patterns as $p_name => $pattern) {
            $method = 'parse'.$p_name.'Number';
            if (!method_exists($this, $method)) {
                continue;
            }
            $provider = $this->{$method}($newPhonenumber, $pattern);
            if ($provider instanceof Provider) {
                return $provider;
            }
        }

        return new Provider([$phonenumber]);
    }

    /**
     *
     * @param string $phonenumber
     * @param array $pattern
     * @return Provider
     */
    private function parseUnfixedNumber(string $phonenumber, array $pattern): ?Provider
    {
        foreach ($pattern as $firstLlen => $prefixList) {
            foreach ($prefixList as $prefix => $secondLen) {
                $prefix = (string) $prefix;
                if (strpos($phonenumber, $prefix) !== 0) {
                    continue;
                }
                return new Provider([$prefix,
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
     * @return Provider
     */
    private function parseFixedNumber(string $phonenumber, array $pattern): ?Provider
    {
        foreach ($pattern as $firstLlen => $prefixList) {
            foreach ($prefixList as $prefix) {
                $prefix = (string) $prefix;
                if (strpos($phonenumber, $prefix) !== 0) {
                    continue;
                }
                return new Provider([$prefix, substr($phonenumber, $firstLlen)]);
            }
        }
        return null;
    }

    /**
     *
     * @param string $phonenumber
     * @param array $pattern
     * @return ?Provider
     */
    private function parseEmergencyNumber(string $phonenumber, array $pattern): ?Provider
    {
        $len = strlen($phonenumber);
        foreach ($pattern as $prefix => $length) {
            $prefix = (string) $prefix;
            if ($length != $len || strpos($phonenumber, $prefix) !== 0) {
                continue;
            }
            return new Provider([$phonenumber]);
        }
        return null;
    }
}
