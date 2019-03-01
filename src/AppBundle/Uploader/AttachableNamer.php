<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Uploader;

use AppBundle\Entity\Manager\CompanyManager;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;

class AttachableNamer implements NamerInterface
{
    /**
     * @var CompanyManager
     */
    private $companyManager;

    public function __construct(CompanyManager $companyManager)
    {
        $this->companyManager = $companyManager;
    }

    public function name(FileInterface $file)
    {
        $company = $this->companyManager->getCurrent();

        $name = sprintf('%s/%s', $company->getAttachmentsUploadDir(), uniqid() . '.' . $file->getExtension());

        return $name;
    }
}