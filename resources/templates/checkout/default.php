<? if (!$empty) : ?>
<div class="ed-container">
    <div class="ed-main">
        <div class="ed-col-12 ed-row ed-content-between">
            <? $_inclusion('page_nav_default.php'); ?>
            <nav class="ed-nav-link">
                <a href="<? echo $page->cart_url; ?>"><span><? echo $page->proceed_to_cart; ?></span></a>
            </nav>
        </div>
        <div class="ed-page-content">
            <div class="ed-notices"> </div>
                <div class="ed-col-12">
                    <h2 class="ed-text-center"><? echo $page->page_title; ?></h2>
                </div>
                <div class="ed-form-content">
                    <form class="ed-form ed-row ed-content-center" method="POST" action="<? echo $page->create_order_url; ?>" enctype="multipart/form-data">
                        <div class="ed-col-6 ed-left-side">
                            <div class="ed-col-12">
                                <h3 class="ed-text-center"><? echo $page->bill_and_shipp; ?></h3>
                            </div>
                            <div class="ed-col-12">
                                <div class="ed-row ed-content-between">
                                    <div class="ed-col-6 ed-form-input required">
                                        <label for="bill_fname"><? echo $page->first_name; ?></label>
                                        <input type="text" name="bill_fname" id="bill_fname" />
                                    </div>
                                    <div class="ed-col-6 ed-form-input required">
                                        <label for="bill_lname"><? echo $page->last_name; ?></label>
                                        <input type="text" name="bill_lname" id="bill_lname" />
                                    </div>
                                </div>
                            </div>
                            <div class="ed-col-12 ed-form-input required">
                                <label for="bill_country"><? echo $page->country; ?></label>
                                <select name="bill_country" id="bill_country">
                                    <optgroup label="<? echo $page->country; ?>">
                                        <? foreach ($page->countries as $country) : ?>
                                            <option value="<? echo $country->id; ?>">
                                                <? echo $country->name; ?>
                                            </option>
                                        <? endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="ed-col-12 ed-form-input required">
                                <label for="bill_city"><? echo $page->city; ?></label>
                                <input type="text" name="bill_city" id="bill_city" />
                            </div>
                            <div class="ed-col-12 ed-form-input required">
                                <label for="bill_address"><? echo $page->address->label; ?></label>
                                <input type="text" name="bill_address" id="bill_address" placeholder="<? echo $page->address->placeholder; ?>" />
                            </div>
                            <div class="ed-col-12 ed-form-input required">
                                <label for="bill_zip"><? echo $page->postcode; ?></label>
                                <input type="text" name="bill_zip" id="bill_zip" />
                            </div>
                            <div class="ed-col-12 ed-form-input required">
                                <label for="bill_phone"><? echo $page->phone; ?></label>
                                <input type="tel" name="bill_phone" id="bill_phone" />
                            </div>
                            <div class="ed-col-12 ed-form-input required">
                                <label for="bill_email"><? echo $page->email; ?></label>
                                <input type="email" name="bill_email" id="bill_email" />
                            </div>
                            <div class="ed-col-12 ed-my-20">
                                <h4 class="ed-text-center"><? echo $page->comments->title; ?></h4>
                            </div>
                            <div class="ed-col-12 ed-form-input">
                                <label for="order_comment"><? echo $page->comments->label; ?></label>
                                <textarea name="order_comment" id="order_comment" placeholder="<? echo $page->comments->placeholder; ?>"></textarea>
                            </div>
                        </div>
                        <div class="ed-col-6 ed-right-side">
                            <div class="ed-col-12">
                                <h3 class="ed-text-center"><? echo $page->total; ?></h3>
                            </div>
                            <div class="ed-col-12">
                                <div class="ed-row ed-content-between ed-bg-lgrey">
                                    <div class="ed-block-50">
                                        <div class="ed-col-12">
                                            <h4 class="ed-text-center ed-my-20"><? echo $page->product; ?></h4>
                                        </div>
                                    </div>
                                    <div class="ed-block-50">
                                        <div class="ed-col-12">
                                            <h4 class="ed-text-center ed-my-20"><? echo $page->sub_total; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <? foreach ($products as $product) : ?>
                                <div class="ed-col-12 ed-underline-grey">
                                    <div class="ed-block-row">
                                        <div class="ed-block-50">
                                            <div class="ed-col-12">
                                                <p><? echo $product->title . ' x '; ?><strong id="product_amount"><? echo $product->cart_amount; ?></strong></p>
                                            </div>
                                        </div>
                                        <div class="ed-block-50">
                                            <div class="ed-col-12">
                                                <p class="ed-text-center"><strong><? echo $with_currency($product->sub_total); ?></strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? endforeach; ?>
                            <div class="ed-col-12">
                                <div class="ed-row ed-content-center ed-my-20">
                                    <table class="ed-table ed-mobile-border">
                                        <tbody>
                                            <tr class="ed-table-header ed-bg-lgrey">
                                                <th><? echo $page->sub_total; ?> </th>
                                                <td data-title="<? echo $page->sub_total; ?>">
                                                    <span class="ed-ax-data cart-sub_total">
                                                        <? echo $with_currency($sub_total); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="ed-table-header ed-bg-lgrey">
                                                <th><? echo $page->shipping; ?></th>
                                                <td data-title="<? echo $page->shipping; ?>">
                                                    <? $_inclusion("shipping_default.php"); ?>
                                                </td>
                                            </tr>
                                            <tr class="ed-table-header ed-bg-lgrey">
                                                <th> <? echo $page->total; ?></th>
                                                <td data-title="<? echo $page->total; ?>">
                                                    <strong>
                                                        <span class="ed-ax-data cart-total">
                                                            <? echo $with_currency($total); ?>
                                                        </span>
                                                    </strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="ed-col-12 ed-p-10">
                                <div class="ed-col-12 ed-form-item ed-toggle-area">
                                    <div class="ed-col-12 ed-toggler">
                                        <div class="ed-row ed-content-around">
                                            <? foreach ($payment as $method) : ?>
                                                <div>
                                                    <input type="radio" value="<? echo $method->id; ?>" name="payment[id]" id="<? echo $method->slug; ?>" <? echo $method->checked; ?> />
                                                    <label for="<? echo $method->slug; ?>"><? echo $method->title; ?></label>
                                                </div>
                                            <? endforeach; ?>
                                        </div>
                                        <? foreach ($payment as $method) : ?>
                                            <? if (!empty($method->inclusion)) $_inclusion($method->inclusion, ['method' => $method]); ?>
                                        <? endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="ed-order-submit">
                                <input name="ed_submit_submit" value="1" hidden />
                            </div>
                            <div class="ed-content-center">
                                <button class="ed-btn-place-order" type="submit"><? echo $page->order_submit; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
<? endif; ?>