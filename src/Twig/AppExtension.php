<?php

namespace App\Twig;

use DateTime;
use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('area', [$this, 'calculateArea']),
        ];
    }

    
    public function getFilters(): array
    {
        return [
            new TwigFilter('priceFormat', [$this, 'formatPrice']),
            new TwigFilter('dateFormat', [$this, 'formatDate']),
            new TwigFilter('dayInWeek', [$this, 'dayInWeek']),
        ];
    }

    public function formatPrice(float $number, int $decimals = 0, string $decPoint = '.', string $thousandsSep = ','): string
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price. '€';

        return $price;
    }

    public function dayInWeek(DateTime $date = null, $locale = null): string
    {
        if($date){
            if('fr' == $locale){
                switch($date->format('l')){
                    case 'Monday':
                        return 'Lundi';
                    case 'Tuesday':
                        return 'Mardi';
                    case 'Wednesday':
                        return 'Mercredi';
                    case 'Thursday':
                        return 'Jeudi';
                    case 'Friday':
                        return 'Vendredi';
                    case 'Saturday':
                        return 'Samedi';
                    case 'Sunday':
                        return 'Dimanche';
                };
            }
            return $date->format('l');

        }
        return '';
    }

    public function formatDate(DateTimeInterface $date, $showTime = false): string
    {
        $format = 'd/m/y';
        if ($showTime){
            $format = 'd/m/y h:m:i';
        }
        
        return $date->format($format);
    }
}
?>