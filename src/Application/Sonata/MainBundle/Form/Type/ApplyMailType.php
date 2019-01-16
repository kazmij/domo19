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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;


class ApplyMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'required' => true,
                'label' => 'Adres E-mail'
            ])
            ->add('fullName', null, [
                'required' => true,
                'label' => 'Imię i nazwisko'
            ])
            ->add('subject', null, [
                'required' => true,
                'label' => 'Temat',
                'data' => 'Aplikacja na stanowisko Doradca ds. Nieruchomości.',
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('file', FileType::class, [
                    'label' => 'Plik z CV',
                    'attr' => [
                        'accept' => "application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.oasis.opendocument.text"
                    ]
                ]
            )
            ->add('city', ChoiceType::class, [
                'required' => true,
                'label' => 'Miasto',
                'placeholder' => 'Wybierz miasto',
                'choices' => [
                    'Gdańsk' => 'Gdańsk',
                    'Gdynia' => 'Gdynia',
                    'Katowice' => 'Katowice',
                    'Kraków' => 'Kraków',
                    'Lublin' => 'Lublin',
                    'Olsztyn' => 'Olsztyn',
                    'Poznań' => 'Poznań',
                    'Warszawa' => 'Warszawa',
                    'Wrocław' => 'Wrocław'
                ],
                'attr' => [
                    'class' => 'select-menu'
                ]
            ])
            ->add('phone', null, [
                'required' => true,
                'label' => 'Telefon'
            ])
            ->add('rule1', CheckboxType::class, [
                'required' => true,
                'label' => '<i class="required"></i> Wyrażam zgodę na przetwarzanie przez Equal Properties Sp. z o.o., Rondo ONZ 1, 00-124 Warszawa moich danych osobowych zawartych w zgłoszeniu rekrutacyjnym w celu prowadzenia rekrutacji na stanowisko wskazane w ogłoszeniu.',
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('rule2', CheckboxType::class, [
                'required' => false,
                'label' => '<i></i> Wyrażam zgodę na przetwarzanie przez Equal Properties Sp. z o.o. moich danych osobowych zawartych w zgłoszeniu rekrutacyjnym dla celów przyszłych rekrutacji prowadzonych przez Equal Properties Sp. z o.o.',
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('rule3', CheckboxType::class, [
                'required' => true,
                'label' => '<i class="required"></i> Oświadczam, że zostałem/łam poinformowany/na, że w każdym czasie mogę cofnąć zgodę, kontaktując się z Wami pod adresem kariera@equalproperties.com. Moje dane osobowe wskazane w Kodeksie pracy lub w innych ustawach szczegółowych (według wymogów ogłoszenia), przetwarzane są w oparciu o przepisy prawa i ich podanie jest konieczne do wzięcia udziału w rekrutacji. Pozostałe dane osobowe (np. wizerunek) przetwarzane są na podstawie Mojej dobrowolnej zgody, którą wyraziłem/łam wysyłając swoje zgłoszenie rekrutacyjne i ich podanie nie ma wpływu na możliwość udziału w rekrutacji. Można przetwarzać Moje dane osobowe zawarte w zgłoszeniu rekrutacyjnym także w celu ustalenia, dochodzenia lub obrony przed roszczeniami, jeżeli roszczenia dotyczą prowadzonej przez rekrutacji. W tym celu Moje dane osobowe będą przetwarzane w oparciu o prawnie uzasadniony interes, polegający na ustaleniu, dochodzeniu lub obrony przed roszczeniami w postępowaniu przed sądami lub organami państwowymi. Mam prawo dostępu do swoich danych, w tym uzyskania ich kopii, sprostowania danych, żądania ich usunięcia, ograniczenia przetwarzania, wniesienia sprzeciwu wobec przetwarzania oraz przeniesienia podanych danych (na których przetwarzanie wyraziłem zgodę) do innego administratora danych. Mam także prawo do wniesienia skargi do Prezesa Urzędu Ochrony Danych Osobowych. Cofnięcie zgody pozostaje bez wpływu na zgodność z prawem przetwarzania, którego dokonano na podstawie zgody przed jej cofnięciem. Moje dane osobowe są przetwarzane w celu prowadzenia rekrutacji na stanowisko wskazane w ogłoszeniu przez okres 6 miesięcy, a gdy wyraziłem/łam zgodę na udział w przyszłych rekrutacjach przez okres 12 miesięcy. Moje dane osobowe można przekazać dostawcom usługi publikacji ogłoszeń o pracę, dostawcom systemów do zarządzania rekrutacjami, dostawcom usług IT takich jak hosting oraz dostawcom systemów informatycznych.  Moje dane osobowe nie są przekazywane poza Europejski Obszar Gospodarczy. W razie pytań mogę skontaktować się z Equal Properties Sp. z o.o. pod adresem: kariera@equalproperties.com ',
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
