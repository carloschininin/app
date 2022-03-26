<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security\Form;

use CarlosChininin\App\Infrastructure\Security\MenuPermission;
use CarlosChininin\App\Infrastructure\Security\Permission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

final class MenuPermissionType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('menu', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => $this->menus(),
            ])
            ->add('atributos', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => $this->values(),
                'multiple' => true,
                'expanded' => true,
            ])
            ->setDataMapper($this)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => MenuPermission::class,
                'empty_data' => null,
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'menu_permission';
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
//        $menus = $this->entityManager->getRepository(Menu::class)->valuesForSecurityRol();
//        $data = [];
//        foreach ($menus as $menu) {
//            $name = mb_strtoupper($menu['padre_nombre'].' - '.$menu['nombre']);
//            $data[$name] = $menu['ruteo'];
//        }
//
//        return $data;

        return [
            'MENU 1' => 'menu1',
            'MENU 2' => 'menu2',
            'MENU 3' => 'menu3',
        ];
    }

    /** @param MenuPermission $viewData */
    public function mapDataToForms($viewData, Traversable $forms)
    {
        $forms = iterator_to_array($forms);
        $forms['menu']->setData($viewData ? $viewData->menu() : '');
        $forms['atributos']->setData($viewData ? $viewData->atributos() : []);
    }

    public function mapFormsToData(Traversable $forms, &$viewData)
    {
        $forms = iterator_to_array($forms);
        $viewData = new MenuPermission(
            $forms['menu']->getData(),
            $forms['atributos']->getData()
        );
    }
}
