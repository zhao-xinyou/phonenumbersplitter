<?php
declare(strict_types=1);

namespace Rebib\Phonenumber;

/**
 * @deprecated since 1.2.1
 *
 */
class PhonenumberSplitter extends Splitter
{

    /**
     *
     * @deprecated since version 1.1.5
     * @param string $phonenumber
     * @return Provider
     */
    public function normalize(string $phonenumber): Provider
    {
        return $this->parse($phonenumber);
    }
}
