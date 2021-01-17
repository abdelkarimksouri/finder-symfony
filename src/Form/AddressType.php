<?php
namespace App\Form;

use App\Entity\Address;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class AddressType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('streetNumber', TextType::class)
            ->add('streetName', TextType::class)
            ->add('streetComplementary', TextareaType::class)
            ->add('zipCode', IntegerType::class)
            ->add('longitude', TextType::class)
            ->add('latitude', TextType::class)
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => 'false',
                'format' => 'YYYY-MM-dd',
                'attr' => ['data-date-format' => 'YYYY-MM-DD']
            ])
            ->add('updatedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => 'false',
                'format' => 'YYYY-MM-dd',
                'attr' => ['data-date-format' => 'YYYY-MM-DD']
            ])
            ->add('city', TextType::class)
            ->add('country', EntityType::class, [
                'class' => Country::class
            ]);
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'csrf_protection' => false,
            'allow_extra_field' => TRUE
        ]);
    }
}
