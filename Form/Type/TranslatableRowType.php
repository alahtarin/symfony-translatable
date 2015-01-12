<?php

namespace Alahtarin\TranslatableBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            $builder->add($builder->getName() . '_' . $locale, 'text', ['mapped' => false, 'label' => false]);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge($view->vars, [
            'locales' => $options['locales']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => false,
            'locales' => $this->defaultLocales
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
