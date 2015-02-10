<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2/4/15
 * Time: 3:24 PM
 */

namespace Alahtarin\TranslatableBundle\Form\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class TranslatableRowSubscriber
 *
 * @package Alahtarin\TranslatableBundle\Form\EventListener
 */
class TranslatableRowSubscriber implements EventSubscriberInterface
{
    private $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function onPostSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $valid = false;
        foreach ($this->options['locales'] as $locale) {
            if ($form->get($locale)->getData()) {
                $valid = true;
                break;
            }
        }

        if (! $valid) {
            $form->addError(new FormError('translatable validation error'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::POST_SUBMIT => 'onPostSubmit'];
    }

}
