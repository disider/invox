<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\DataFixtures\ORM\Processor;

use App\Entity\Attachable;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Filesystem\Filesystem;

class AttachableProcessor implements ProcessorInterface
{

    /**
     * {@inheritdoc}
     */
    public function preProcess(string $id, $object): void
    {
        if (!($object instanceof Attachable)) {
            return;
        }

        $fileUrl = $object->getFileUrl();
        $originalPath = __DIR__.'/../../../../../../attachments/uploads/';
        $pos = strrpos($fileUrl, '/');

        try {
            if ($pos) {
                $source = $originalPath.$fileUrl;
                $fileUrl = substr($fileUrl, $pos + 1);

                if (!is_file($source)) {
                    throw new \InvalidArgumentException(sprintf('File %s not found', $source));
                }

                $object->setFileUrl($fileUrl);

                $fs = new Filesystem();
                $destination = $object->getUploadRootDir().'/'.$fileUrl;
                $fs->copy($source, $destination);
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess(string $id, $object): void
    {
    }
}