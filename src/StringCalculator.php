<?php

class StringCalculator {
    const DEFAULT_DELIMITER = ',';

    public function add($numbers) {
        $numbers = $this->handleCustomDelimiters($numbers);
        $numbers = $this->convertCustomDelimiterToDefaultDelimiter("\n", $numbers);

        if ($numbers == '') {
            return 0;
        }

        $number_array = explode(self::DEFAULT_DELIMITER, $numbers);
        $this->throwExceptionIfThereAreNegatives($number_array);

        return $this->getTotal($number_array);
    }

    private function handleCustomDelimiters($numbers) {
        $custom_delimiter = $this->getCustomDelimiter($numbers);
        $numbers = $this->convertCustomDelimiterToDefaultDelimiter($custom_delimiter, $numbers);
        return $this->removeCustomDelimiterDeclaration($numbers);
    }

    private function getCustomDelimiter($numbers) {
        if(preg_match('#//(.)\n#', $numbers, $matches)){
            return $matches[1];
        }
        return self::DEFAULT_DELIMITER;
    }

    private function removeCustomDelimiterDeclaration($numbers) {
        if(preg_match('#//(.)\n(.*)#', $numbers, $matches)){
            return $matches[2];
        }
        return $numbers;
    }

    private function convertCustomDelimiterToDefaultDelimiter($customDelimiter, $numbers) {
        return str_replace($customDelimiter, self::DEFAULT_DELIMITER, $numbers);
    }

    private function getTotal($number_array) {
        $total = 0;

        foreach($number_array as $number){
            $total += $number;
        }
        return $total;
    }

    private function throwExceptionIfThereAreNegatives(array $number_array) {
        $negative_numbers = '';

        foreach($number_array as $number){
            if($number<0){
                $negative_numbers .= $number;
                $negative_numbers .= ' ';
            }
        }

        if($negative_numbers != ''){
            throw new InvalidArgumentException("Negative numbers are not allowed: " . $negative_numbers);
        }
    }
}