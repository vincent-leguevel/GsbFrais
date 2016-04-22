<?php

namespace AL\GsbBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LigneFraisHorsForfaitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('dateFrais','date',array('years'=>range(date('Y'),date('Y')),'months'=>range(date('m'),date('m')),'days'=>range(date('d')-date('d')+1,date('d'))))
            ->add('montant')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AL\GsbBundle\Entity\LigneFraisHorsForfait'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'al_gsbbundle_lignefraishorsforfait';
    }
}
