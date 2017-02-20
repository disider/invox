<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Tests\Mock;

use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;

class InteractorStub extends AbstractInteractor
{
    private $data;
    private $method;
    private $request;

    public function __construct($data, $method)
    {
        $this->data = $data;
        $this->method = $method;
    }

    public function process(Request $request, Presenter $presenter)
    {
        $this->request = $request;
        $method = $this->method;

        $presenter->$method($this->data);
    }

    public function getRequest()
    {
        return $this->request;
    }
}
