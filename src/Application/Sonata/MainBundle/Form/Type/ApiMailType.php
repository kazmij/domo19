<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MainBundle\Form\Type;

use Application\Sonata\MainBundle\Model\Email;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;


class ApiMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sendTo', 'hidden', [
                'required' => false
            ])
            ->add('email', null, [
                'label' => 'Adres E-mail'
            ])
            ->add('fullName', null, [
                'label' => 'Imię i nazwisko'
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Temat',
                'placeholder' => 'Wybierz temat',
                'choices' => Email::$subjects,
                'attr' => [
                    'class' => 'select-menu'
                ]
            ])
            ->add('phone', null, [
                'label' => 'Telefon'
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Wiadomość',
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('rule1', CheckboxType::class, [
                'required' => true,
                'label' => '<i class="required"></i> Oświadczam, że zostałam/em poinformowany, iż administratorem danych osobowych udostępnionych za pomocą Formularza Kontaktowego jest Equal Properties Sp. z o.o. z siedzibą w Warszawie (00-124), przy Rondo ONZ 1, wpisana do rejestru przedsiębiorców KRS prowadzonego przez Sąd Rejonowy w Warszawie, XII Wydział Gospodarczy Krajowego Rejestru Sądowego pod numerem KRS 0000698793, posiadająca NIP 5252725768.',
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('rule2', CheckboxType::class, [
                'required' => true,
                'label' => '<i class="required"></i> Wyrażam zgodę na otrzymywanie od Equal Properties Sp. z o.o z siedzibą w Warszawie za pomocą środków komunikacji elektronicznej oraz telekomunikacyjnych urządzeń końcowych informacji o charakterze marketingowym (w tym informacji i ofert handlowych) dotyczących Equal Properties Sp. z o.o z siedzibą w Warszawie',
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('rule3', CheckboxType::class, [
                'required' => false,
                'label' => 'Wyrażam zgodę na otrzymywanie od Gent Holding S.A. z siedzibą w Warszawie za pomocą środków komunikacji elektronicznej oraz telekomunikacyjnych urządzeń końcowych informacji o charakterze marketingowym (w tym informacji i ofert handlowych) dotyczących działalności holdingu Gent Holding S.A. z siedzibą w Warszawie.',
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('rule4', CheckboxType::class, [
                'required' => false,
                'label' => 'Wyrażam zgodę na otrzymywanie od podmiotów wchodzących w skład grupy kapitałowej Gent Holding S.A. za pomocą środków komunikacji elektronicznej oraz telekomunikacyjnych urządzeń końcowych informacji o charakterze marketingowym (w tym informacji i ofert handlowych) dotyczących tychże podmiotów.',
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('rule5', CheckboxType::class, [
                'required' => true,
                'label' => '<i class="required"></i> Oświadczam, że zostałam/em poinformowany o tym, że: dane będą przetwarzane w zgodzie z przepisami prawa w celu  udzielenia odpowiedzi na zapytanie przesłane za pomocą Formularza Kontaktowego, na podstawie art. 6 ust. 1 lit. b) RODO oraz na podstawie ww. zgód, podanie ww. danych jest dobrowolne, jednak niezbędne do otrzymania odpowiedzi oraz że mam prawo do dostępu do treści danych, sprostowania danych, usunięcia danych, ograniczenia przetwarzania danych, wniesienia sprzeciwu wobec przetwarzania danych, żądania przeniesienia danych oraz prawo do wniesienia skargi do właściwego organu nadzorczego, a także o pozostałych kwestiach wynikających z art. 13 RODO, określonych w  Regulaminie Serwisu Internetowego Equal Properties  oraz w Polityce Prywatności. Zapytania oraz żądania dotyczące danych osobowych należy przesyłać na adres e-mail: dane.osobowe@equalproperties.com',
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('recaptcha', EWZRecaptchaType::class, array(
                'label' => 'Potwierdź, że nie jesteś robotem',
                'attr' => array(
                    'options' => array(
                        'theme' => 'light',
                        'type' => 'image',
                        'size' => 'normal'
                    )
                )
            ));
    }
}
