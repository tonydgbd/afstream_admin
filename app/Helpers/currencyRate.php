<?php
namespace App\Helpers;
use Modules\Setting\Entities\Settings;
use Modules\Setting\Entities\Currency;

class currencyRate{

    public static function fetchRate(){

        $rate = 1;

        $dc = Settings::where('name', 'default_currency_id')->first();
        $defaultCurrency = Currency::where('id',$dc->value)->first();
        if(!session()->get('currency')){

            session()->put('currency',[
                'code' => $defaultCurrency->code,
                'symbol' => $defaultCurrency->symbol
            ]);
            return $rate = 1;
        }else{
            $to = session()->get('currency')['code'];
            $from = $defaultCurrency->code;
            $rate = currency(1.00, $from, $to, $format = false);
            return sprintf("%.2f",$rate);
        }
        //return $rate = $defaultCurrency->exchange_rate;

    }


}