<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Controller;

use AppBundle\Model\Module;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/modules")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class ModuleController extends BaseController
{
    /**
     * @Route("", name="modules")
     * @Template
     */
    public function indexAction()
    {
        return [
            'company' => $this->getCurrentCompany(),
            'modules' => Module::getAll(),
        ];
    }

    /**
     * @Route("/{moduleName}/enable", name="module_enable")
     */
    public function enableAction($moduleName)
    {
        $module = new Module($moduleName);

        $company = $this->getCurrentCompany();

        if ($company->hasModule($module)) {
            $this->addFlash('danger', $this->translate('error.module_already_enabled', ['%module%' => $this->translate($module)], 'validators'));
        } else {
            $company->addModule($module);
            $this->save($company);

            $this->addFlash('success', 'flash.module.enabled', ['%module%' => $this->translate($module)]);
        }

        return $this->redirectToRoute('modules');
    }

    /**
     * @Route("/{moduleName}/disable", name="module_disable")
     */
    public function disableAction($moduleName)
    {
        $module = new Module($moduleName);

        $company = $this->getCurrentCompany();

        if (!$company->hasModule($module)) {
            $this->addFlash('danger', $this->translate('error.module_not_enabled', ['%module%' => $this->translate($module)], 'validators'));
        } else {
            $company->removeModule($module);
            $this->save($company);

            $this->addFlash('success', 'flash.module.disabled', ['%module%' => $this->translate($module)]);
        }

        return $this->redirectToRoute('modules');
    }
}
