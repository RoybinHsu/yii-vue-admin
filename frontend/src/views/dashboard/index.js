!function (e) {
  var t = {};

  function r(n) {
    if (t[n])
      return t[n].exports;
    var o = t[n] = {
      i: n,
      l: !1,
      exports: {}
    };
    return e[n].call(o.exports, o, o.exports, r),
      o.l = !0,
      o.exports
  }

  r.m = e,
    r.c = t,
    r.d = function (e, t, n) {
      r.o(e, t) || Object.defineProperty(e, t, {
        enumerable: !0,
        get: n
      })
    }
    ,
    r.r = function (e) {
      "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
        value: "Module"
      }),
        Object.defineProperty(e, "__esModule", {
          value: !0
        })
    }
    ,
    r.t = function (e, t) {
      if (1 & t && (e = r(e)),
      8 & t)
        return e;
      if (4 & t && "object" == typeof e && e && e.__esModule)
        return e;
      var n = Object.create(null);
      if (r.r(n),
        Object.defineProperty(n, "default", {
          enumerable: !0,
          value: e
        }),
      2 & t && "string" != typeof e)
        for (var o in e)
          r.d(n, o, function (t) {
            return e[t]
          }
            .bind(null, o));
      return n
    }
    ,
    r.n = function (e) {
      var t = e && e.__esModule ? function () {
            return e.default
          }
          : function () {
            return e
          }
      ;
      return r.d(t, "a", t),
        t
    }
    ,
    r.o = function (e, t) {
      return Object.prototype.hasOwnProperty.call(e, t)
    }
    ,
    r.p = "",
    r(r.s = 1382)
}({
  1382: function (e, t) {
    var r = function (e, t) {
      var r = e.match(new RegExp("[?&]" + t + "=([^&]+)"));
      return r ? r[1] : null
    };
    window.DTFrameLogin = function (e, t, n, o) {
      var i, u = e.id && document.getElementById(e.id) || null, c = document.createElement("iframe");
      t.client_id && t.redirect_uri && t.response_type && t.scope ? u ? (u.innerHTML = "",
        u.appendChild(c),
        c && c.contentWindow && c.contentWindow.postMessage && window.addEventListener ? (c.src = "https://" + ((i = t).isPre ? "pre-login" : "login") + ".dingtalk.com/oauth2/auth?iframe=true&redirect_uri=" + i.redirect_uri + "&response_type=" + i.response_type + "&client_id=" + i.client_id + "&scope=" + i.scope + (i.prompt ? "&prompt=" + i.prompt : "") + (i.state ? "&state=" + i.state : "") + (i.org_type ? "&org_type=" + i.org_type : "") + (i.corpId ? "&corpId=" + i.corpId : "") + (i.exclusiveLogin ? "&exclusiveLogin=" + i.exclusiveLogin : "") + (i.exclusiveCorpId ? "&exclusiveCorpId=" + i.exclusiveCorpId : ""),
          c.width = "" + (e.width || 300),
          c.height = "" + (e.height || 300),
          c.frameBorder = "0",
          c.scrolling = "no",
          window.addEventListener("message", (function (e) {
              var t = e.data
                , i = e.origin;
              if (/login\.dingtalk\.com/.test(i) && t)
                if (t.success && t.redirectUrl) {
                  var u = t.redirectUrl
                    , c = r(u, "authCode") || ""
                    , d = r(u, "state") || ""
                    , s = r(u, "error") || "";
                  c ? n && n({
                    redirectUrl: u,
                    authCode: c,
                    state: d
                  }) : o && o(s)
                } else
                  o && o(t.errorMsg)
            }
          ))) : o && o("Browser not support")) : o && o("Element not found") : o && o("Missing parameters")
    }
  }
});
