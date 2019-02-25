<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\AppBundle\Mock;

use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;

class ErrorInteractor extends AbstractInteractor
{
    /** @var string */
    private $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    public function process(Request $request, Presenter $presenter)
    {
        $presenter->setErrors([$this->error]);
    }
}
