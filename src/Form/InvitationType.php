<?php
namespace App\Form;

use App\Entity\Invitation;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;



class InvitationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('sender', EntityType::class, [
                'class' => User::class
            ])
            ->add('received', EntityType::class, [
                'class' => User::class
            ])
            ->add('status', ChoiceType::class, 
                [
                'choices' => [
                    'waiting' => '0',
                    'accepted' => '1',
                ]]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invitation::class,
            'csrf_protection' => false,
            'allow_extra_field' => true,
        ]);
    }
}
