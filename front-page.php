<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
<?php
$categories = helper()->cost_table->get_tf_table_field('tf_usercat', get_current_user_id(), 'user_id');


////////////////
$date_create = date("d.m.Y", strtotime('+3 hours'));
$cost_data = helper()->cost_table->get_data_by_date($date_create);
$cost_day_data = $cost_data['cost_data'];

$new_data = [
        [
            'cost_category_slug' => 'pitanie123',
            'cost_category_name' => 'Питание',
            'cost_category_values' => [
                date("H:i:s", strtotime('+3 hours'))  =>  1111
            ]
        ]
];

$fields = helper()->cost_table->get_tf_table_field('tf_usercat', 1, 'user_id');

helper()->backend->print_arr($fields);
helper()->backend->print_arr($cost_data);
/////////////
?>
    <div class="week-days__datepicker datepicker js-datepicker"></div><!-- / .datepicker -->

    <div class="right_cost_popup">
        <form action=""></form>
        <div class="cost_popup__header">
            <div class="title_popup">Дата траты</div>
            <div class="cost_date_input">
                <input id="cost_date" type="text" name="cost_date"
                       placeholder="<?php echo date("d.m.Y", strtotime('+3 hours'))?>"
                       value="<?php echo date("d.m.Y", strtotime('+3 hours'))?>">
            </div>
            <!--        <div class="title_popup">--><?php //echo date("d.m.Y", strtotime('+3 hours'))?><!--</div>-->
        </div>
        <div class="cost_popup__body"></div>
        <div class="cost_popup__footer"></div>
        <ul class="list_cost">
            <?php if (!empty($categories) && isset($categories)) {
                foreach ($categories as $category) {?>
                    <li class="cost_item add__cost_item" data-category="<?php echo $category['category_slug']?>">
                        <button class="cost_btn cost_item__trigger" type="button">
                            <svg width="32" height="32" fill="#252728">
                                <path d="M 17,12 l -5,5 l 5,5"></path>
                            </svg>
                            <span class="cost_category_name"><?php echo $category['category_name']?></span>
                        </button>
                        <div class="new__cost_item" style="display: none">
                            <form class="form__add_new_cost">
                                <input id="cost_value" class="cost_value" name="cost_value" type="text" placeholder="Сумма">
                                <button type="submit" class="add_cost_btn">Добавить</button>
                            </form>
                        </div>
                    </li>
                <?php }
            }?>
            <li class="cost_item add__cost_category_item">
                <button class="cost_btn cost_category_item__trigger" type="button">
                    <svg width="32" height="32" fill="#252728">
                        <path d="M 17,12 l -5,5 l 5,5"></path>
                    </svg>
                    <span>Добавить</span>
                </button>
                <div class="new__cost_item" style="display: none">
                    <form class="form__add_new_category_cost">
                        <input class="cost_category_name" name="cost_category_name" type="text" placeholder="Название">
                        <input class="cost_value" name="cost_value" type="text" placeholder="Сумма">
                        <button type="submit" class="add_cost_btn">Добавить</button>
                    </form>
                </div>
            </li>
        </ul>
    </div>

<?php get_footer();
