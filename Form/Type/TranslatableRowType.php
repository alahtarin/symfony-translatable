<?php

namespace Alahtarin\TranslatableBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Alahtarin\TranslatableBundle\Form\EventListener\TranslatableRowSubscriber;

/**
* Class TranslatableRowType
 *
 * Adds support for multilanguage inputs.
 *
 */
class TranslatableRowType extends AbstractType
{
    /**
     * @var array
     */
    protected $defaultLocales;

    /**
     * Label class
     */
    const LABEL_CLASS = 'translatable-label';

    /**
     * @param array $locales
     */
    public function __construct($locales = [])
    {
        $this->defaultLocales = $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (count($options['locales']) == 0) {
            throw new \Exception("Translatable form type should be provided with at least one locale");
        }

        foreach ($options['locales'] as $locale) {
            $builder->add($locale, $options['field_type'], [
                'mapped' => false,
                'label' => false
            ]);
        }

        //event listeners
        if ($options['required']) {
            $builder->addEventSubscriber(new TranslatableRowSubscriber($options));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['label_attr'])) {
            $options['label_attr']['class'] = isset($options['label_attr']['class'])
                ? $options['label_attr']['class'] . static::LABEL_CLASS
                : static::LABEL_CLASS;
        } else {
            $options['label_attr'] = ['class' => static::LABEL_CLASS];
        }

        $view->vars = array_merge($view->vars, [
            'locales' => $options['locales'],
            'switch_class' => $options['switch_class'],
            'use_delete' => $options['use_delete'],
            'field_type' => $options['field_type'],
            'label_attr' => $options['label_attr']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => false,
            'locales' => $this->defaultLocales,
            'use_delete' => false,
            'switch_class' => '',
            'field_type' => 'text',
            'required' => true,
            'error_bubbling' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'translatable';
    }

}
