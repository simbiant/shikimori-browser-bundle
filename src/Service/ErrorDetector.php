<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriBrowserBundle\Service;

use AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\ErrorException;

class ErrorDetector
{
    /**
     * @param string $content
     *
     * @return array
     */
    public function detect($content)
    {
        if ($content == '') {
            return [];
        }

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ErrorException::invalidResponse(json_last_error_msg(), json_last_error());
        }

        if (!empty($data['code'])) {
            throw ErrorException::failed(
                isset($data['message']) ? $data['message'] : '',
                $data['code']
            );
        }

        return (array) $data;
    }
}
