<?php
namespace App\Form;

use App\Entity\UserPublished;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class UserPublishedType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('publishedText', TextareaType::class)
            ->add('userPublished', EntityType::class, [
                'class' => User::class
            ])
            
            ->add('mediaId', IntegerType::class)
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
            'data_class' => UserPublished::class,
            'csrf_protection' => false,
            'allow_extra_field' => true,
        ]);
    }
}
