<?php

use mpyw\Co\Co;
use mpyw\Co\CURLException;

require __DIR__ . '/client-init.php';

// Wait 7 sec
print_time();
$result = Co::wait([curl('/rest', ['id' => 1, 'sleep' => 7]), function () {
    // Wait 4 sec
    print_r(yield [
        curl('/rest', ['id' => 2, 'sleep' => 3]),
        curl('/rest', ['id' => 3, 'sleep' => 4]),
    ]);
    print_time();
    // Wait 2 sec
    print_r(yield [
        function () {
            // Wait 1 sec
            echo yield curl('/rest', ['id' => 4, 'sleep' => 1]), "\n";
            print_time();
            return curl('/rest', ['id' => 5, 'sleep' => 1]);
        },
        function () {
            // Wait 0 sec
            echo (yield CO::SAFE => curl_init('invaild'))->getMessage(), "\n";
            print_time();
            try {
                // Wait 0 sec
                yield curl_init('invalid');
            } catch (CURLException $e) {
                echo $e->getMessage(), "\n";
                print_time();
            }
            return ['x' => ['y' => function () {
                return curl('/rest', ['id' => 6, 'sleep' => 2]);
            }]];
        }
    ]);
    print_time();
    return curl('/rest', ['id' => 7, 'sleep' => 1]);
}], ['interval' => 0.05]);
print_r($result);
print_time();
