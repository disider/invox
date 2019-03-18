<?php

namespace App\Twig;

use Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode;
use Twig_Extension;
use Twig_SimpleFunction;

class BootstrapFormExtension extends Twig_Extension
{
    /** @var string */
    private $style;
    /** @var string */
    private $colSize = 'lg';
    /** @var integer */
    private $widgetCol = 10;
    /** @var integer */
    private $labelCol = 2;
    /** @var integer */
    private $simpleCol = false;
    /** @var array */
    private $settingsStack = [];

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('bootstrap_set_style', [$this, 'setStyle']),
            new Twig_SimpleFunction('bootstrap_get_style', [$this, 'getStyle']),
            new Twig_SimpleFunction('bootstrap_set_col_size', [$this, 'setColSize']),
            new Twig_SimpleFunction('bootstrap_get_col_size', [$this, 'getColSize']),
            new Twig_SimpleFunction('bootstrap_set_widget_col', [$this, 'setWidgetCol']),
            new Twig_SimpleFunction('bootstrap_get_widget_col', [$this, 'getWidgetCol']),
            new Twig_SimpleFunction('bootstrap_set_label_col', [$this, 'setLabelCol']),
            new Twig_SimpleFunction('bootstrap_get_label_col', [$this, 'getLabelCol']),
            new Twig_SimpleFunction('bootstrap_set_simple_col', [$this, 'setSimpleCol']),
            new Twig_SimpleFunction('bootstrap_get_simple_col', [$this, 'getSimpleCol']),
            new Twig_SimpleFunction('bootstrap_backup_form_settings', [$this, 'backupFormSettings']),
            new Twig_SimpleFunction('bootstrap_restore_form_settings', [$this, 'restoreFormSettings']),
            new Twig_SimpleFunction(
                'checkbox_row',
                null,
                ['is_safe' => ['html'], 'node_class' => SearchAndRenderBlockNode::class]
            ),
            new Twig_SimpleFunction(
                'radio_row',
                null,
                ['is_safe' => ['html'], 'node_class' => SearchAndRenderBlockNode::class]
            ),
            new Twig_SimpleFunction(
                'global_form_errors',
                null,
                ['is_safe' => ['html'], 'node_class' => SearchAndRenderBlockNode::class]
            ),
            new Twig_SimpleFunction(
                'form_control_static',
                [$this, 'formControlStaticFunction'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * Sets the style.
     *
     * @param string $style Name of the style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * Returns the style.
     *
     * @return string Name of the style
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Sets the column size.
     *
     * @param string $colSize Column size (xs, sm, md or lg)
     */
    public function setColSize($colSize)
    {
        $this->colSize = $colSize;
    }

    /**
     * Returns the column size.
     *
     * @return string Column size (xs, sm, md or lg)
     */
    public function getColSize()
    {
        return $this->colSize;
    }

    /**
     * Sets the number of columns of widgets.
     *
     * @param integer $widgetCol Number of columns.
     */
    public function setWidgetCol($widgetCol)
    {
        $this->widgetCol = $widgetCol;
    }

    /**
     * Returns the number of columns of widgets.
     *
     * @return integer Number of columns.Class
     */
    public function getWidgetCol()
    {
        return $this->widgetCol;
    }

    /**
     * Sets the number of columns of labels.
     *
     * @param integer $labelCol Number of columns.
     */
    public function setLabelCol($labelCol)
    {
        $this->labelCol = $labelCol;
    }

    /**
     * Returns the number of columns of labels.
     *
     * @return integer Number of columns.
     */
    public function getLabelCol()
    {
        return $this->labelCol;
    }

    /**
     * Sets the number of columns of simple widgets.
     *
     * @param integer $simpleCol Number of columns.
     */
    public function setSimpleCol($simpleCol)
    {
        $this->simpleCol = $simpleCol;
    }

    /**
     * Returns the number of columns of simple widgets.
     *
     * @return integer Number of columns.
     */
    public function getSimpleCol()
    {
        return $this->simpleCol;
    }

    /**
     * Backup the form settings to the stack.
     *
     * @internal Should only be used at the beginning of form_start. This allows
     *           a nested subform to change its settings without affecting its
     *           parent form.
     */
    public function backupFormSettings()
    {
        $settings = [
            'style' => $this->style,
            'colSize' => $this->colSize,
            'widgetCol' => $this->widgetCol,
            'labelCol' => $this->labelCol,
            'simpleCol' => $this->simpleCol,
        ];
        array_push($this->settingsStack, $settings);
    }

    /**
     * Restore the form settings from the stack.
     *
     * @internal Should only be used at the end of form_end.
     * @see backupFormSettings
     */
    public function restoreFormSettings()
    {
        if (count($this->settingsStack) < 1) {
            return;
        }
        $settings = array_pop($this->settingsStack);
        $this->style = $settings['style'];
        $this->colSize = $settings['colSize'];
        $this->widgetCol = $settings['widgetCol'];
        $this->labelCol = $settings['labelCol'];
        $this->simpleCol = $settings['simpleCol'];
    }

    /**
     * @param string $label
     * @param string $value
     *
     * @return string
     */
    public function formControlStaticFunction($label, $value)
    {
        return sprintf(
            '<div class="form-group"><label class="col-sm-%s control-label">%s</label><div class="col-sm-%s"><p class="form-control-static">%s</p></div></div>',
            $this->getLabelCol(),
            $label,
            $this->getWidgetCol(),
            $value
        );
    }
}