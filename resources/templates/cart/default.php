<? if (!$empty) : ?>
    <div class="ed-container">
        <div class="ed-main ed-my-20">
            <div class="ed-col-12">
                <? $_inclusion('page_nav_default.php'); ?>
            </div>
            <div class="ed-col-12 ed-content-center">
                <div class="ed-col-12">
                    <div class="ed-row ed-content-center">
                        <div class="ed-col-12 ed-content-center">
                            <h2><? echo $page->page_title; ?></h2>
                        </div>
                        <div class="ed-col-12">
                            <table class="ed-table ed-cp-table">
                                <thead>
                                    <tr class="ed-table-header ed-bg-cyan">
                                        <th scope="col" class="ed-no-mobile"></th>
                                        <th scope="col" class="ed-no-mobile"></th>
                                        <th scope="col"><? echo $page->product; ?></th>
                                        <th scope="col"><? echo $page->price; ?></th>
                                        <th scope="col"><? echo $page->amount; ?></th>
                                        <th scope="col"><? echo $page->sub_total; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ed-border-shadow">
                                    <? foreach ($products as $product) : ?>
                                        <tr class="ed-cart-item" data-prod_id="<? echo $product->slug; ?>">
                                            <td class="ed-no-mobile">
                                                <a role="button" class="ed-ax-btn" title="remove" data-url="<? echo $page->remove_url; ?>"><i class="bi bi-cart-x"></i></a>
                                            </td>
                                            <td class="ed-no-mobile"><i class="bi bi-capsule-pill"></i></td>
                                            <td data-title="<? echo $page->product; ?>"><? echo $product->title; ?>
                                                <a role="button" class="ed-mobile ed-ax-btn" title="remove" data-url="<? echo $page->remove_url; ?>"><i class="bi bi-x-circle"></i></a>
                                            </td>
                                            <td data-title="<? echo $page->price; ?>"><? echo $with_currency($product->price); ?></td>
                                            <td data-title="<? echo $page->amount; ?>">
                                                <div class="ed-buttons-container">
                                                    <span class="ed-text-dark"><? echo $product->cart_amount; ?></span>
                                                </div>
                                            </td>
                                            <td data-title="<? echo $page->sub_total; ?>"><? echo $with_currency($product->sub_total); ?></td>
                                        </tr>
                                    <? endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ed-col-11 ed-content-end">
                <div class="ed-col-6">
                    <h2 class="ed-content-center"><? echo $page->total; ?></h2>
                    <table class="ed-table ed-mobile-border">
                        <tbody>
                            <tr class="ed-table-header ed-bg-lgrey">
                                <th><? echo $page->sub_total; ?></th>
                                <td data-title="<? echo $page->sub_total; ?>">
                                    <span class="ed-ax-data cart-sub_total"><? echo $with_currency($sub_total); ?></span>
                                </td>
                            </tr>
                            <tr class="ed-table-header ed-bg-lgrey">
                                <th><? echo $page->shipping; ?></th>
                                <td data-title="<? echo $page->shipping; ?>">
                                    <? $_inclusion("shipping_default.php"); ?>
                                </td>
                            </tr>
                            <tr class="ed-table-header ed-bg-lgrey">
                                <th><? echo $page->total; ?></th>
                                <td data-title="<? echo $page->total; ?>">
                                    <strong>
                                        <span class="ed-ax-data cart-total"><? echo $with_currency($total); ?></span>
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="ed-col-12 ed-content-center ed-my-20">
                        <div class="ed-col-6 ed-content-end ed-my-20">
                            <a href="<? echo $page->checkout_url; ?>" role="button" class="ed-buy-btn ed-btn-blue"><? echo $page->checkout; ?></a>
                            <input type="submit" class="ed-buy-btn ed-btn-blue" target="<? echo $page->checkout_url; ?>"><? echo $page->checkout; ?></input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>