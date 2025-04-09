<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Form;

use CarlosChininin\App\Infrastructure\Form\DataTransformer\MonthDatePickerTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthDatePickerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new MonthDatePickerTransformer());
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['class'] = 'month-datepicker';
        $view->vars['attr']['autocomplete'] = 'off';
        $view->vars['attr']['data-provide'] = 'datepicker';
        $view->vars['attr']['data-date-format'] = 'MM-yyyy';
        $view->vars['attr']['data-date-language'] = 'es';
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'html5' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'month_datepicker';
    }
}