(function(w, d) {
    let b = d.getElementsByTagName('body')[0],
        e = d.documentElement,
        wWidth = w.innerWidth || e.clientWidth || b.clientWidth,
        wHeight = w.innerHeight || e.clientHeight || b.clientHeight;

    if (foodpress.font_uri) {
        let font = d.createElement('link');
        font.async = true;
        font.type = 'text/css';
        font.rel = 'stylesheet';
        font.href = foodpress.font_uri;
        b.appendChild(font);
    }

    let icon = d.createElement('link');
    icon.async = true;
    icon.type = 'text/css';
    icon.rel = 'stylesheet';
    icon.href = 'https://cdn.lineicons.com/1.0.1/LineIcons.min.css';
    b.appendChild(icon);

    let lazyload = d.createElement('script'),
        lazyloadVersion = !('IntersectionObserver' in w) ? '8.17.0' : '10.19.0';
    lazyload.async = true;
    lazyload.src = 'https://cdn.jsdelivr.net/npm/vanilla-lazyload@' + lazyloadVersion + '/dist/lazyload.min.js';
    w.lazyLoadOptions = { elements_selector: '.lazy' };
    b.appendChild(lazyload);

    let autocomplete = d.createElement('script');
    autocomplete.async = true;
    autocomplete.src = 'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/js/autoComplete.min.js';
    b.appendChild(autocomplete);

    let navMenuToggle = document.getElementById('nav-menu-toggle');
    if (typeof(navMenuToggle) != 'undefined' && navMenuToggle !== null) {
        let navMenu = document.getElementById('nav-menu');
        if (typeof(navMenu) != 'undefined' && navMenu !== null) {
            navMenuToggle.onclick = function() {
                if (-1 !== navMenu.className.indexOf('show')) {
                    navMenu.classList.remove('show');
                    navMenu.style.visibility = 'hidden';
                    navMenu.style.opacity = 0;
                    navMenu.style.display = 'none';
                } else {
                    navMenu.classList.add('show');
                    navMenu.style.visibility = 'visible';
                    navMenu.style.opacity = 1;
                    navMenu.style.display = 'block';
                }
            }
        }
    }

    let navMenuLiHasChild = document.querySelectorAll('.menu-item-has-children');
    for (var i = 0, length = navMenuLiHasChild.length; i < length; i++) {
        let children = navMenuLiHasChild[i].querySelector('.sub-menu');
        navMenuLiHasChild[i].addEventListener(
            'click',
            function(event) {
                if (-1 !== children.className.indexOf('show')) {
                    children.classList.remove('show');
                    children.style.visibility = 'hidden';
                    children.style.opacity = 0;
                    children.style.display = 'none';
                } else {
                    children.classList.add('show');
                    children.style.visibility = 'visible';
                    children.style.opacity = 1;
                    children.style.display = 'block';
                }
                event.stopPropagation()
            },
            false
        );
    }

    let sliderjs = d.createElement('script');
    sliderjs.async = true;
    sliderjs.src = 'https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js';
    b.appendChild(sliderjs);

    sliderjs.onload = function() {
        let slider = d.querySelector('#splide1');
        if (typeof(slider) != 'undefined' && slider != null) {
            let splide1 = new Splide('#splide1', {
                arrows: false,
                type: 'loop',
                autoplay: true,
                pauseOnHover: true,
                pauseOnFocus: true,
                lazyLoad: 'sequential',
                interval: 3000,
            }).mount();
        }
    }

    let main = d.createElement('script');
    main.async = true;
    main.src = foodpress.main_script;
    b.appendChild(main);


}(window, document));

window.addComment = function(a) {
    function b() { c(), g() }

    function c(a) { if (t && (m = j(r.cancelReplyId), n = j(r.commentFormId), m)) { m.addEventListener("touchstart", e), m.addEventListener("click", e); for (var b, c = d(a), g = 0, h = c.length; g < h; g++) b = c[g], b.addEventListener("touchstart", f), b.addEventListener("click", f) } }

    function d(a) { var b, c = r.commentReplyClass; return a && a.childNodes || (a = q), b = q.getElementsByClassName ? a.getElementsByClassName(c) : a.querySelectorAll("." + c) }

    function e(a) {
        var b = this,
            c = r.temporaryFormId,
            d = j(c);
        d && o && (j(r.parentIdFieldId).value = "0", d.parentNode.replaceChild(o, d), b.style.display = "none", a.preventDefault())
    }

    function f(b) {
        var c, d = this,
            e = i(d, "belowelement"),
            f = i(d, "commentid"),
            g = i(d, "respondelement"),
            h = i(d, "postid");
        e && f && g && h && (c = a.addComment.moveForm(e, f, g, h), !1 === c && b.preventDefault())
    }

    function g() {
        if (s) {
            var a = { childList: !0, subTree: !0 };
            p = new s(h), p.observe(q.body, a)
        }
    }

    function h(a) {
        for (var b = a.length; b--;)
            if (a[b].addedNodes.length) return void c()
    }

    function i(a, b) { return u ? a.dataset[b] : a.getAttribute("data-" + b) }

    function j(a) { return q.getElementById(a) }

    function k(b, c, d, e) {
        var f = j(b);
        o = j(d);
        var g, h, i, k = j(r.parentIdFieldId),
            p = j(r.postIdFieldId);
        if (f && o && k) {
            l(o), e && p && (p.value = e), k.value = c, m.style.display = "", f.parentNode.insertBefore(o, f.nextSibling), m.onclick = function() { return !1 };
            try {
                for (var s = 0; s < n.elements.length; s++)
                    if (g = n.elements[s], h = !1, "getComputedStyle" in a ? i = a.getComputedStyle(g) : q.documentElement.currentStyle && (i = g.currentStyle), (g.offsetWidth <= 0 && g.offsetHeight <= 0 || "hidden" === i.visibility) && (h = !0), "hidden" !== g.type && !g.disabled && !h) { g.focus(); break }
            } catch (t) {}
            return !1
        }
    }

    function l(a) {
        var b = r.temporaryFormId,
            c = j(b);
        c || (c = q.createElement("div"), c.id = b, c.style.display = "none", a.parentNode.insertBefore(c, a))
    }
    var m, n, o, p, q = a.document,
        r = { commentReplyClass: "comment-reply-link", cancelReplyId: "cancel-comment-reply-link", commentFormId: "commentform", temporaryFormId: "wp-temp-form-div", parentIdFieldId: "comment_parent", postIdFieldId: "comment_post_ID" },
        s = a.MutationObserver || a.WebKitMutationObserver || a.MozMutationObserver,
        t = "querySelector" in q && "addEventListener" in a,
        u = !!q.documentElement.dataset;
    return t && "loading" !== q.readyState ? b() : t && a.addEventListener("DOMContentLoaded", b, !1), { init: c, moveForm: k }
}(window);