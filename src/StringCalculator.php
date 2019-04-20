<?php

class StringCalculator {
    const DEFAULT_DELIMITER = ',';
    const SINGLE_CHARACTER_DELIMITER_REGEX = '#//(.)\n#';
    const MULTIPLE_CHARACTER_DELIMITER_REGEX = '#//\[(.*)\]\n#';

    public function add($numbers) {
        $numbers = $this->handleCustomDelimiters($numbers);
        $numbers = $this->convertCustomDelimiterToDefaultDelimiter("\n", $numbers);

        if (empty($numbers)) {
            return 0;
        }

        $number_array = explode(self::DEFAULT_DELIMITER, $numbers);
        $this->throwExceptionIfThereAreNegatives($number_array);

        $number_array = $this->removeNumbersGreaterThan1000($number_array);
        return array_sum($number_array);
    }

    private function handleCustomDelimiters($numbers) {
        $custom_delimiter_array = $this->getCustomDelimiterArray($numbers);
        foreach ($custom_delimiter_array as $custom_delimiter){
            $numbers = $this->convertCustomDelimiterToDefaultDelimiter($custom_delimiter, $numbers);
        }

        return $this->removeCustomDelimiterDeclaration($numbers);
    }

    private function getCustomDelimiterArray($numbers) {
        if(!preg_match_all('#//.+?\n#', $numbers, $matches)){
            return array(self::DEFAULT_DELIMITER);
        }
        if (preg_match_all('#//(.)\n#', $numbers, $matches)||
            preg_match_all('#\[(.+?)\]#', $numbers, $matches)){
            return $matches[1];
        }
        return array(self::DEFAULT_DELIMITER);
    }

    private function convertCustomDelimiterToDefaultDelimiter($customDelimiter, $numbers) {
        return str_replace($customDelimiter, self::DEFAULT_DELIMITER, $numbers);
    }

    private function removeCustomDelimiterDeclaration($numbers) {
        if (preg_match('#//(.*)\n(.*)#', $numbers, $matches)) {
            return $matches[2];
        }
        return $numbers;
    }

    private function throwExceptionIfThereAreNegatives(array $number_array) {
        $negative_numbers = [];

        foreach ($number_array as $number) {
            if ($number < 0) {
                $negative_numbers[] = $number; //Push $number to $negative_numbers
            }
        }

        if (!empty($negative_numbers)) {
            throw new InvalidArgumentException("Negative numbers are not allowed: " . implode(',', $negative_numbers));
        }
    }

    private function removeNumbersGreaterThan1000($number_array) {
        foreach ($number_array as $key => $value) {
            if ($value > 1000) {
                unset($number_array[$key]);
            }
        }
        return $number_array;
    }
}