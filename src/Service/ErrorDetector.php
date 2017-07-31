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
use AnimeDb\Bundle\ShikimoriBrowserBundle\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;

class ErrorDetector
{
    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    public function detect(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 404) {
            throw NotFoundException::page();
        }

        $content = $response->getBody()->getContents();

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

        return $data;
    }
}
