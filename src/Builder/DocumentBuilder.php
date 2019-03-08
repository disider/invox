<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Builder;

use App\Entity\Document;

class DocumentBuilder
{
    /** @var string */
    private $cacheDir;

    /** @var \Twig_Environment */
    private $environment;

    public function __construct(\Twig_Environment $environment, $cacheDir)
    {
        $this->environment = $environment;
        $this->cacheDir = $cacheDir;
    }

    public function build(Document $document, $section)
    {
        $isAutoReload = $this->environment->isAutoReload();

        $this->environment->enableAutoReload();
        $template = $document->getDocumentTemplate();
        $company = $document->getCompany();

        $method = 'get' . ucfirst($section);
//
//        $file = $section . '.twig.html';
//        $dir = sprintf('%s/companies/%d/templates/%d', $this->cacheDir, $company->getId(), $template->getId());
//        $path = $dir . '/' . $file;
//
//        @mkdir($dir, 0777, true);
//        file_put_contents($path, $template->$method());

        $content = $this->environment->createTemplate($template->$method());

        if (!$isAutoReload) {
            $this->environment->disableAutoReload();
        }

        return $content->render([
            'document' => $document
        ]);
    }
}
