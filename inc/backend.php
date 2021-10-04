<?php


class TabFinanceBackend {
    private static $_instance = null;

    private function __construct() {

    }
    static public function getInstance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    function init() {

    }

    /**
     * Переводит кирилицу в латиницу
     *
     * @param string $s
     *
     * @return string
     */
    function translit($value) {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }


    /**
     * Приводит массив к читабельному виду
     *
     * @param array $arr
     *
     */
    function print_arr ($arr) {
        print_r('-------------');
        print_r('<pre>');
        print_r($arr);
        print_r('</pre>');
        print_r('-------------');
    }


    /**
     * Собирает в расходы в читабельный массив
     *
     * @param array $arr
     *
     */
    function f ($arr) {

    }
}