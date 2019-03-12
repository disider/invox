<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonTypeExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['button_class'] = $form->getConfig()->getOption('button_class');
        $view->vars['as_link'] = $form->getConfig()->getOption('as_link');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['button_class', 'as_link']);
    }

    public static function getExtendedTypes()
    {
        return [
            ButtonType::class
        ];
    }
}