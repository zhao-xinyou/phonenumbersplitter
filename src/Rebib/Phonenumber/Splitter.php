<?php
declare(strict_types=1);

namespace Rebib\Phonenumber;

use Symfony\Component\Yaml\Yaml;

class Splitter
{
    /**
     * @var string[]
     */
    private const PATTERN_ORDER = ['emergency', 'fixed', 'unfixed'];

    /**
     * Return the phone number with hyphen
     *
     * @param string $phonenumber
     * @return Provider
     */
    public function parse(string $phonenumber): Provider
    {
        $patternFile = __DIR__.'/data/Pattern.yml';
        $normalizedPhonenumber = preg_replace('/\D/', '', $phonenumber);
        if (!is_string($normalizedPhonenumber) || $normalizedPhonenumber === '') {
            return new Provider([$phonenumber]);
        }
        if (!file_exists($patternFile)) {
            return new Provider([$normalizedPhonenumber]);
        }

        $patterns = Yaml::parseFile($patternFile);
        if (!is_array($patterns)) {
            return new Provider([$normalizedPhonenumber]);
        }

        foreach (self::PATTERN_ORDER as $patternName) {
            if (!array_key_exists($patternName, $patterns) || !is_array($patterns[$patternName])) {
                continue;
            }
            $provider = match ($patternName) {
                'emergency' => $this->parseEmergencyNumber($normalizedPhonenumber, $patterns[$patternName]),
                'fixed' => $this->parseFixedNumber($normalizedPhonenumber, $patterns[$patternName]),
                'unfixed' => $this->parseUnfixedNumber($normalizedPhonenumber, $patterns[$patternName]),
            };
            if ($provider !== null) {
                return $provider;
            }
        }

        return new Provider([$normalizedPhonenumber]);
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
                if (!str_starts_with($phonenumber, $prefix)) {
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
                if (!str_starts_with($phonenumber, $prefix)) {
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
            if ((int) $length !== $len || !str_starts_with($phonenumber, $prefix)) {
                continue;
            }
            return new Provider([$phonenumber]);
        }
        return null;
    }
}
