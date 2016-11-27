<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\OAuthServerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorizeFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add(
            'allowAccess',
            SubmitType::class,
            [
                'label' => 'Allow access',
                'attr'  => [ 'class' => 'btn btn-success btn-grant-access' ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            [
                'data_class' => 'Parabot\BDN\OAuthServerBundle\Form\Model\Authorize',
            ]
        );
    }

}