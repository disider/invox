<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextEditorType extends AbstractType
{

    public function getParent()
    {
        return TextareaType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'filebrowsers' => [
                    'ImageUpload',
                    'ImageBrowse',
                ],
                'config' => [
                    'filebrowserImageBrowseRoute' => 'media_browse',
                    'filebrowserImageBrowseRouteAbsolute' => false,
                    'filebrowserImageUploadRoute' => '_uploader_upload_medium',
                    'filebrowserImageUploadRouteAbsolute' => false,
                ],
                'attr' => [
                    'class' => 'form-control text-editor',
                ],
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'textEditor';
    }

}
