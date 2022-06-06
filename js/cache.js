'use strict';

window.addEventListener("load", () => {
    fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            const classNames = [...new Set(
                Array.from(html.matchAll(/class="([^"]+)"/g))
                    .map(el => el[1].split(' ')).flat())
            ];

            const hash = md5(classNames.join(' '));
            fetch(tailpress_ajax_object.ajax_url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' }),
                body: new URLSearchParams({
                    action: 'tailpress_ajax',
                    _ajax_nonce: tailpress_ajax_object.ajax_nonce,
                    url: window.location.href,
                    hash: hash,
                    css: document.querySelector('head style:last-of-type')?.innerText,
                })
            }).then(response => response.json())
                .then(data => console.log(data));
        });
});
