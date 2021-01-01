<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Carbon</title>
</head>
<body>
  <h1>Carbon</h1>
  <?php
  require 'vendor/autoload.php';
  use Carbon\Carbon;
  use Carbon\CarbonImmutable;
  use Carbon\CarbonInterval;
  
  echo "<h2>Quelques exemples : </h2>";
  echo (new Carbon("next monday"))->modify('+14 days');
  echo "<hr>";
  
  echo (new Carbon("monday next week +5 hours +10 minutes -10 seconds"));
  echo "<hr>";
  
  echo (new Carbon("next friday"))->diffForHumans();
  echo "<hr>";
  
  echo (new Carbon('first day of january this year'))->modify('+7 days');
  echo "<hr>";
  
  echo "<h2>1er pas avec Carbon : </h2>";
  printf("Now: %s", Carbon::now());

  $mutable = Carbon::now();
  $immutable = CarbonImmutable::now();
  $modifiedMutable = $mutable->add(1, 'day');
  $modifiedImmutable = CarbonImmutable::now()->add(1, 'day');

  var_dump($modifiedMutable === $mutable);             // bool(true)
  var_dump($mutable->isoFormat('dddd D'));             // string(11) "Saturday 24"
  var_dump($modifiedMutable->isoFormat('dddd D'));     // string(11) "Saturday 24"
  // So it means $mutable and $modifiedMutable are the same object
  // both set to now + 1 day.
  var_dump($modifiedImmutable === $immutable);         // bool(false)
  var_dump($immutable->isoFormat('dddd D'));           // string(9) "Friday 23"
  var_dump($modifiedImmutable->isoFormat('dddd D'));   // string(11) "Saturday 24"
  // While $immutable is still set to now and cannot be changed and
  // $modifiedImmutable is a new instance created from $immutable
  // set to now + 1 day.

  $mutable = CarbonImmutable::now()->toMutable();
  var_dump($mutable->isMutable());                     // bool(true)
  var_dump($mutable->isImmutable());                   // bool(false)
  $immutable = Carbon::now()->toImmutable();
  var_dump($immutable->isMutable());                   // bool(false)
  var_dump($immutable->isImmutable());                 // bool(true)
  echo "<hr>";

  echo "<h2>Timezone : </h2>";
  $dtToronto = Carbon::create(2012, 1, 1, 0, 0, 0, 'America/Toronto');
  $dtVancouver = Carbon::create(2012, 1, 1, 0, 0, 0, 'America/Vancouver');
  // Try to replace the 4th number (hours) or the last argument (timezone) with
  // Europe/Paris for example and see the actual result on the right hand.
  // It's alive!

  echo $dtVancouver->diffInHours($dtToronto); // 3
  // Now, try to double-click on "diffInHours" or "create" to open
  // the References panel.
  // Once the references panel is open, you can use the search field to
  // filter the list or click the (<) button to close it.
  echo "<hr>";

  $carbon = new Carbon();                  // equivalent to Carbon::now()
  $carbon = new Carbon('first day of January 2008', 'America/Vancouver');
  echo get_class($carbon)."<br>";          // 'Carbon\Carbon'
  $carbon = new Carbon(new DateTime('first day of January 2008'), new DateTimeZone('America/Vancouver')); // equivalent to previous instance
  echo "<pre>".var_export($carbon, true)."</pre>";
  // You can create Carbon or CarbonImmutable instance from:
  //   - string representation
  //   - integer timestamp
  //   - DateTimeInterface instance (that includes DateTime, DateTimeImmutable or an other Carbon instance)
  // All those are available right in the constructor, other creator methods can be found
  // in the "Reference" panel searching for "create".
  echo "<hr>";

  $now = Carbon::now(); // will use timezone as set with date_default_timezone_set
  // PS: we recommend you to work with UTC as default timezone and only use
  // other timezones (such as the user timezone) on display

  $nowInLondonTz = Carbon::now(new DateTimeZone('Europe/London'));

  // or just pass the timezone as a string
  $nowInLondonTz = Carbon::now('Europe/London');
  echo $nowInLondonTz->tzName;             // Europe/London
  echo "<br>";

  // or to create a date with a custom fixed timezone offset
  $date = Carbon::now('+13:30');
  echo $date->tzName;                      // +13:30
  echo "<br>";

  // Get/set minutes offset from UTC
  echo $date->utcOffset();                 // 810
  echo "<br>";

  $date->utcOffset(180);

  echo $date->tzName;                      // +03:00
  echo "<br>";
  echo $date->utcOffset();                 // 180
  echo "<hr>";

  echo "<h2>Ajouter des semaines : </h2>";
  echo (new Carbon('first day of December 2008'))->addWeeks(2);     // 2008-12-15 00:00:00
  echo "<br>";
  echo Carbon::parse('first day of December 2008')->addWeeks(2);    // 2008-12-15 00:00:00
  echo "<hr>";

  echo "<h2>Ajouter des heures : </h2>";
  echo Carbon::now()->addHours(2);
  echo "<br>";
  echo Carbon::now()->addHours(-2);
  echo "<hr>";

  echo "<h2>Date courante : </h2>";
  $now = Carbon::now();
  echo $now;                               // 2020-10-23 09:22:34
  echo "<br>";
  
  echo "<h2>Jour courant : </h2>";
  $today = Carbon::today();
  echo $today;                             // 2020-10-23 00:00:00
  echo "<br>";
  
  echo "<h2>Demain (Londres) : </h2>";
  $tomorrow = Carbon::tomorrow('Europe/London');
  echo $tomorrow;                          // 2020-10-24 00:00:00
  echo "<br>";
  
  echo "<h2>Hier : </h2>";
  $yesterday = Carbon::yesterday();
  echo $yesterday;                         // 2020-10-22 00:00:00
  echo "<hr>";

  $year = 2000; $month = 4; $day = 19;
  $hour = 20; $minute = 30; $second = 15; $tz = 'Europe/Madrid';
  echo Carbon::createFromDate($year, $month, $day, $tz)."<br>";
  echo Carbon::createMidnightDate($year, $month, $day, $tz)."<br>";
  echo Carbon::createFromTime($hour, $minute, $second, $tz)."<br>";
  echo Carbon::createFromTimeString("$hour:$minute:$second", $tz)."<br>";
  echo Carbon::create($year, $month, $day, $hour, $minute, $second, $tz)."<br>";
  echo "<hr>";

  $dt = Carbon::createFromFormat('Y-m-d H:i:s.u', '2019-02-01 03:45:27.612584');

  // $dt->toAtomString() is the same as $dt->format(DateTime::ATOM);
  echo $dt->toAtomString()."<br>";           // 2019-02-01T03:45:27+00:00
  echo $dt->toCookieString()."<br>";         // Friday, 01-Feb-2019 03:45:27 UTC

  echo $dt->toIso8601String();        // 2019-02-01T03:45:27+00:00
  // Be aware we chose to use the full-extended format of the ISO 8601 norm
  // Natively, DateTime::ISO8601 format is not compatible with ISO-8601 as it
  // is explained here in the PHP documentation:
  // https://php.net/manual/class.datetime.php#datetime.constants.iso8601
  // We consider it as a PHP mistake and chose not to provide method for this
  // format, but you still can use it this way:
  echo $dt->format(DateTime::ISO8601)."<br>"; // 2019-02-01T03:45:27+0000

  echo $dt->toISOString()."<br>";            // 2019-02-01T03:45:27.612584Z
  echo $dt->toJSON()."<br>";                 // 2019-02-01T03:45:27.612584Z

  echo $dt->toIso8601ZuluString()."<br>";    // 2019-02-01T03:45:27Z
  echo $dt->toDateTimeLocalString()."<br>";  // 2019-02-01T03:45:27
  echo $dt->toRfc822String()."<br>";         // Fri, 01 Feb 19 03:45:27 +0000
  echo $dt->toRfc850String()."<br>";         // Friday, 01-Feb-19 03:45:27 UTC
  echo $dt->toRfc1036String()."<br>";        // Fri, 01 Feb 19 03:45:27 +0000
  echo $dt->toRfc1123String()."<br>";        // Fri, 01 Feb 2019 03:45:27 +0000
  echo $dt->toRfc2822String()."<br>";        // Fri, 01 Feb 2019 03:45:27 +0000
  echo $dt->toRfc3339String()."<br>";        // 2019-02-01T03:45:27+00:00
  echo $dt->toRfc7231String()."<br>";        // Fri, 01 Feb 2019 03:45:27 GMT
  echo $dt->toRssString()."<br>";            // Fri, 01 Feb 2019 03:45:27 +0000
  echo $dt->toW3cString()."<br>";            // 2019-02-01T03:45:27+00:00
  echo "<hr>";

  echo "<h2>Rendu en array : </h2>";
  $dt = Carbon::createFromFormat('Y-m-d H:i:s.u', '2019-02-01 03:45:27.612584');
  echo "<pre>"; var_dump($dt->toArray()); echo "</pre>"; 
  echo "<br>";
  /*
  array(12) {
    ["year"]=>
    int(2019)
    ["month"]=>
    int(2)
    ["day"]=>
    int(1)
    ["dayOfWeek"]=>
    int(5)
    ["dayOfYear"]=>
    int(32)
    ["hour"]=>
    int(3)
    ["minute"]=>
    int(45)
    ["second"]=>
    int(27)
    ["micro"]=>
    int(612584)
    ["timestamp"]=>
    int(1548992727)
    ["formatted"]=>
    string(19) "2019-02-01 03:45:27"
    ["timezone"]=>
    object(Carbon\CarbonTimeZone)#3499 (2) {
      ["timezone_type"]=>
      int(3)
      ["timezone"]=>
      string(3) "UTC"
    }
  }
  */

  echo "<h2>Rendu en object : </h2>";
  echo "<pre>"; var_dump($dt->toObject()); echo "</pre>"; 
  echo "<br>";
  /*
  object(stdClass)#3499 (12) {
    ["year"]=>
    int(2019)
    ["month"]=>
    int(2)
    ["day"]=>
    int(1)
    ["dayOfWeek"]=>
    int(5)
    ["dayOfYear"]=>
    int(32)
    ["hour"]=>
    int(3)
    ["minute"]=>
    int(45)
    ["second"]=>
    int(27)
    ["micro"]=>
    int(612584)
    ["timestamp"]=>
    int(1548992727)
    ["formatted"]=>
    string(19) "2019-02-01 03:45:27"
    ["timezone"]=>
    object(Carbon\CarbonTimeZone)#3511 (2) {
      ["timezone_type"]=>
      int(3)
      ["timezone"]=>
      string(3) "UTC"
    }
  }
  */

  echo "<pre>"; var_dump($dt->toDate()); echo "</pre>"; // Same as $dt->toDateTime()
  echo "<br>";
  /*
  object(DateTime)#3499 (3) {
    ["date"]=>
    string(26) "2019-02-01 03:45:27.612584"
    ["timezone_type"]=>
    int(3)
    ["timezone"]=>
    string(3) "UTC"
  }
  */

  // Note than both Carbon and CarbonImmutable can be cast
  // to both DateTime and DateTimeImmutable
  echo "<pre>"; var_dump($dt->toDateTimeImmutable()); echo "</pre>";
  echo "<br>";
  /*
  object(DateTimeImmutable)#3499 (3) {
    ["date"]=>
    string(26) "2019-02-01 03:45:27.612584"
    ["timezone_type"]=>
    int(3)
    ["timezone"]=>
    string(3) "UTC"
  }
  */
  echo "<hr>";

  echo "<h2>Add / Sub :</h2>";
  $dt = Carbon::create(2012, 1, 31, 0);

  echo $dt->toDateTimeString()."<br>";            // 2012-01-31 00:00:00
  echo "<br>";

  echo $dt->addCenturies(5)."<br>";               // 2512-01-31 00:00:00
  echo $dt->addCentury()."<br>";                  // 2612-01-31 00:00:00
  echo $dt->subCentury()."<br>";                  // 2512-01-31 00:00:00
  echo $dt->subCenturies(5)."<br>";               // 2012-01-31 00:00:00
  echo "<br>";
  
  echo $dt->addYears(5)."<br>";                   // 2017-01-31 00:00:00
  echo $dt->addYear()."<br>";                     // 2018-01-31 00:00:00
  echo $dt->subYear()."<br>";                     // 2017-01-31 00:00:00
  echo $dt->subYears(5)."<br>";                   // 2012-01-31 00:00:00
  echo "<br>";

  echo $dt->addQuarters(2)."<br>";                // 2012-07-31 00:00:00
  echo $dt->addQuarter()."<br>";                  // 2012-10-31 00:00:00
  echo $dt->subQuarter()."<br>";                  // 2012-07-31 00:00:00
  echo $dt->subQuarters(2)."<br>";                // 2012-01-31 00:00:00
  echo "<br>";

  echo $dt->addMonths(60)."<br>";                 // 2017-01-31 00:00:00
  echo $dt->addMonth()."<br>";                    // 2017-03-03 00:00:00 equivalent of $dt->month($dt->month + 1); so it wraps
  echo $dt->subMonth()."<br>";                    // 2017-02-03 00:00:00
  echo $dt->subMonths(60)."<br>";                 // 2012-02-03 00:00:00
  echo "<br>";

  echo $dt->addDays(29)."<br>";                   // 2012-03-03 00:00:00
  echo $dt->addDay()."<br>";                      // 2012-03-04 00:00:00
  echo $dt->subDay()."<br>";                      // 2012-03-03 00:00:00
  echo $dt->subDays(29)."<br>";                   // 2012-02-03 00:00:00
  echo "<br>";

  echo $dt->addWeekdays(4)."<br>";                // 2012-02-09 00:00:00
  echo $dt->addWeekday()."<br>";                  // 2012-02-10 00:00:00
  echo $dt->subWeekday()."<br>";                  // 2012-02-09 00:00:00
  echo $dt->subWeekdays(4)."<br>";                // 2012-02-03 00:00:00
  echo "<br>";

  echo $dt->addWeeks(3)."<br>";                   // 2012-02-24 00:00:00
  echo $dt->addWeek()."<br>";                     // 2012-03-02 00:00:00
  echo $dt->subWeek()."<br>";                     // 2012-02-24 00:00:00
  echo $dt->subWeeks(3)."<br>";                   // 2012-02-03 00:00:00
  echo "<br>";

  echo $dt->addHours(24)."<br>";                  // 2012-02-04 00:00:00
  echo $dt->addHour()."<br>";                     // 2012-02-04 01:00:00
  echo $dt->subHour()."<br>";                     // 2012-02-04 00:00:00
  echo $dt->subHours(24)."<br>";                  // 2012-02-03 00:00:00
  echo "<br>";

  echo $dt->addMinutes(61)."<br>";                // 2012-02-03 01:01:00
  echo $dt->addMinute()."<br>";                   // 2012-02-03 01:02:00
  echo $dt->subMinute()."<br>";                   // 2012-02-03 01:01:00
  echo $dt->subMinutes(61)."<br>";                // 2012-02-03 00:00:00
  echo "<br>";

  echo $dt->addSeconds(61)."<br>";                // 2012-02-03 00:01:01
  echo $dt->addSecond()."<br>";                   // 2012-02-03 00:01:02
  echo $dt->subSecond()."<br>";                   // 2012-02-03 00:01:01
  echo $dt->subSeconds(61)."<br>";                // 2012-02-03 00:00:00
  echo "<br>";

  echo $dt->addMilliseconds(61)."<br>";           // 2012-02-03 00:00:00
  echo $dt->addMillisecond()."<br>";              // 2012-02-03 00:00:00
  echo $dt->subMillisecond()."<br>";              // 2012-02-03 00:00:00
  echo $dt->subMillisecond(61)."<br>";            // 2012-02-03 00:00:00
  echo "<br>";

  echo $dt->addMicroseconds(61)."<br>";           // 2012-02-03 00:00:00
  echo $dt->addMicrosecond()."<br>";              // 2012-02-03 00:00:00
  echo $dt->subMicrosecond()."<br>";              // 2012-02-03 00:00:00
  echo $dt->subMicroseconds(61)."<br>";           // 2012-02-03 00:00:00
  echo "<br>";

  // and so on for any unit: millenium, century, decade, year, quarter, month, week, day, weekday,
  // hour, minute, second, microsecond.

  // Generic methods add/sub (or subtract alias) can take many different arguments:
  echo $dt->add(61, 'seconds')."<br>";                      // 2012-02-03 00:01:01
  echo $dt->sub('1 day')."<br>";                            // 2012-02-02 00:01:01
  echo $dt->add(CarbonInterval::months(2))."<br>";          // 2012-04-02 00:01:01
  echo $dt->subtract(new DateInterval('PT1H'))."<br>";      // 2012-04-01 23:01:01
  echo "<hr>";
  
  $dt = CarbonImmutable::create(2017, 1, 31, 0);
  echo $dt->addMonth()."<br>";                    // 2017-03-03 00:00:00
  echo $dt->subMonths(2)."<br>";                  // 2016-12-01 00:00:00
  
  $dt = CarbonImmutable::create(2017, 1, 31, 0);
  $dt->settings([
      'monthOverflow' => false,
  ]);
  echo "<br>";
  echo $dt->addMonth()."<br>";                    // 2017-02-28 00:00:00
  echo $dt->subMonths(2)."<br>";                  // 2016-11-30 00:00:00
  echo "<hr>";
  
  echo "<h2>Checker que la date correspond à une date de la semaine prochaine : </h2>";
  $dt = Carbon::now()->addWeek(1);
  echo $dt->format(DateTime::ISO8601)."<br>";
  if ($dt->isNextWeek()) {
    echo "semaine prochaine";
  } else {
    echo "Pas semaine prochaine";
  }
  echo "<hr>";
  
  $dt = CarbonImmutable::create(2021, 1, 31, 0);
  echo $dt->addDays(3)."<br>";
  echo $dt->subDays(3);
  echo "<hr>";
  ?>
</body>
</html>
