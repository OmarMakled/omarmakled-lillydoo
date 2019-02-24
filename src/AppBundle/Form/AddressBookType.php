<?php

/*
 * This file is part of the www.lillydoo.com test.
 *
 * @author Omar Makled <omar.makled@gmail.com.com>
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\AddressBook;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AddressBookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('country')
            ->add('city')
            ->add('address')
            ->add('zip')
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('photo', FileType::class, [
                'data_class' => null,
                'attr' => ['accept' => 'image/x-png, image/jpeg'],
                'required' => false,
                'mapped' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddressBook::class,
        ]);
    }
}
