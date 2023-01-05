'use strict';

window.addEventListener("load", () => {
    const $el = document.getElementById("tailpress_clear-cache");
    $el.addEventListener('click', () => {
        const $spinner = document.createElement('span');
        $spinner.classList.add('spinner', 'is-active');
        $el.appendChild($spinner);

        fetch(tailpress_clear_cache_ajax_object.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' }),
            body: new URLSearchParams({
                action: 'tailpress_ajax_clear_cache',
                _ajax_nonce: tailpress_clear_cache_ajax_object.ajax_nonce,
            })
        }).then((response) => response.json())
            .then((data) => {
                if (data === "OK") {
                    $el.removeChild($spinner);
                }
            });
    });
});