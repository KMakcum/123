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
     * Получает данные за период и складывает их в новый массив
     *
     * @param array $cost_data_by_user
     *
     */
    function period_expenses  ($cost_data_by_user) {
        if (!empty($cost_data_by_user) && isset($cost_data_by_user)){
            $data_period_expenses = [];
            foreach ($cost_data_by_user as $cost_data_by_day) {
                foreach ($cost_data_by_day['cost_data'] as $key => $cost_item) {
                    $total_amount = 0;
                    foreach ($cost_item['cost_category_values'] as $cost_count) {
                        $total_amount += (int)$cost_count;
                    }
                    if ($cost_item['cost_category_slug'] == $data_period_expenses[$key]['expenses_slug']) {
                        $data_period_expenses[$key]['expenses_count'] = $data_period_expenses[$key]['expenses_count'] + $total_amount;
                    } else {
                        $data_period_new_expenses = [
                            'expense_name'   => $cost_item['cost_category_name'],
                            'expenses_slug'  => $cost_item['cost_category_slug'],
                            'expenses_count' => $total_amount
                        ];
                        array_push($data_period_expenses, $data_period_new_expenses);
                    }
                }
            }
            return $data_period_expenses;
        } else {
            return false;
        }
    }
}