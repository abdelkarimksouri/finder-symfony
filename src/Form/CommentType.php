<?php
namespace App\Form;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\UserPublished;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;



class CommentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('commentBody', TextAreaType::class)
                
            ->add('userComment', EntityType::class, [
                'class' => User::class
            ])
            ->add('userPublished', EntityType::class, [
                'class' => UserPublished::class
            ])
            ->add('isArchived', ChoiceType::class, 
                [
                'choices' => [
                    '0' => '0',
                    '1' => '1',
                ]]
            )
                
            ->add('isUpdated', ChoiceType::class, 
                [
                'choices' => [
                    '0' => '0',
                    '1' => '1',
                ]]
            )
        ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit'])
        ;
    }
    
    public function onPostSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $isUpdated = $data->getIsUpdated();
        $isArchived = $data->getIsArchived();
        if ($isUpdated) {
            $data->setUpdatedAt(new \DateTime());
        }
        
        if ($isArchived) {
            $data->setArchivedAt(new \DateTime());
        }

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_protection' => false,
            'allow_extra_field' => true,
        ]);
    }
}
