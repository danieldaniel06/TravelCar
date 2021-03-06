<?php
/**
 * Created by PhpStorm.
 * User: danielahmed
 * Date: 13/03/2017
 * Time: 23:24
 */

namespace TravelCarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'form.name', 'translation_domain' => 'TravelCarBundle'))
            ->add('lastName', TextType::class, array(
                'label' => 'form.lastName',
                'translation_domain' => 'TravelCarBundle'
            ))
            ->add('birthDate', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'd/m/yyyy',
                'data' => new \DateTime(),
                'label' => 'form.birthDate',
                'translation_domain' => 'TravelCarBundle',
                'attr'=>array('placeholder'=>'d/m/yyyy', 'class'=>'')
            ))
            ->add('phoneNumber', TextType::class, array(
                'label' => 'form.phoneNumber',
                'translation_domain' => 'TravelCarBundle'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TravelCarBundle\Entity\User'
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'travelcarbundle_user_profile';
    }
}
