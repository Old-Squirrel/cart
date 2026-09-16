(function () {
    const _byid = id => document.getElementById(id); const _select = (selector, context = document) => context.querySelector(selector);
    const _selectall = (selector, context = document) => context.querySelectorAll(selector); const _bytag = (tag, context = document) => context.getElementsByTagName(tag);
    const _byclass = (class_name, context = document) => context.getElementsByClassName(class_name);
    const page_update = () => {
        [..._bytag('p')].filter(p => p.outerHTML == "<p></p>").forEach(p => p.remove());
        [..._selectall(".ed-form-input input, .ed-form-input select")].forEach(input => input.required = input.parentElement.classList.contains('required'));
        const cc_pay = _select('#pay_1');
        if (cc_pay) cc_pay.checked = "checked";
    };
    const ship_route = slug => _byid(slug).dataset.url + slug; const prod_route = slug => _select("tr[data-prod_id=" + slug + "] a.ed-ax-btn").dataset.url + slug;
    const cart_items = () => {
        const list = _byclass("ed-cart-item");
        return {
            "prods": [...list].filter(item => item.dataset.prod_id),
            "ships": [...list].filter(item => item.dataset.ship_id),
        };
    };
    async function _getdata(uri) {
        let response = await fetch(uri, {
            method: 'GET',
            mode: 'same-origin',
            cache: 'no-store',
            referrerPolicy: "origin-when-cross-origin"
        });
        
        let cart = await response.json();
        if (cart.response) cart.data.empty ? this.location.reload() : sync_page(cart.data);
    }
    const sync_cart = route => {
        let re = /\/[a-z]{3,6}\/[a-z]{3,6}-[0-9]{1,5}\-[0-9]{1,2}/;
        route = re.exec(route);
        if(route){
            route = '/cart' + route;
            _getdata(route);
        }
      
    };
    const sync_totals = data => {
        const set_cart_data = (class_name) => [..._byclass("ed-ax-data")].filter(el => el.classList.contains(class_name));
        set_cart_data('cart-total_amount').map(el => el.innerText = data.total_amount);
        set_cart_data('cart-total').map(el => el.innerText = data.total);
        set_cart_data('cart-sub_total').map(el => el.innerText = data.sub_total);
    }
    const sync_page = cart => {
        sync_totals(cart); sync_shipping(cart);
        if (cart.removed) {
            let prod = _select('tr[data-prod_id =' + cart.removed + ']');
            prod ? prod.remove() : null;
        }
    };
    const sync_shipping = (cart_data => {
        cart_items().ships.forEach(li => {
            let found = cart_data.shipping_list.find(sh => sh.slug == li.dataset['ship_id']);
            if (!found) li.remove(); let chosen = _byid(cart_data.chosen_shipping);
            if (!chosen) this.location.reload(); chosen.checked = "checked";
        });
    });
    cart_items().ships.forEach(s => _byid(s.dataset.ship_id).addEventListener("click", () => { const route = ship_route(s.dataset.ship_id); sync_cart(route); }));
    cart_items().prods.forEach(p => {
        const btns = _selectall("tr[data-prod_id=" + p.dataset.prod_id + "] a.ed-ax-btn");
        const route = prod_route(p.dataset.prod_id);
        btns.forEach(btn => btn.addEventListener("click", () => sync_cart(route)));
    });
    [..._selectall("div.ed-toggle-area")].forEach(area => {
        const toggler = _select("div.ed-toggler", area); const targets = [..._selectall('.ed-toggle-target[data-toggle_id]', area)];
        const selected = () => targets.filter(select => select.classList.contains("selected")); const active = () => [..._byclass('ed-btn-toggle active', area)];
        const toggle_target = (val) => targets.filter(target => target.dataset.toggle_id == val);
        const area_control = div => {
            let child = _bytag('input', div).item(0);
            child = child ? child : _bytag('select', div).item(0);
            let state = div.classList.contains('required');
            if (child) child.required = state;
        };
        [..._selectall("input[type=radio]", toggler)].forEach(inp => {
            inp.addEventListener("click", () => {
                selected().forEach(target => {
                    target.classList.remove('selected');[..._byclass('ed-form-input', target)].forEach(div => { div.classList.remove('required'); area_control(div) });
                });
                toggle_target(event.currentTarget.id).forEach(target => {
                    target.classList.add('selected');[..._byclass('ed-form-input', target)].forEach(div => { div.classList.add('required'); area_control(div) });
                });
            });
        });
        [..._selectall(".ed-btn-toggle", toggler)].forEach(btn => {
            btn.addEventListener("click", () => {
                active().map(button => button.classList.remove('active')); selected().map(target => target.classList.remove('selected'));
                event.currentTarget.classList.add('active'); toggle_target(event.currentTarget.dataset.target).map(el => el.classList.add('selected'));
            });
        });
    });

    const jsv = () => {
        ver_list = ["1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "2.0"];
        jsversion = "";
        for (i = 0; i < ver_list.length; i++) {
            let g = document.createElement('script'), s = _bytag('script')[0];
            g.setAttribute("language", "JavaScript" + ver_list[i]);
            g.text = "jsversion='" + ver_list[i] + "';"; s.parentNode.insertBefore(g, s); g.remove();
        }
        return "javascript " + jsversion;
    }

    [..._byclass('ed-buy-btn')].forEach(buy_btn => {
        buy_btn.addEventListener("click", () => {
            event.preventDefault();
            let d = new Date();
            let cookie_data = [window.screen.colorDepth, window.screen.width + 'x' + window.screen.height,
            jsv(), d.toLocaleString().split(" ").slice(-1) + '|' + d.getTimezoneOffset()].join('~,');
            document.cookie = "shopcustomer=" + cookie_data + "; secure=" + true + "; path = /; SameSite=Lax;";
            window.location.href = buy_btn.pathname;
        })
    })

    page_update();
}).call(this);