'use strict';

window.addEventListener("load", () => {
    const $el = document.getElementById("tailpress_clear-cache");
    $el.addEventListener('click', () => {
        fetch(tailpress_clear_cache_ajax_object.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' }),
            body: new URLSearchParams({
                action: 'tailpress_ajax_clear_cache',
                _ajax_nonce: tailpress_clear_cache_ajax_object.ajax_nonce,
            })
        });
    });
});