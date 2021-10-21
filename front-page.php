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
//todo: вывести все дни
//todo: добавлять записи на определенную дату
//todo: удалять записи из базы

get_header(); ?>
<?php
$categories = helper()->cost_table->get_tf_table_field('tf_usercat', get_current_user_id(), 'user_id');
$cost_data_by_user = helper()->cost_table->get_data_by_user(get_current_user_id());
$cost_data_by_date = helper()->cost_table->get_data_by_date(date("d.m.Y", strtotime('+3 hours')));


////////////////
$cost_data_by_user1 = helper()->cost_table->get_tf_table_field('tf_userdates',get_current_user_id(), 'id');


get_user_meta(1);
helper()->backend->print_arr($cost_data_by_user);
//helper()->backend->print_arr($cost_data);
/////////////
?>
    <div class="week-days__datepicker datepicker js-datepicker"></div><!-- / .datepicker -->

<div class="right_sidebar">
    <div class="right_sidebar__header">
        <a href="/" class="logo_content">
            <i class='bx bx-analyse' ></i>
            <div class="logo_name">TabFinance</div>
        </a>
        <div class="expenses_date">
            <div class="expenses_date__title">Потрачено за</div>
            <div class="expenses_date__field">
                <input class="expenses_date__input" type="text" name="expenses_date__input"
                       placeholder="<?php echo date("d.m.Y", strtotime('+3 hours'))?>"
                       value="<?php echo date("d.m.Y", strtotime('+3 hours'))?>">
            </div>
        </div>
    </div>
    <div class="right_sidebar__body">
        <div class="expenses">
            <ul class="expenses_list">
                <?php if (!empty($cost_data_by_user) && isset($cost_data_by_user)){
                    foreach ($cost_data_by_user['cost_data'] as $cost_item) {?>
                        <li class="expenses_category">
                            <div class="category_name"><?php echo $cost_item['cost_category_name']?></div>
                            <?php
                            $total_amount = 0;
                            foreach ($cost_item['cost_category_values'] as $cost_count) {
                                $total_amount += $cost_count;
                            }?>
                            <div class="category__amount">
                                <?php echo $total_amount?>
                                <i class='bx bx-ruble' ></i>
                            </div>
                        </li>
                    <?php }?>
                    <li class="expenses_category add_new__expenses_category">
                        <div class="expenses_btn expenses_btn__trigger" type="button">
                            <i class='bx bx-chevron-left'></i>
                            <span>Добавить</span>
                        </div>
                        <ul class="new__expenses_list" style="display: none">
                            <?php if (!empty($categories) && isset($categories)) {
                                foreach ($categories as $category) {?>
                                    <li class="new__expenses_category" data-category="<?php echo $category['category_slug']?>">
                                        <div class="new__expenses_category--name"><span><?php echo $category['category_name']?></span></div>
                                        <div class="add__expenses_category" style="display: none">
                                            <form class="form_add__expenses_category">
                                                <input id="expenses_value" class="expenses_value" name="expenses_value" type="text" placeholder="Сумма">
                                                <button type="submit" class="add_expenses_btn">Добавить</button>
                                            </form>
                                        </div>
                                    </li>
                                <?php }
                            }?>
                            <li class="new__expenses_category">
                                <div class="new__expenses_category--name">Добавить новую</div>
                                <div class="add__expenses_category" style="display: none">
                                    <form class="form_add__expenses_category">
                                        <input class="expenses_category_name" name="expenses_category_name" type="text" placeholder="Название">
                                        <input class="expenses_value" name="expenses_value" type="text" placeholder="Сумма">
                                        <button type="submit" class="add_expenses_category_btn">Добавить</button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                <?php }?>
            </ul>
        </div>
    </div>
    <div class="right_sidebar__footer"></div>
</div>
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
