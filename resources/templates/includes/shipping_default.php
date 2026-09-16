<ul class="ed-list">
<? foreach ($shipping_list as $sh) : ?>
<li data-ship_id="<? echo $sh->slug; ?>" class="ed-cart-item">
<input type="radio" class="ed-ax-btn" data-url="<? echo $page->update_url; ?>" name="shipping" value="<? echo $sh->slug; ?>" id="<? echo $sh->slug; ?>" <? echo $sh->checked; ?> />
<label for="<? echo $sh->slug; ?>"><? echo $sh->description . ' : ' . $sh->price; ?></label>
</li>
<? endforeach; ?>
</ul>