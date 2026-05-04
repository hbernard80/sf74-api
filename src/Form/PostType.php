<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $post = $options['data'];

        // Champ Id uniquement si formulaire de modif
        if ($post && null !== $post->getId() ) 
        {
            $builder->add('id', TextType::class, [
                'label' => 'global.id',
                'translation_domain' => 'forms',
                'disabled' => true,
                'mapped' => false,
                'data' => $post->getId(),
            ]);
        }

         $builder
             ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'post.fields.category',
                'translation_domain' => 'forms',
                'placeholder' => 'post.placeholders.category',
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])  

            ->add('title', TextType::class, [
                'label' => 'post.fields.title',
                'translation_domain' => 'forms',
                'attr' => [
                    'maxlength' => 255,
                    'placeholder' => 'post.placeholders.title',
                    'data-slug-source' => 'true',
                ],
            ])

            ->add('slug', TextType::class, [
                'label' => 'post.fields.slug',
                'translation_domain' => 'forms',
                'attr' => [
                    'maxlength' => 255,
                    'placeholder' => 'post.placeholders.slug',
                    'data-slug-target' => 'true',
                ],
            ])

            ->add('content', TextareaType::class, [
                'label' => 'post.fields.content',
                'translation_domain' => 'forms',
                'attr' => [
                    'rows' => 10,
                    'placeholder' => 'post.placeholders.content',
                ],
            ])

            /*
            ->add('created_at', DateTimeType::class, [
                'label' => 'post.fields.created_at',
                'translation_domain' => 'forms',
                'widget' => 'single_text',
                'disabled' => true,
                'required' => false,
            ])

            ->add('updated_at', DateTimeType::class, [
                'label' => 'post.fields.updated_at',
                'translation_domain' => 'forms',
                'widget' => 'single_text',
                'disabled' => true,
                'required' => false,
            ])
            */    
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}