<?php

namespace UserBundle\Form\Type;

use AppBundle\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeacherRegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//         add your custom field
//        $builder->add('profilePicture', 'vich_file');
//        $builder->add('firstName');
//        $builder->add('lastName');
//        $builder->add('qualifications');
//        $builder->add('subjects', 'collection',array(
//                'type' => new Subject()
//        ));
//        $builder->add(
//        'subjects', 'entity',
//        [
//            'class' => 'AppBundle\Entity\Subject',
//            'property' => 'name',
//            'multiple' => TRUE,
//            'expanded' => TRUE,
//            'label' => 'Subjects',
//
//        ]
//        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Teacher'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'teacher_registration';
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
}
