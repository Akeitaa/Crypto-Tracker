<?php

namespace App\Form;

use App\Entity\Transaction;
use App\Enum\CryptoSymbol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('crypto',ChoiceType::class,[
                'required' => false,
                'label' => false,
                'placeholder' => 'Sélectionner une crypto',
                'choices' => [
                    CryptoSymbol::BTC->value . ' (Bitcoin)' => CryptoSymbol::BTC->value,
                    CryptoSymbol::ETH->value . ' (Ethereum)' => CryptoSymbol::ETH->value,
                    CryptoSymbol::XRP->value . ' (Ripple)' => CryptoSymbol::XRP->value,
                ]
            ])
            ->add('quantity',NumberType::class,[
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Quantité'
                ]
            ])
            ->add('price',NumberType::class,[
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix d\'achat'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
