<?php

namespace HouseFinder\APIBundle\Form\Advertisement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdvertisementListType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('limit', 'integer', array(
                'required'      => true,
            ))
            ->add('page', 'integer', array(
                'required'      => true,
            ))
            ->add('price_from', 'integer', array(
                'required'      => false,
            ))
            ->add('price_to', 'integer', array(
                'required'      => false,
            ))
            ->add('rooms_from', 'integer', array(
                'required'      => false,
            ))
            ->add('rooms_to', 'integer', array(
                'required'      => false,
            ))
            ->add('space_from', 'integer', array(
                'required'      => false,
            ))
            ->add('space_to', 'integer', array(
                'required'      => false,
            ))
            ->add('space_living_from', 'integer', array(
                'required'      => false,
            ))
            ->add('space_living_to', 'integer', array(
                'required'      => false,
            ))
            ->add('type', 'text', array(
                'required'      => false,
            ))
            ->add('level_from', 'integer', array(
                'required'      => false,
            ))
            ->add('level_to', 'integer', array(
                'required'      => false,
            ))
            ->add('house_level_from', 'integer', array(
                'required'      => false,
            ))
            ->add('house_level_to', 'integer', array(
                'required'      => false,
            ))
            ->add('city_id', 'integer', array(
                'required'      => false,
            ))
            ->add('period', 'text', array(
                'required'      => false,
            ))
            ->add("_format", 'text', array(
                'required'      => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return '';
    }

}

