<?php

class notification_preference_enum{

    //sms has value 1, telegram value 8
    private static $enumerates = ['sms','email','whatsapp','telegram']; 

    public static function getEnumerates(){
        return $enumerates;
    }

    public static function names($value){
        $output = [];
        $allowed_length = count(self::$enumerates);
        $binaryArray  = str_split(str_pad(decbin($value), $allowed_length, '0', STR_PAD_LEFT));
        if(count($binaryArray) > count($enumerates)){
            throw new Exception("enumerate out of range",69000);
        }
        for ($i = 0; $i < count($binaryArray); $i++) {
            if ($binaryArray[$i] == '1') {
                $output[] = self::$enumerates[count(self::$enumerates)-$i-1];
            }
        }
        return $output;
    }

    public static function value($names){
        $value = 0;
        $add = 1;
        foreach(self::$enumerates as $enumerate){
            if(in_array($enumerate,$names)){
                $value += $add;
            }
            $add *= 2;
        }
        return $value;
    }
}

// var_dump( notification_preference_enum::names(5) );
// var_dump( notification_preference_enum::value(['sms','email']) );