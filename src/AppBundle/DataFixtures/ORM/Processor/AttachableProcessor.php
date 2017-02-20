<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\DataFixtures\ORM\Processor;

use AppBundle\Entity\Attachable;
use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\Filesystem\Filesystem;

class AttachableProcessor implements ProcessorInterface
{

    /**
     * {@inheritdoc}
     */
    public function preProcess($object)
    {
        if (!($object instanceof Attachable)) {
            return;
        }

        $fileUrl = $object->getFileUrl();
        $originalPath = __DIR__ . '/../../../../../../attachments/uploads/';
        $pos = strrpos($fileUrl, '/');

        try {
            if ($pos) {
                $source = $originalPath . $fileUrl;
                $fileUrl = substr($fileUrl, $pos + 1);

                if (!is_file($source)) {
                    throw new \InvalidArgumentException(sprintf('File %s not found', $source));
                }

                $object->setFileUrl($fileUrl);

                $fs = new Filesystem();
                $destination = $object->getUploadRootDir() . '/' . $fileUrl;
                $fs->copy($source, $destination);
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess($object)
    {
    }
}