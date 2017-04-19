<?php

/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 13.4.2017
 * Time: 10:24
 */

namespace AppBundle\Forms;

//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Entity\Priselistfiles;

class FormUploadPricelist extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ...

        $builder
            ->add('excelfile', VichFileType::class, [
            'required' => false,
            'allow_delete' => true, // optional, default is true
            'download_link' => true, // optional, default is true
//            'download_uri' => '...', // optional, if not provided - will automatically resolved using storage
        ])
        ->add('suppliername', TextType::class)
        ->add('upload', SubmitType::class, array('label' => 'Upload pricelist'));
    }

//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'AppBundle\Entity\Priselistfiles'
//        ));
//    }

//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
//        // ...
//
//        $builder->add('username', TextType::class)
//            ->add('password', TextType::class)
//            ->add('upload', SubmitType::class, array('label' => 'Upload pricelist'));
//    }
//
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'AppBundle\Entity\User'
//        ));
//    }

}