<?php
namespace App\Form;

use App\Entity\ProfilUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use App\Form\AddressType;


class ProfilType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('ddn', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'YYYY-MM-dd',
                'attr' => ['data-date-format' => 'YYYY-MM-DD']
            ])
            ->add('phoneNumber', IntegerType::class)
            ->add('height', NumberType::class)
            ->add('weight', NumberType::class)
            ->add('address', AddressType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfilUser::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}
