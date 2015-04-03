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
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TranslatableRowSubscriber
 *
 * @package Alahtarin\TranslatableBundle\Form\EventListener
 */
class TranslatableRowSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator
     * @param array               $options
     */
    public function __construct(TranslatorInterface $translator, array $options)
    {
        $this->options      = $options;
        $this->translator   = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function onPostSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $method = $this->options['validation_method'];

        if ($this->options['required']) {
            $valid = $method === 'one' ? false : true;

            foreach ($this->options['locales'] as $locale) {
                if ($form->get($locale)->getData() && $method === 'one') {
                    $valid = true;
                    break;
                } elseif (!$form->get($locale)->getData() && $method === 'all') {
                    $valid = false;
                    break;
                }
            }
        } else {
            $valid = true;
        }

        if ($valid && $this->options['requirements']) {
            $req = $this->options['requirements'];

            $errors = [];
            foreach ($this->options['locales'] as $locale) {
                if ($data = $form->get($locale)->getData()) {
                    if (isset($req['min']) && !isset($errors['min']) && mb_strlen(strip_tags($data), 'UTF8') < $req['min']) {
                        $message = $this->translator->transChoice(
                            'translatable validation error min %count%',
                            $req['min'],
                            ['%count%' => $req['min']]
                        );

                        $form->addError(new FormError($message));
                        $errors['min'] = true;
                    }

                    if (isset($req['max']) && !isset($errors['max']) && mb_strlen(strip_tags($data), 'UTF8') > $req['max']) {
                        $message = $this->translator->transChoice(
                            'translatable validation error max %count%',
                            $req['max'],
                            ['%count%' => $req['max']]
                        );

                        $form->addError(new FormError($message));
                        $errors['max'] = true;
                    }
                }
            }
        }

        if (! $valid) {
            $form->addError(new FormError('translatable validation error ' . $method));
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
