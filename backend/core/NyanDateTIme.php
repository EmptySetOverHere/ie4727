<?php

//this class is meant as a more convenient way to convert 'Y-m-d H:i:s' between timezones
//also forces DateTime to use the serverconfig.php's timezone rather than the timezone in php.ini

require_once 'constants/ServerConfig.php';

class NyanDateTime extends DateTime {
    //static function to 
    public static function change_timezone($datetime, string $from_timezone, string $to_timezone): string{
        $utc_time = new DateTime($datetime, new DateTimeZone($from_timezone));
        $utc_time->setTimezone(new DateTimeZone($to_timezone));
        return $utc_time->format('Y-m-d H:i:s');
    }

    //gives the UTC value without changing the timezone of the DateTime
    public function value(): string {
        $convertedDateTime = clone $this;
        $convertedDateTime->setTimezone(new DateTimeZone('UTC'));
        return $convertedDateTime->format('Y-m-d H:i:s');
    }

    //modified constructor to make creating a new DateTime less verbose
    public function __construct($datetime = 'now', $timezone = null) {
        if ($timezone !== null) {
            if (is_string($timezone)) {
                $datetimezone = new DateTimeZone($timezone);
            } elseif ($timezone instanceof DateTimeZone) {
                $datetimezone = $timezone;
            } else {
                throw new InvalidArgumentException('Invalid timezone specified.');
            }
        } else {
            $datetimezone = new DateTimeZone(SERVERCONFIG::server_timezone); // Use server timezone
        }
        parent::__construct($datetime, $datetimezone);
    }

    public static function now(): string {
        return (new NyanDateTime())->value;
    }
}
?>