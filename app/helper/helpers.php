<?php
// app/helpers.php
if (!function_exists('amountToWords')) {
    function amountToWords($amount, $firstUnite=null, $secondUnit=null, $currency = 'International')
    {
        if ($currency === 'BDT' && $amount <= 999999999) {
            return convertToIndianWords($amount) . ' Taka';
        }
        $numberWords = [
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
            'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        ];

        $tensWords = [
            '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
        ];

        $unitPlaces = ['', 'thousand', 'million', 'billion'];

        $amount = number_format($amount, 2, '.', '');
        list($dollars, $cents) = explode('.', $amount);

        $dollarsInWords = '';

        if ($dollars > 0) {
            $dollarsInWords .= amountToWordsHelper($dollars, $numberWords, $tensWords, $unitPlaces);
            $dollarsInWords .= $firstUnite;
        }

        $centsInWords = amountToWordsHelper($cents, $numberWords, $tensWords, $unitPlaces);
        $centsInWords .= $secondUnit;

        $result = $dollarsInWords;

        if ($cents > 0) {
            $result .= ' and ' . $centsInWords;
        }

        return ucfirst($result);
    }

    function amountToWordsHelper($amount, $numberWords, $tensWords, $unitPlaces)
    {
        $words = '';

        $amount = str_pad($amount, 12, '0', STR_PAD_LEFT);

        $billions = (int)substr($amount, 0, 3);
        $millions = (int)substr($amount, 3, 3);
        $thousands = (int)substr($amount, 6, 3);
        $hundreds = (int)substr($amount, 9, 3);

        if ($billions > 0) {
            $words .= amountToWordsPart($billions, $numberWords, $tensWords, $unitPlaces[3]) . ' ';
        }

        if ($millions > 0) {
            $words .= amountToWordsPart($millions, $numberWords, $tensWords, $unitPlaces[2]) . ' ';
        }

        if ($thousands > 0) {
            $words .= amountToWordsPart($thousands, $numberWords, $tensWords, $unitPlaces[1]) . ' ';
        }

        if ($hundreds > 0) {
            $words .= amountToWordsPart($hundreds, $numberWords, $tensWords, $unitPlaces[0]) . ' ';
        }

        return $words;
    }

    function amountToWordsPart($amount, $numberWords, $tensWords, $unit)
    {
        $words = '';

        $hundreds = (int)($amount / 100);
        $tensUnits = $amount % 100;

        if ($hundreds > 0) {
            $words .= $numberWords[$hundreds] . ' hundred';
            if ($tensUnits > 0) {
                $words .= ' and ';
            }
        }

        if ($tensUnits > 0) {
            if ($tensUnits < 20) {
                $words .= $numberWords[$tensUnits];
            } else {
                $tens = (int)($tensUnits / 10);
                $units = $tensUnits % 10;
                $words .= $tensWords[$tens];
                if ($units > 0) {
                    $words .= ' ' . $numberWords[$units];
                }
            }
        }

        if ($amount > 0) {
            $words .= ' ' . $unit;
        }

        return $words;
    }
}
if (!function_exists('convertToIndianWords')) {
    function convertToIndianWords($number)
    {
        $words = '';

        // Break the number into segments
        $crores = floor($number / 10000000);
        $number %= 10000000;
        $lakhs = floor($number / 100000);
        $number %= 100000;
        $thousands = floor($number / 1000);
        $number %= 1000;
        $hundreds = floor($number / 100);
        $number %= 100;
        $tensAndOnes = $number;

        // Convert each segment to words
        $words .= ($crores > 0) ? convertSegmentToWords($crores) . ' Crore ' : '';
        $words .= ($lakhs > 0) ? convertSegmentToWords($lakhs) . ' Lakh ' : '';
        $words .= ($thousands > 0) ? convertSegmentToWords($thousands) . ' Thousand ' : '';
        $words .= ($hundreds > 0) ? convertSegmentToWords($hundreds) . ' Hundred ' : '';
        $words .= ($tensAndOnes > 0) ? convertSegmentToWords($tensAndOnes) : '';

        return $words;
    }
    function convertSegmentToWords($number) {

        // Define units
        $units = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten');

        // Define tens
        $tens = array('', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');

        // Define teens
        $teens = array('', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
        $words = '';

        // Convert tens place
        if ($number >= 20) {
            $words .= $tens[floor($number / 10)] . ' ';
            $number %= 10;
        } elseif ($number >= 11) {
            $words .= $teens[$number - 10] . ' ';
            $number = 0; // Skip the ones place for teens
        }

        // Convert ones place
        if ($number > 0) {
            $words .= $units[$number] . ' ';
        }

        return $words;
    }
}
