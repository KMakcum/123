<?php


class TabFinanceCostAjax {
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
        if( wp_doing_ajax() ) {
            add_action('wp_ajax_get_cost_categories_popup', [$this, 'get_cost_categories_popup']);
            add_action('wp_ajax_nopriv_get_cost_categories_popup', [$this, 'get_cost_categories_popup']);

            add_action('wp_ajax_set_new_cost', [$this, 'set_new_cost']);
            add_action('wp_ajax_nopriv_set_new_cost', [$this, 'set_new_cost']);

            add_action('wp_ajax_set_new_category_cost', [$this, 'set_new_category_cost']);
            add_action('wp_ajax_nopriv_set_new_category_cost', [$this, 'set_new_category_cost']);
        }
    }




    function get_cost_categories_popup() {
        if( ! wp_verify_nonce( $_POST['nonce'], 'costAjaxNonce' ) ) die( 'Stop!');

        $categories = helper()->cost_table->get_tf_table_field('tf_usercat', get_current_user_id(), 'user_id');
        if (!empty($categories) && isset($categories)) {

            wp_send_json_success(
                array(
                    'popup' => $categories,
                )
            );
        } else {
            $html = '';
            $html .= '<ul class="list_cost">
                        <li class="cost_item add__cost_category_item">
                            Добавить
                            <button class="cost_btn cost_category_item__trigger" type="button">
                                <svg width="24" height="24" fill="#252728">
                                    <!--                    <use href="https://dev-qa.lifechef.com/app/themes/sf-theme/assets/img/sprites/general.svg#icon-angle-rigth-light"></use>-->
                                </svg>
                            </button>
                        </li>
                        </ul>';

            wp_send_json_success(
                array(
                    'popup' => $html,
                )
            );
        }

        wp_send_json_error(
            array(
                'error' => 'not_categories_for_user'
            )
        );

        die();
    }

    function set_new_cost() {
        $cost_category_name = $_POST['cost_category_name'] ? $_POST['cost_category_name'] : 'N/A';
        $cost_category_slug = $_POST['cost_category_slug'] ? $_POST['cost_category_slug'] : 'N/A';
        parse_str($_POST['form'], $cost_form);

        $data = [
            [
                'cost_category_slug' => $cost_category_slug,
                'cost_category_name' => $cost_category_name,
                'cost_category_values' => [
                    date("H:i:s", strtotime('+3 hours'))  =>  $cost_form['expenses_value']
                ]
            ],

        ];

        $date_create = date("d.m.Y", strtotime('+3 hours'));
        helper()->cost_table->insert_tf_userCat_table($cost_category_name);
        $result = helper()->cost_table->add_day_data($date_create, $data);

        wp_send_json_success(
            array(
                'result' => $cost_form,
            )
        );

        die();
    }

    function set_new_category_cost() {
        parse_str($_POST['form'], $cost_form);
        $cost_category_name = $cost_form['expenses_category_name'];
        $cost_category_slug = helper()->backend->translit($cost_category_name);
        $data = [
            [
                'cost_category_slug' => $cost_category_slug,
                'cost_category_name' => $cost_category_name,
                'cost_category_values' => [
                    date("H:i:s", strtotime('+3 hours'))  =>  $cost_form['expenses_value']
                ]
            ]
        ];
        $date_create = date("d.m.Y", strtotime('+3 hours'));
        helper()->cost_table->insert_tf_userCat_table($cost_category_name);
        $result = helper()->cost_table->add_day_data($date_create, $data);

        wp_send_json_success(
            array(
                '$result' => $result,
            )
        );

        die();
    }
}