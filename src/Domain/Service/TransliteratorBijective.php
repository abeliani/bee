<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Service;

class TransliteratorBijective
{
    public const DIRECTION_RU = 0;
    public const DIRECTION_EN = 1;

    public function toRu(string $text): string
    {
        if (!(ctype_alpha($text[0]) || ctype_alpha($text[1]))) {
            return $text;
        }

        return $this->translate($text, self::DIRECTION_RU);
    }

    public function toEn(string $text): string
    {
        if (ctype_alpha($text[0]) || ctype_alpha($text[1])) {
            return $text;
        }

        return $this->translate($text, self::DIRECTION_EN);
    }

    private function translate(string $text, int $direction): string
    {
        $map = [
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => '+E', 'Ж' => 'J', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => '4',
            'Ш' => 'W', 'Щ' => 'W_', 'Ъ' => '^', 'Ы' => '6', 'Ь' => '_',
            'Э' => ':E', 'Ю' => 'Q', 'Я' => 'X',

            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => '+e', 'ж' => 'j', 'з' => 'z', 'и' => 'i',
            'й' => 'Y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => '4',
            'ш' => 'w', 'щ' => 'w_', 'ъ' => '^', 'ы' => '6', 'ь' => '_',
            'э' => ':e', 'ю' => 'q', 'я' => 'x'
        ];

        if ($direction === self::DIRECTION_RU) {
            $map = array_flip($map);
        }

        return strtr($text, $map);
    }
}
