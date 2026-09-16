<div data-toggle_id="<? echo $method->slug; ?>" class="ed-toggle-target ed-top-border selected"
id="<? echo 'payment_' . $method->id; ?>" aria-labelledby="<? echo $method->slug; ?>" >
    <div class="ed-col-12 ed-form-input required">
        <label for="card_name">
            <? echo $page->card_name; ?>
        </label>
        <input type="text" name="payment[card_name]" id="card_name" autocomplete="off"  placeholder="<? echo $page->card_name; ?>"/>
    </div>
    <div class="ed-col-12 ed-my-20 ed-form-input required">
        <label for="card_number">
            <? echo $page->card_number->label; ?>
        </label>
        <input type="tel" name="payment[card_number]" id="card_number" placeholder="<? echo $page->card_number->placeholder; ?>" autocomplete="off" inputmode="numeric" />
    </div>
    <div class="ed-col-12 ed-my-20 ed-form-input required">
        <label for="card_type">
            <? echo $page->card_type; ?>
        </label>
        <select name="payment[card_type]" id="card_type">
            <optgroup label="<? echo $page->select_card; ?>">
                <? foreach ($page->card_types as $option_value => $card_type) : ?>
                    <option value="<? echo $option_value; ?>">
                        <? echo $card_type; ?>
                    </option>
                <? endforeach; ?>
            </optgroup>
        </select>
    </div>
    <div class="ed-col-12 ed-my-20 ed-form-input required">
        <label for="card_expiry">
            <? echo $page->card_date->label; ?>
        </label>
        <input type="tel" name="payment[card_expiry]" id="card_expiry" placeholder="<? echo $page->card_date->placeholder; ?>" autocomplete="off" inputmode="numeric" />
    </div>
    <div class="ed-col-12 ed-my-20 ed-form-input required">
        <label for="card_cvv">
            <? echo $page->card_cvv->label; ?>
        </label>
        <input type="tel" name="payment[card_cvv]" id="card_cvv" placeholder="<? echo $page->card_cvv->placeholder; ?>" autocomplete="off" inputmode="numeric" />
    </div>
</div>