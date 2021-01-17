<?php
namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;




class MessageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('sender', EntityType::class, [
                'class' => User::class
            ])
            ->add('received', EntityType::class, [
                'class' => User::class
            ])
            ->add('body', TextareaType::class, [
                'attr' => ['class' => 'tinymce']
            ])          
            ->add('archived', ChoiceType::class, 
                [
                'choices' => [
                    'waiting' => '0',
                    'accepted' => '1',
                ]]
            )
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit'])
        ;
    }

    public function onPostSubmit(FormEvent $event)
    {
        $data = $event->getData();
       
        $isArchived = $data->getArchived();
        if ($isArchived) {
            $data->setArchivedAt(new \DateTime());
        }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'csrf_protection' => false,
            'allow_extra_field' => true,
            'validation_groups' => ["updtMessage"]
        ]);
    }
}
