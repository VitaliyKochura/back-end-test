<?php

namespace App\Form\Type;

use App\Entity\Company;
use App\Entity\SentResume;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SentResumeType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', EntityType::class, [
                'label' => 'Company',
                'class' => Company::class,
                'choice_label' => 'Name',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'query_builder' => function () {
                    return $this->entityManager->getRepository(Company::class)->createQueryBuilder('company')
                        ->orderBy('company.name', 'ASC');
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SentResume::class,
        ]);
    }
}