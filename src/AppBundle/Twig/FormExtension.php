<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode;

class FormExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {

        $options = [
            'node_class' => SearchAndRenderBlockNode::class,
            'is_safe' => ['html'],
        ];
        return [
            new \Twig_SimpleFunction('form_javascript', null, $options),
            new \Twig_SimpleFunction('form_stylesheet', null, $options),
        ];
    }

}