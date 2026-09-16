<div class="ed-table-container">
    <div class="ed-col-12 ed-toggle-area">
        <div class="ed-toggle-container" role="menu">
            <div class="ed-toggler">
<? foreach ($categories as $group) : ?>
<a role="button" class="ed-btn-toggle ed-btn-blue <? echo $active(); ?>" data-target="<? echo $data_target($group);?>"><? echo $group . $table->units; ?></a>
<? endforeach; ?>
</div></div>
<div class="ed-col-12">
<? foreach ($categories as $group) : ?>
<h2 class="<? echo $target_class($selected($group)); ?>" data-toggle_id="<? echo $data_target($group);?>">
<? echo $table->title . html_entity_decode("&nbsp;");?><span><? echo $group . $table->units;?></span></h2>
<? endforeach; ?>
</div>
        <div class="ed-products ed-border-shadow">
            <table class="ed-table">
                <thead>
                    <tr class="ed-table-header ed-bg-cyan">
                        <th scope="col"><? echo $table->package; ?></th>
                        <th scope="col"><? echo $table->price; ?></th>
                        <th scope="col"><? echo $table->per_one; ?></th>
                        <th scope="col"><? echo $table->economy; ?></th>
                        <th scope="col"><? echo $table->bonus; ?></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($product_group as $group => $products) : ?>
                        <? foreach ($products as $prod) : ?>
                            <tr class="<? echo $target_class($selected($group), $prod->bestseller());?>" data-toggle_id="<? echo $data_target($group);?>">
                                <td data-title="<? echo $table->package;?>"><? echo $prod->package();?></td>
                                <td data-title="<? echo $table->price;?>">
                                    <div itemprop="offers" itemscope="itemscope" itemtype="https://schema.org/Offer">
                                        <? if (!$prod->price()->is_first) : ?>
                                        <span class="ed-cross-out"><? echo $prod->price()->crossed; ?></span>
                                        <span class="ed-delimiter"><i><? echo $prod->price()->delimiter; ?></i></span><? endif; ?>
                                        <meta itemprop="url" content="<? echo $table->page_url;?>">
                                        <link itemprop="availability" href="https://schema.org/InStock">
<? if ($prod->currency()->value_after_symbol) : ?>
<span itemprop="priceCurrency" content="<? echo $prod->currency()->title;?>">
<? echo $prod->currency()->symbol; ?></span><? echo "&nbsp;"; ?><span itemprop="price" content="<? echo $prod->price()->value;?>"><? echo $prod->price()->value;?></span>
<? else : ?>
<span itemprop="price" content="<? echo $prod->price()->value;?>">
<? echo $prod->price()->value; ?></span><? echo "&nbsp;"; ?><span itemprop="priceCurrency" content="<? echo $prod->currency()->title; ?>"><? echo $prod->currency()->symbol; ?>
</span><? endif; ?></div></td>
                                <td data-title="<? echo $table->per_one;?>"><? echo $prod->per_one(); ?></td>
                                <td data-title="<? echo $table->economy;?>"><span class="ed-text-red"><? echo $prod->economy();?></span></td>
                                <td data-title="<? echo $table->bonus;?>"><? echo $prod->bonus(); ?></td>
                                <td><a href="<? echo $prod->cart_add_url();?>" class="ed-buy-btn ed-btn-blue" role="button" rel="noindex, nofollow"><? echo $table->buy ?></a></td>
                            </tr>
                        <? endforeach; ?>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>