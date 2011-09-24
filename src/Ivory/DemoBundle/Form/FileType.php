<?php

namespace Ivory\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Demo form
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class FileType extends AbstractType
{
    /**
     * @override
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('file', 'ajax_file');
    }

    /**
     * @override
     */
    public function getName()
    {
        return 'demo';
    }
}
