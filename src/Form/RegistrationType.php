<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prenom", "Votre prenom..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre email..."))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profile", "url de votre avatar..."))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Votre mot de passe..."))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("confirmation de mot de passe", "Confirmez votre mot de passe..."))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Presentez vous en quelque mots"))
            ->add('description', TextareaType::class, $this->getConfiguration("Description detaillée", "c'est le moment de vous presenter..."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
