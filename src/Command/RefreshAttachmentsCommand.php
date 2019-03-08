<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Command;

use App\Entity\Attachable;
use App\Entity\DocumentAttachment;
use App\Entity\PettyCashNoteAttachment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class RefreshAttachmentsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('invox:attachments:refresh')
            ->setDescription('Refresh document attachments');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $attachments = $this->entityManager->getRepository(DocumentAttachment::class)->findAll();
        $this->refreshAttachments($output, $attachments);

        $attachments = $this->entityManager->getRepository(PettyCashNoteAttachment::class)->findAll();
        $this->refreshAttachments($output, $attachments);
    }

    protected function refreshAttachments(OutputInterface $output, $attachments)
    {
        /** @var Attachable $attachment */
        foreach ($attachments as $attachment) {
            $fileUrl = $attachment->getFileUrl();
            $originalPath = __DIR__.'/../../../../../../attachments/uploads/';
            $pos = strrpos($fileUrl, '/');

            try {
                if ($pos) {
                    $source = $originalPath.$fileUrl;
                    $fileUrl = substr($fileUrl, $pos + 1);

                    if (!is_file($source)) {
                        throw new \InvalidArgumentException(sprintf('File %s not found', $source));
                    }

                    $attachment->setFileUrl($fileUrl);

                    $fs = new Filesystem();
                    $destination = $attachment->getUploadRootDir().'/'.$fileUrl;
                    $fs->copy($source, $destination);
                    $this->entityManager->persist($attachment);
                }
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
        }

        $this->entityManager->flush();
    }

}