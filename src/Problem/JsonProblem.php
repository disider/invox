<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Problem;

class JsonProblem
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $statusCode;

    public function __construct($title, $statusCode = 400)
    {
        $this->title = $title;
        $this->statusCode = $statusCode;
    }

    public function format()
    {
        return json_encode(
            [
                'title' => $this->title,
            ]
        );
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

}