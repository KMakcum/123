<?php


class TabFinanceCostTable {
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
     * Создает таблицу tf_userdates
     */
    function create_tf_userDates_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tf_userdates';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
        id bigint unsigned NOT NULL AUTO_INCREMENT,
        user_id bigint unsigned NOT NULL,
        user_name varchar(255) NOT NULL default '',
        cost_cr_date varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
        cost_data longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
                  PRIMARY KEY (id)
            ) {$charset_collate};" ;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Добавляет потраченную сумму за день
     *
     * @param $date_create
     * @format 01.01.2000
     * @param  $data
     *
     */
    function insert_tf_userDates_table($date_create, $data)
    {
        global $wpdb;
        $current_user = wp_get_current_user();
        $table_name = $wpdb->prefix . 'tf_userdates';
        $data = json_encode($data);

        if ( $wpdb->get_var("show tables like '".$table_name."'") !== $table_name ) {
            $this->create_tf_userDates_table();
        }

        $wpdb->insert(
            $table_name,
            array(
                'user_id' => get_current_user_id(),
                'user_name' => $current_user->data->user_login,
                'cost_cr_date' => $date_create,
                'cost_data' => $data,
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
            )
        );

        return true;
    }

    /**
     * Обновляет потраченную сумму за день
     *
     * @param $date_create
     * @format 01.01.2000
     * @param  $data
     *
     */
    function update_tf_userDates_table($date_create, $data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tf_userdates';
        $data = json_encode($data);
        $subs = array('cost_data' => $data);
        $where = array('user_id' => get_current_user_id(),'cost_cr_date' => $date_create);

        $wpdb->update($table_name, $subs, $where);


        $wpdb->update(
            $table_name,
            [
                'cost_data' => $data,
            ],
            [
                'user_id' => get_current_user_id(),
                'cost_cr_date' => $date_create,
            ]
        );
    }

    /**
     * Delete cache by version.
     *
     * @param int $product_id
     */
    public function delete_item_from_cache( $product_id ) {
        global $wpdb;

        $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->table_name} WHERE `var_id` = %s", $product_id ) );
    }


    /**
     * Возвращает данные по полю
     *
     * @param $value
     * @param $field
     *
     * @return array
     */
    function get_tf_table_field($table_name, $value, $field = 'id')
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;

        $results = $wpdb->get_results( $wpdb->prepare(
        /** @lang sql */ "SELECT * FROM `{$table_name}` WHERE %1s = %2s", $field, $value) );
        $results = json_decode(json_encode($results),true);

        return ( empty( $results ) ) ? [] : $results;
    }

    /**
     * Возвращает массив расходов за определенный день
     *
     * @param $cost_cr_date
     * @format 01.01.2000
     *
     * @return array
     */
    function get_data_by_date( $cost_cr_date )
    {
        $table_name = 'tf_userdates';
        $field = 'cost_cr_date';
        $data = $this->get_tf_table_field($table_name, $cost_cr_date , $field)[0];
        $data['cost_data'] = json_decode($data['cost_data'],true);

        return $data;
    }

    /**
     * Возвращает массив расходов для пользователя
     *
     * @param $user_id
     *
     * @return array
     */
    function get_data_by_user($user_id)
    {
        $table_name = 'tf_userdates';
        $data = $this->get_tf_table_field($table_name, $user_id )[0];
        $data['cost_data'] = json_decode($data['cost_data'],true);

        return $data;
    }

    /**
     * Добавляет/обновляет потраченную сумму за день
     *
     * @param $cost_cr_date
     * @format 01.01.2000
     * @param  $new_data
     *
     */
    function add_day_data($cost_cr_date, $new_data) {
        $cost_data = $this->get_data_by_date($cost_cr_date);
        $cost_day_data = $cost_data['cost_data'];

        if (!empty($cost_day_data) && isset($cost_day_data)) {
            $write_bd = true;
            foreach ($cost_day_data as $cat_key => $cat_value) {
                foreach ($cat_value as $cat_item_key => $cat_item_value) {
                    if ($new_data[0]['cost_category_slug'] == $cat_item_value) {
                        $new_cost_values = array_merge($new_data[0]['cost_category_values'],$cost_day_data[$cat_key]['cost_category_values']);
                        $cost_data['cost_data'][$cat_key]['cost_category_values'] = $new_cost_values;
                        $write_bd = false;
                    }
                }
            }
            if ($write_bd) {
                $cost_data['cost_data'] = array_merge($cost_day_data, $new_data);
            }
            $this->update_tf_userDates_table($cost_cr_date, $cost_data['cost_data']);
            return $cost_data['cost_data'];
        } else {
            $this->insert_tf_userDates_table($cost_cr_date, $new_data);
            return $new_data;
        }
    }



    /**
     * Создает таблицу tf_userCat
     */
    function create_tf_userCat_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tf_userCat';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
        user_id bigint unsigned NOT NULL,
        user_name varchar(255) NOT NULL default '',
        category_id bigint unsigned NOT NULL AUTO_INCREMENT,
        category_slug varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
        category_name varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
                  PRIMARY KEY (category_id)
            ) {$charset_collate};" ;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Добавляет категорию трат для пользователя
     *
     * @param $date_create
     * @format 01.01.2000
     * @param  $data
     *
     */
    function insert_tf_userCat_table($category_name)
    {
        global $wpdb;
        $current_user_id = get_current_user_id();
        $current_user = wp_get_current_user();
        $table_name = $wpdb->prefix . 'tf_userCat';
        $category_slug = helper()->backend->translit($category_name);
        $is_category_slug = '';

        if ( $wpdb->get_var("show tables like '".$table_name."'") !== $table_name ) {
            $this->create_tf_userCat_table();
        }

        $is_category_slug = $wpdb->get_results( $wpdb->prepare(
            'SELECT * FROM '.$table_name.' WHERE category_slug = %s AND user_id = '.get_current_user_id().' ', $category_slug) );
        $is_category_slug = json_decode(json_encode($is_category_slug),true);
        if (empty($is_category_slug)) {
            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $current_user_id,
                    'user_name' => $current_user->data->user_login,
                    'category_slug' => $category_slug,
                    'category_name' => $category_name,
                ),
                array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

        return true;
    }


}