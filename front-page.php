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
//todo: добавлять записи на определенную дату
//todo: удалять записи из базы

get_header(); ?>
<?php
$categories = helper()->cost_table->get_tf_table_field('tf_usercat', get_current_user_id(), 'user_id');
$cost_data_by_user = helper()->cost_table->get_data_by_user(get_current_user_id());
$cost_data_by_date = helper()->cost_table->get_data_by_date(date("d.m.Y", strtotime('+3 hours')));


////////////////
$cost_data_by_user = helper()->cost_table->get_data_by_user(get_current_user_id());



helper()->backend->print_arr($cost_data_by_date);
//helper()->backend->print_arr($cost_data);
/////////////
?>
    <div class="week-days__datepicker datepicker js-datepicker"></div><!-- / .datepicker -->

    <div class="right_cost_popup show">
        <div class="cost_popup__header">
            <div class="title_popup">Дата расходов</div>
            <div class="cost_date_input">
                <input id="cost_date" class="title_date" type="text" name="cost_date"
                       placeholder="<?php echo date("d.m.Y", strtotime('+3 hours'))?>"
                       value="<?php echo date("d.m.Y", strtotime('+3 hours'))?>">
            </div>
        </div>
        <div class="cost_popup__body">
            <?php if (!empty($cost_data_by_user) && isset($cost_data_by_user)){?>
                <ul class="expenses_list">
                    <?php foreach ($cost_data_by_user['cost_data'] as $cost_item) {?>
                        <li class="expenses_category">
                            <div class="expenses_category__name"><?php echo $cost_item['cost_category_name']?></div>
                            <?php
                            $total_amount = 0;
                            foreach ($cost_item['cost_category_values'] as $cost_count) {
                                $total_amount += $cost_count;
                            }?>
                            <div class="expenses_category__amount"><?php echo $total_amount?><span class="currency"> Руб</span></div>
                        </li>
                    <?php }?>
                </ul>
            <?php }?>
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
        <div class="cost_popup__footer"></div>
        <button class="hide_show__popup"><</button>
    </div>

<?php get_footer();
