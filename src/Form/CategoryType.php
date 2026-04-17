<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('name', TextType::class, [
                'label' => 'category.fields.name',
                'translation_domain' => 'forms',
                'attr' => [
                    'maxlength' => 100,
                    // 'placeholder' => 'category.placeholders.name',
                    'data-slug-source' => 'true',
                ],
            ])

            ->add('slug', TextType::class, [
                'label' => 'category.fields.slug',
                'translation_domain' => 'forms',
                'attr' => [
                    'maxlength' => 120,
                    // 'placeholder' => 'category.placeholders.slug',
                    'data-slug-target' => 'true',
                ],
            ])

            ->add('description', TextareaType::class, [
                'label' => 'category.fields.description',
                'translation_domain' => 'forms',
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    // 'placeholder' => 'category.placeholders.description',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
