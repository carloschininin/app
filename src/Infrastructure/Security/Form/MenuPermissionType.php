<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security\Form;

use CarlosChininin\App\Domain\Model\AuthMenu\MenuServiceInterface;
use CarlosChininin\App\Infrastructure\Security\MenuPermission;
use CarlosChininin\App\Infrastructure\Security\Permission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuPermissionType extends AbstractType implements DataMapperInterface
{
    public function __construct(
        private readonly MenuServiceInterface $menuService,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('menu', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => $this->menus(),
            ])
            ->add('attributes', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => $this->values(),
                'multiple' => true,
                'expanded' => true,
            ])
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => MenuPermission::class,
                'empty_data' => null,
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return 'menu_permission';
    }

    /** @param MenuPermission $viewData */
    public function mapDataToForms($viewData, \Traversable $forms): void
    {
        $forms = iterator_to_array($forms);
        $forms['menu']->setData($viewData ? $viewData->menu() : '');
        $forms['attributes']->setData($viewData ? $viewData->attributes() : []);
    }

    public function mapFormsToData(\Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);
        $viewData = new MenuPermission(
            $forms['menu']->getData(),
            $forms['attributes']->getData()
        );
    }

    private function values(): array
    {
        $data = [];
        foreach (Permission::cases() as $value) {
            $data[$value->name] = $value->value;
        }

        return $data;
    }

    private function menus(): array
    {
        return $this->menuService->menusToArray();
    }
}
