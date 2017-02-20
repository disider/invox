<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Pagination;

use AppBundle\Model\Medium;
use Symfony\Component\Finder\Finder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Knp\Component\Pager\Event\ItemsEvent;

class DirectorySubscriber implements EventSubscriberInterface
{
    public function items(ItemsEvent $event)
    {
        if (is_string($event->target) && is_dir($event->target)) {
            $uploadDir = $event->options['uploadDir'];
            
            $finder = new Finder;
            $finder
                ->files()
                ->depth('< 4') // 3 levels
                ->in($event->target)
            ;
            $iter = $finder->getIterator();
            $files = iterator_to_array($iter);
            $event->count = count($files);
            $event->items = $this->buildFiles(array_slice(
                $files,
                $event->getOffset(),
                $event->getLimit()
            ), $uploadDir);
            $event->stopPropagation();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'knp_pager.items' => ['items', 1/*increased priority to override any internal*/]
        ];
    }

    private function buildFiles($records, $uploadDir)
    {
        $files = [];
        foreach($records as $record) {
            $files[] = new Medium($record, $uploadDir);
        }
        
        return $files;
    }
}