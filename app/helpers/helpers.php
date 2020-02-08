<?php

    function calculateDiscount($price,$type,$discount) {
        switch($type) {

            case 'percentage':
                return $price - (($price / 100) * $discount);
            break;

            case 'amount';
                return $price - $discount;
            break;

            default :
                return 0;
        }
    }

    function calculateAmount($price,$type,$discount) {
        switch($type) {
            case 'percentage':
                return (($price / 100) * $discount);
            break;

            case 'amount';
                return $discount;
            break;

            default :
                return 0;
        }
    }
