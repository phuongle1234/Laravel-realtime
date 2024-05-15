(function (t, e) {
    "object" === typeof exports && "object" === typeof module
        ? (module.exports = e(require("vue")))
        : "function" === typeof define && define.amd
        ? define([], e)
        : "object" === typeof exports
        ? (exports["VuePureLightbox"] = e(require("vue")))
        : (t["VuePureLightbox"] = e(t["Vue"]));
})("undefined" !== typeof self ? self : this, function (t) {
    return (function (t) {
        var e = {};
        function n(r) {
            if (e[r]) return e[r].exports;
            var o = (e[r] = { i: r, l: !1, exports: {} });
            return t[r].call(o.exports, o, o.exports, n), (o.l = !0), o.exports;
        }
        return (
            (n.m = t),
            (n.c = e),
            (n.d = function (t, e, r) {
                n.o(t, e) ||
                    Object.defineProperty(t, e, { enumerable: !0, get: r });
            }),
            (n.r = function (t) {
                "undefined" !== typeof Symbol &&
                    Symbol.toStringTag &&
                    Object.defineProperty(t, Symbol.toStringTag, {
                        value: "Module",
                    }),
                    Object.defineProperty(t, "__esModule", { value: !0 });
            }),
            (n.t = function (t, e) {
                if ((1 & e && (t = n(t)), 8 & e)) return t;
                if (4 & e && "object" === typeof t && t && t.__esModule)
                    return t;
                var r = Object.create(null);
                if (
                    (n.r(r),
                    Object.defineProperty(r, "default", {
                        enumerable: !0,
                        value: t,
                    }),
                    2 & e && "string" != typeof t)
                )
                    for (var o in t)
                        n.d(
                            r,
                            o,
                            function (e) {
                                return t[e];
                            }.bind(null, o)
                        );
                return r;
            }),
            (n.n = function (t) {
                var e =
                    t && t.__esModule
                        ? function () {
                              return t["default"];
                          }
                        : function () {
                              return t;
                          };
                return n.d(e, "a", e), e;
            }),
            (n.o = function (t, e) {
                return Object.prototype.hasOwnProperty.call(t, e);
            }),
            (n.p = ""),
            n((n.s = "fb15"))
        );
    })({
        "06cf": function (t, e, n) {
            var r = n("83ab"),
                o = n("d1e7"),
                i = n("5c6c"),
                c = n("fc6a"),
                u = n("c04e"),
                a = n("5135"),
                f = n("0cfb"),
                s = Object.getOwnPropertyDescriptor;
            e.f = r
                ? s
                : function (t, e) {
                      if (((t = c(t)), (e = u(e, !0)), f))
                          try {
                              return s(t, e);
                          } catch (n) {}
                      if (a(t, e)) return i(!o.f.call(t, e), t[e]);
                  };
        },
        "0cfb": function (t, e, n) {
            var r = n("83ab"),
                o = n("d039"),
                i = n("cc12");
            t.exports =
                !r &&
                !o(function () {
                    return (
                        7 !=
                        Object.defineProperty(i("div"), "a", {
                            get: function () {
                                return 7;
                            },
                        }).a
                    );
                });
        },
        "0ec0": function (t, e, n) {},
        "1be4": function (t, e, n) {
            var r = n("d066");
            t.exports = r("document", "documentElement");
        },
        "1d80": function (t, e) {
            t.exports = function (t) {
                if (void 0 == t) throw TypeError("Can't call method on " + t);
                return t;
            };
        },
        "23cb": function (t, e, n) {
            var r = n("a691"),
                o = Math.max,
                i = Math.min;
            t.exports = function (t, e) {
                var n = r(t);
                return n < 0 ? o(n + e, 0) : i(n, e);
            };
        },
        "241c": function (t, e, n) {
            var r = n("ca84"),
                o = n("7839"),
                i = o.concat("length", "prototype");
            e.f =
                Object.getOwnPropertyNames ||
                function (t) {
                    return r(t, i);
                };
        },
        "37e8": function (t, e, n) {
            var r = n("83ab"),
                o = n("9bf2"),
                i = n("825a"),
                c = n("df75");
            t.exports = r
                ? Object.defineProperties
                : function (t, e) {
                      i(t);
                      var n,
                          r = c(e),
                          u = r.length,
                          a = 0;
                      while (u > a) o.f(t, (n = r[a++]), e[n]);
                      return t;
                  };
        },
        "3bbe": function (t, e, n) {
            var r = n("861d");
            t.exports = function (t) {
                if (!r(t) && null !== t)
                    throw TypeError(
                        "Can't set " + String(t) + " as a prototype"
                    );
                return t;
            };
        },
        "428f": function (t, e, n) {
            var r = n("da84");
            t.exports = r;
        },
        "44ad": function (t, e, n) {
            var r = n("d039"),
                o = n("c6b6"),
                i = "".split;
            t.exports = r(function () {
                return !Object("z").propertyIsEnumerable(0);
            })
                ? function (t) {
                      return "String" == o(t) ? i.call(t, "") : Object(t);
                  }
                : Object;
        },
        "4d64": function (t, e, n) {
            var r = n("fc6a"),
                o = n("50c4"),
                i = n("23cb"),
                c = function (t) {
                    return function (e, n, c) {
                        var u,
                            a = r(e),
                            f = o(a.length),
                            s = i(c, f);
                        if (t && n != n) {
                            while (f > s) if (((u = a[s++]), u != u)) return !0;
                        } else
                            for (; f > s; s++)
                                if ((t || s in a) && a[s] === n)
                                    return t || s || 0;
                        return !t && -1;
                    };
                };
            t.exports = { includes: c(!0), indexOf: c(!1) };
        },
        "50c4": function (t, e, n) {
            var r = n("a691"),
                o = Math.min;
            t.exports = function (t) {
                return t > 0 ? o(r(t), 9007199254740991) : 0;
            };
        },
        5135: function (t, e) {
            var n = {}.hasOwnProperty;
            t.exports = function (t, e) {
                return n.call(t, e);
            };
        },
        5692: function (t, e, n) {
            var r = n("c430"),
                o = n("c6cd");
            (t.exports = function (t, e) {
                return o[t] || (o[t] = void 0 !== e ? e : {});
            })("versions", []).push({
                version: "3.6.5",
                mode: r ? "pure" : "global",
                copyright: "© 2020 Denis Pushkarev (zloirock.ru)",
            });
        },
        5899: function (t, e) {
            t.exports = "\t\n\v\f\r                　\u2028\u2029\ufeff";
        },
        "58a8": function (t, e, n) {
            var r = n("1d80"),
                o = n("5899"),
                i = "[" + o + "]",
                c = RegExp("^" + i + i + "*"),
                u = RegExp(i + i + "*$"),
                a = function (t) {
                    return function (e) {
                        var n = String(r(e));
                        return (
                            1 & t && (n = n.replace(c, "")),
                            2 & t && (n = n.replace(u, "")),
                            n
                        );
                    };
                };
            t.exports = { start: a(1), end: a(2), trim: a(3) };
        },
        "5c6c": function (t, e) {
            t.exports = function (t, e) {
                return {
                    enumerable: !(1 & t),
                    configurable: !(2 & t),
                    writable: !(4 & t),
                    value: e,
                };
            };
        },
        "623a": function (t, e, n) {
            "use strict";
            var r = n("fe1d"),
                o = n.n(r);
            o.a;
        },
        "69f3": function (t, e, n) {
            var r,
                o,
                i,
                c = n("7f9a"),
                u = n("da84"),
                a = n("861d"),
                f = n("9112"),
                s = n("5135"),
                l = n("f772"),
                p = n("d012"),
                d = u.WeakMap,
                h = function (t) {
                    return i(t) ? o(t) : r(t, {});
                },
                v = function (t) {
                    return function (e) {
                        var n;
                        if (!a(e) || (n = o(e)).type !== t)
                            throw TypeError(
                                "Incompatible receiver, " + t + " required"
                            );
                        return n;
                    };
                };
            if (c) {
                var b = new d(),
                    g = b.get,
                    x = b.has,
                    y = b.set;
                (r = function (t, e) {
                    return y.call(b, t, e), e;
                }),
                    (o = function (t) {
                        return g.call(b, t) || {};
                    }),
                    (i = function (t) {
                        return x.call(b, t);
                    });
            } else {
                var m = l("state");
                (p[m] = !0),
                    (r = function (t, e) {
                        return f(t, m, e), e;
                    }),
                    (o = function (t) {
                        return s(t, m) ? t[m] : {};
                    }),
                    (i = function (t) {
                        return s(t, m);
                    });
            }
            t.exports = { set: r, get: o, has: i, enforce: h, getterFor: v };
        },
        "6eeb": function (t, e, n) {
            var r = n("da84"),
                o = n("9112"),
                i = n("5135"),
                c = n("ce4e"),
                u = n("8925"),
                a = n("69f3"),
                f = a.get,
                s = a.enforce,
                l = String(String).split("String");
            (t.exports = function (t, e, n, u) {
                var a = !!u && !!u.unsafe,
                    f = !!u && !!u.enumerable,
                    p = !!u && !!u.noTargetGet;
                "function" == typeof n &&
                    ("string" != typeof e || i(n, "name") || o(n, "name", e),
                    (s(n).source = l.join("string" == typeof e ? e : ""))),
                    t !== r
                        ? (a ? !p && t[e] && (f = !0) : delete t[e],
                          f ? (t[e] = n) : o(t, e, n))
                        : f
                        ? (t[e] = n)
                        : c(e, n);
            })(Function.prototype, "toString", function () {
                return ("function" == typeof this && f(this).source) || u(this);
            });
        },
        7156: function (t, e, n) {
            var r = n("861d"),
                o = n("d2bb");
            t.exports = function (t, e, n) {
                var i, c;
                return (
                    o &&
                        "function" == typeof (i = e.constructor) &&
                        i !== n &&
                        r((c = i.prototype)) &&
                        c !== n.prototype &&
                        o(t, c),
                    t
                );
            };
        },
        7839: function (t, e) {
            t.exports = [
                "constructor",
                "hasOwnProperty",
                "isPrototypeOf",
                "propertyIsEnumerable",
                "toLocaleString",
                "toString",
                "valueOf",
            ];
        },
        "7c73": function (t, e, n) {
            var r,
                o = n("825a"),
                i = n("37e8"),
                c = n("7839"),
                u = n("d012"),
                a = n("1be4"),
                f = n("cc12"),
                s = n("f772"),
                l = ">",
                p = "<",
                d = "prototype",
                h = "script",
                v = s("IE_PROTO"),
                b = function () {},
                g = function (t) {
                    return p + h + l + t + p + "/" + h + l;
                },
                x = function (t) {
                    t.write(g("")), t.close();
                    var e = t.parentWindow.Object;
                    return (t = null), e;
                },
                y = function () {
                    var t,
                        e = f("iframe"),
                        n = "java" + h + ":";
                    return (
                        (e.style.display = "none"),
                        a.appendChild(e),
                        (e.src = String(n)),
                        (t = e.contentWindow.document),
                        t.open(),
                        t.write(g("document.F=Object")),
                        t.close(),
                        t.F
                    );
                },
                m = function () {
                    try {
                        r = document.domain && new ActiveXObject("htmlfile");
                    } catch (e) {}
                    m = r ? x(r) : y();
                    var t = c.length;
                    while (t--) delete m[d][c[t]];
                    return m();
                };
            (u[v] = !0),
                (t.exports =
                    Object.create ||
                    function (t, e) {
                        var n;
                        return (
                            null !== t
                                ? ((b[d] = o(t)),
                                  (n = new b()),
                                  (b[d] = null),
                                  (n[v] = t))
                                : (n = m()),
                            void 0 === e ? n : i(n, e)
                        );
                    });
        },
        "7f9a": function (t, e, n) {
            var r = n("da84"),
                o = n("8925"),
                i = r.WeakMap;
            t.exports = "function" === typeof i && /native code/.test(o(i));
        },
        "825a": function (t, e, n) {
            var r = n("861d");
            t.exports = function (t) {
                if (!r(t)) throw TypeError(String(t) + " is not an object");
                return t;
            };
        },
        "83ab": function (t, e, n) {
            var r = n("d039");
            t.exports = !r(function () {
                return (
                    7 !=
                    Object.defineProperty({}, 1, {
                        get: function () {
                            return 7;
                        },
                    })[1]
                );
            });
        },
        "861d": function (t, e) {
            t.exports = function (t) {
                return "object" === typeof t
                    ? null !== t
                    : "function" === typeof t;
            };
        },
        8875: function (t, e, n) {
            var r, o, i;
            (function (n, c) {
                (o = []),
                    (r = c),
                    (i = "function" === typeof r ? r.apply(e, o) : r),
                    void 0 === i || (t.exports = i);
            })("undefined" !== typeof self && self, function () {
                function t() {
                    var e = Object.getOwnPropertyDescriptor(
                        document,
                        "currentScript"
                    );
                    if (
                        !e &&
                        "currentScript" in document &&
                        document.currentScript
                    )
                        return document.currentScript;
                    if (e && e.get !== t && document.currentScript)
                        return document.currentScript;
                    try {
                        throw new Error();
                    } catch (d) {
                        var n,
                            r,
                            o,
                            i = /.*at [^(]*\((.*):(.+):(.+)\)$/gi,
                            c = /@([^@]*):(\d+):(\d+)\s*$/gi,
                            u = i.exec(d.stack) || c.exec(d.stack),
                            a = (u && u[1]) || !1,
                            f = (u && u[2]) || !1,
                            s = document.location.href.replace(
                                document.location.hash,
                                ""
                            ),
                            l = document.getElementsByTagName("script");
                        a === s &&
                            ((n = document.documentElement.outerHTML),
                            (r = new RegExp(
                                "(?:[^\\n]+?\\n){0," +
                                    (f - 2) +
                                    "}[^<]*<script>([\\d\\D]*?)<\\/script>[\\d\\D]*",
                                "i"
                            )),
                            (o = n.replace(r, "$1").trim()));
                        for (var p = 0; p < l.length; p++) {
                            if ("interactive" === l[p].readyState) return l[p];
                            if (l[p].src === a) return l[p];
                            if (
                                a === s &&
                                l[p].innerHTML &&
                                l[p].innerHTML.trim() === o
                            )
                                return l[p];
                        }
                        return null;
                    }
                }
                return t;
            });
        },
        8925: function (t, e, n) {
            var r = n("c6cd"),
                o = Function.toString;
            "function" != typeof r.inspectSource &&
                (r.inspectSource = function (t) {
                    return o.call(t);
                }),
                (t.exports = r.inspectSource);
        },
        "8bbf": function (e, n) {
            e.exports = t;
        },
        "8d5c": function (t, e, n) {
            "use strict";
            var r = n("0ec0"),
                o = n.n(r);
            o.a;
        },
        "90e3": function (t, e) {
            var n = 0,
                r = Math.random();
            t.exports = function (t) {
                return (
                    "Symbol(" +
                    String(void 0 === t ? "" : t) +
                    ")_" +
                    (++n + r).toString(36)
                );
            };
        },
        9112: function (t, e, n) {
            var r = n("83ab"),
                o = n("9bf2"),
                i = n("5c6c");
            t.exports = r
                ? function (t, e, n) {
                      return o.f(t, e, i(1, n));
                  }
                : function (t, e, n) {
                      return (t[e] = n), t;
                  };
        },
        "94ca": function (t, e, n) {
            var r = n("d039"),
                o = /#|\.prototype\./,
                i = function (t, e) {
                    var n = u[c(t)];
                    return (
                        n == f ||
                        (n != a && ("function" == typeof e ? r(e) : !!e))
                    );
                },
                c = (i.normalize = function (t) {
                    return String(t).replace(o, ".").toLowerCase();
                }),
                u = (i.data = {}),
                a = (i.NATIVE = "N"),
                f = (i.POLYFILL = "P");
            t.exports = i;
        },
        "9bf2": function (t, e, n) {
            var r = n("83ab"),
                o = n("0cfb"),
                i = n("825a"),
                c = n("c04e"),
                u = Object.defineProperty;
            e.f = r
                ? u
                : function (t, e, n) {
                      if ((i(t), (e = c(e, !0)), i(n), o))
                          try {
                              return u(t, e, n);
                          } catch (r) {}
                      if ("get" in n || "set" in n)
                          throw TypeError("Accessors not supported");
                      return "value" in n && (t[e] = n.value), t;
                  };
        },
        a691: function (t, e) {
            var n = Math.ceil,
                r = Math.floor;
            t.exports = function (t) {
                return isNaN((t = +t)) ? 0 : (t > 0 ? r : n)(t);
            };
        },
        a9e3: function (t, e, n) {
            "use strict";
            var r = n("83ab"),
                o = n("da84"),
                i = n("94ca"),
                c = n("6eeb"),
                u = n("5135"),
                a = n("c6b6"),
                f = n("7156"),
                s = n("c04e"),
                l = n("d039"),
                p = n("7c73"),
                d = n("241c").f,
                h = n("06cf").f,
                v = n("9bf2").f,
                b = n("58a8").trim,
                g = "Number",
                x = o[g],
                y = x.prototype,
                m = a(p(y)) == g,
                _ = function (t) {
                    var e,
                        n,
                        r,
                        o,
                        i,
                        c,
                        u,
                        a,
                        f = s(t, !1);
                    if ("string" == typeof f && f.length > 2)
                        if (
                            ((f = b(f)),
                            (e = f.charCodeAt(0)),
                            43 === e || 45 === e)
                        ) {
                            if (((n = f.charCodeAt(2)), 88 === n || 120 === n))
                                return NaN;
                        } else if (48 === e) {
                            switch (f.charCodeAt(1)) {
                                case 66:
                                case 98:
                                    (r = 2), (o = 49);
                                    break;
                                case 79:
                                case 111:
                                    (r = 8), (o = 55);
                                    break;
                                default:
                                    return +f;
                            }
                            for (
                                i = f.slice(2), c = i.length, u = 0;
                                u < c;
                                u++
                            )
                                if (((a = i.charCodeAt(u)), a < 48 || a > o))
                                    return NaN;
                            return parseInt(i, r);
                        }
                    return +f;
                };
            if (i(g, !x(" 0o1") || !x("0b1") || x("+0x1"))) {
                for (
                    var w,
                        S = function (t) {
                            var e = arguments.length < 1 ? 0 : t,
                                n = this;
                            return n instanceof S &&
                                (m
                                    ? l(function () {
                                          y.valueOf.call(n);
                                      })
                                    : a(n) != g)
                                ? f(new x(_(e)), n, S)
                                : _(e);
                        },
                        O = r
                            ? d(x)
                            : "MAX_VALUE,MIN_VALUE,NaN,NEGATIVE_INFINITY,POSITIVE_INFINITY,EPSILON,isFinite,isInteger,isNaN,isSafeInteger,MAX_SAFE_INTEGER,MIN_SAFE_INTEGER,parseFloat,parseInt,isInteger".split(
                                  ","
                              ),
                        j = 0;
                    O.length > j;
                    j++
                )
                    u(x, (w = O[j])) && !u(S, w) && v(S, w, h(x, w));
                (S.prototype = y), (y.constructor = S), c(o, g, S);
            }
        },
        c04e: function (t, e, n) {
            var r = n("861d");
            t.exports = function (t, e) {
                if (!r(t)) return t;
                var n, o;
                if (
                    e &&
                    "function" == typeof (n = t.toString) &&
                    !r((o = n.call(t)))
                )
                    return o;
                if ("function" == typeof (n = t.valueOf) && !r((o = n.call(t))))
                    return o;
                if (
                    !e &&
                    "function" == typeof (n = t.toString) &&
                    !r((o = n.call(t)))
                )
                    return o;
                throw TypeError("Can't convert object to primitive value");
            };
        },
        c430: function (t, e) {
            t.exports = !1;
        },
        c6b6: function (t, e) {
            var n = {}.toString;
            t.exports = function (t) {
                return n.call(t).slice(8, -1);
            };
        },
        c6cd: function (t, e, n) {
            var r = n("da84"),
                o = n("ce4e"),
                i = "__core-js_shared__",
                c = r[i] || o(i, {});
            t.exports = c;
        },
        c8ba: function (t, e) {
            var n;
            n = (function () {
                return this;
            })();
            try {
                n = n || new Function("return this")();
            } catch (r) {
                "object" === typeof window && (n = window);
            }
            t.exports = n;
        },
        ca84: function (t, e, n) {
            var r = n("5135"),
                o = n("fc6a"),
                i = n("4d64").indexOf,
                c = n("d012");
            t.exports = function (t, e) {
                var n,
                    u = o(t),
                    a = 0,
                    f = [];
                for (n in u) !r(c, n) && r(u, n) && f.push(n);
                while (e.length > a)
                    r(u, (n = e[a++])) && (~i(f, n) || f.push(n));
                return f;
            };
        },
        cc12: function (t, e, n) {
            var r = n("da84"),
                o = n("861d"),
                i = r.document,
                c = o(i) && o(i.createElement);
            t.exports = function (t) {
                return c ? i.createElement(t) : {};
            };
        },
        ce4e: function (t, e, n) {
            var r = n("da84"),
                o = n("9112");
            t.exports = function (t, e) {
                try {
                    o(r, t, e);
                } catch (n) {
                    r[t] = e;
                }
                return e;
            };
        },
        d012: function (t, e) {
            t.exports = {};
        },
        d039: function (t, e) {
            t.exports = function (t) {
                try {
                    return !!t();
                } catch (e) {
                    return !0;
                }
            };
        },
        d066: function (t, e, n) {
            var r = n("428f"),
                o = n("da84"),
                i = function (t) {
                    return "function" == typeof t ? t : void 0;
                };
            t.exports = function (t, e) {
                return arguments.length < 2
                    ? i(r[t]) || i(o[t])
                    : (r[t] && r[t][e]) || (o[t] && o[t][e]);
            };
        },
        d1e7: function (t, e, n) {
            "use strict";
            var r = {}.propertyIsEnumerable,
                o = Object.getOwnPropertyDescriptor,
                i = o && !r.call({ 1: 2 }, 1);
            e.f = i
                ? function (t) {
                      var e = o(this, t);
                      return !!e && e.enumerable;
                  }
                : r;
        },
        d2bb: function (t, e, n) {
            var r = n("825a"),
                o = n("3bbe");
            t.exports =
                Object.setPrototypeOf ||
                ("__proto__" in {}
                    ? (function () {
                          var t,
                              e = !1,
                              n = {};
                          try {
                              (t = Object.getOwnPropertyDescriptor(
                                  Object.prototype,
                                  "__proto__"
                              ).set),
                                  t.call(n, []),
                                  (e = n instanceof Array);
                          } catch (i) {}
                          return function (n, i) {
                              return (
                                  r(n),
                                  o(i),
                                  e ? t.call(n, i) : (n.__proto__ = i),
                                  n
                              );
                          };
                      })()
                    : void 0);
        },
        da84: function (t, e, n) {
            (function (e) {
                var n = function (t) {
                    return t && t.Math == Math && t;
                };
                t.exports =
                    n("object" == typeof globalThis && globalThis) ||
                    n("object" == typeof window && window) ||
                    n("object" == typeof self && self) ||
                    n("object" == typeof e && e) ||
                    Function("return this")();
            }.call(this, n("c8ba")));
        },
        df75: function (t, e, n) {
            var r = n("ca84"),
                o = n("7839");
            t.exports =
                Object.keys ||
                function (t) {
                    return r(t, o);
                };
        },
        f772: function (t, e, n) {
            var r = n("5692"),
                o = n("90e3"),
                i = r("keys");
            t.exports = function (t) {
                return i[t] || (i[t] = o(t));
            };
        },
        fb15: function (t, e, n) {
            "use strict";
            if (
                (n.r(e),
                n.d(e, "LightboxDefaultLoader", function () {
                    return b;
                }),
                "undefined" !== typeof window)
            ) {
                var r = window.document.currentScript,
                    o = n("8875");
                (r = o()),
                    "currentScript" in document ||
                        Object.defineProperty(document, "currentScript", {
                            get: o,
                        });
                var i = r && r.src.match(/(.+\/)[^/]+\.js(\?.*)?$/);
                i && (n.p = i[1]);
            }
            var c = function () {
                    var t = this,
                        e = t.$createElement,
                        n = t._self._c || e;
                    return n(
                        "div",
                        [
                            t._t(
                                "preview",
                                [
                                    t.thumbnail
                                        ? n(
                                              "a",
                                              {
                                                  staticClass:
                                                      "lightbox__thumbnail",
                                                  attrs: {
                                                      href: t.images[0],
                                                      target: "_blank",
                                                  },
                                                  on: {
                                                      click: function (e) {
                                                          return (
                                                              e.preventDefault(),
                                                              t.show(e)
                                                          );
                                                      },
                                                  },
                                              },
                                              [
                                                  n("img", {
                                                      attrs: {
                                                          src: t.thumbnail,
                                                          alt: t.alternateText,
                                                      },
                                                  }),
                                              ]
                                          )
                                        : t._e(),
                                ],
                                { show: t.show }
                            ),
                            t.visible
                                ? n(
                                      "div",
                                      {
                                          staticClass: "lightbox",
                                          on: { click: t.hide },
                                      },
                                      [
                                          n(
                                              "div",
                                              {
                                                  staticClass:
                                                      "lightbox__close",
                                                  on: { click: t.hide },
                                              },
                                              [t._t("icon-close", [t._v("×")])],
                                              2
                                          ),
                                          n(
                                              "div",
                                              {
                                                  staticClass:
                                                      "lightbox__element",
                                                  on: {
                                                      click: function (t) {
                                                          t.stopPropagation();
                                                      },
                                                  },
                                              },
                                              [
                                                  n(
                                                      "div",
                                                      {
                                                          staticClass:
                                                              "lightbox__arrow lightbox__arrow--left",
                                                          class: {
                                                              "lightbox__arrow--invisible":
                                                                  !t.hasPrevious,
                                                          },
                                                          on: {
                                                              click: function (
                                                                  e
                                                              ) {
                                                                  return (
                                                                      e.stopPropagation(),
                                                                      e.preventDefault(),
                                                                      t.previous(
                                                                          e
                                                                      )
                                                                  );
                                                              },
                                                          },
                                                      },
                                                      [
                                                          t._t(
                                                              "icon-previous",
                                                              [
                                                                  n(
                                                                      "svg",
                                                                      {
                                                                          attrs: {
                                                                              height: "24",
                                                                              viewBox:
                                                                                  "0 0 24 24",
                                                                              width: "24",
                                                                              xmlns: "http://www.w3.org/2000/svg",
                                                                          },
                                                                      },
                                                                      [
                                                                          n(
                                                                              "path",
                                                                              {
                                                                                  attrs: {
                                                                                      d: "M15.41 16.09l-4.58-4.59 4.58-4.59L14 5.5l-6 6 6 6z",
                                                                                  },
                                                                              }
                                                                          ),
                                                                          n(
                                                                              "path",
                                                                              {
                                                                                  attrs: {
                                                                                      d: "M0-.5h24v24H0z",
                                                                                      fill: "none",
                                                                                  },
                                                                              }
                                                                          ),
                                                                      ]
                                                                  ),
                                                              ]
                                                          ),
                                                      ],
                                                      2
                                                  ),
                                                  n(
                                                      "div",
                                                      {
                                                          staticClass:
                                                              "lightbox__image",
                                                          on: {
                                                              click: function (
                                                                  t
                                                              ) {
                                                                  t.stopPropagation();
                                                              },
                                                          },
                                                      },
                                                      [
                                                          t._t("loader", [
                                                              n(
                                                                  "LightboxDefaultLoader"
                                                              ),
                                                          ]),
                                                          t.displayImage
                                                              ? t._t(
                                                                    "content",
                                                                    [
                                                                        n(
                                                                            "img",
                                                                            {
                                                                                attrs: {
                                                                                    src: t
                                                                                        .images[
                                                                                        t
                                                                                            .index
                                                                                    ],
                                                                                },
                                                                            }
                                                                        ),
                                                                    ],
                                                                    {
                                                                        url: t
                                                                            .images[
                                                                            t
                                                                                .index
                                                                        ],
                                                                    }
                                                                )
                                                              : t._e(),
                                                      ],
                                                      2
                                                  ),
                                                  n(
                                                      "div",
                                                      {
                                                          staticClass:
                                                              "lightbox__arrow lightbox__arrow--right",
                                                          class: {
                                                              "lightbox__arrow--invisible":
                                                                  !t.hasNext,
                                                          },
                                                          on: {
                                                              click: function (
                                                                  e
                                                              ) {
                                                                  return (
                                                                      e.stopPropagation(),
                                                                      e.preventDefault(),
                                                                      t.next(e)
                                                                  );
                                                              },
                                                          },
                                                      },
                                                      [
                                                          t._t("icon-next", [
                                                              n(
                                                                  "svg",
                                                                  {
                                                                      attrs: {
                                                                          height: "24",
                                                                          viewBox:
                                                                              "0 0 24 24",
                                                                          width: "24",
                                                                          xmlns: "http://www.w3.org/2000/svg",
                                                                      },
                                                                  },
                                                                  [
                                                                      n(
                                                                          "path",
                                                                          {
                                                                              attrs: {
                                                                                  d: "M8.59 16.34l4.58-4.59-4.58-4.59L10 5.75l6 6-6 6z",
                                                                              },
                                                                          }
                                                                      ),
                                                                      n(
                                                                          "path",
                                                                          {
                                                                              attrs: {
                                                                                  d: "M0-.25h24v24H0z",
                                                                                  fill: "none",
                                                                              },
                                                                          }
                                                                      ),
                                                                  ]
                                                              ),
                                                          ]),
                                                      ],
                                                      2
                                                  ),
                                              ]
                                          ),
                                      ]
                                  )
                                : t._e(),
                        ],
                        2
                    );
                },
                u = [],
                a = (n("a9e3"), n("8bbf")),
                f = n.n(a),
                s = function () {
                    var t = this,
                        e = t.$createElement;
                    t._self._c;
                    return t._m(0);
                },
                l = [
                    function () {
                        var t = this,
                            e = t.$createElement,
                            n = t._self._c || e;
                        return n(
                            "div",
                            { staticClass: "lightbox__default-loader" },
                            [
                                n("div", {
                                    staticClass:
                                        "lightbox__default-loader__element",
                                }),
                            ]
                        );
                    },
                ],
                p = {},
                d = p;
            n("8d5c");
            function h(t, e, n, r, o, i, c, u) {
                var a,
                    f = "function" === typeof t ? t.options : t;
                if (
                    (e &&
                        ((f.render = e),
                        (f.staticRenderFns = n),
                        (f._compiled = !0)),
                    r && (f.functional = !0),
                    i && (f._scopeId = "data-v-" + i),
                    c
                        ? ((a = function (t) {
                              (t =
                                  t ||
                                  (this.$vnode && this.$vnode.ssrContext) ||
                                  (this.parent &&
                                      this.parent.$vnode &&
                                      this.parent.$vnode.ssrContext)),
                                  t ||
                                      "undefined" ===
                                          typeof __VUE_SSR_CONTEXT__ ||
                                      (t = __VUE_SSR_CONTEXT__),
                                  o && o.call(this, t),
                                  t &&
                                      t._registeredComponents &&
                                      t._registeredComponents.add(c);
                          }),
                          (f._ssrRegister = a))
                        : o &&
                          (a = u
                              ? function () {
                                    o.call(
                                        this,
                                        (f.functional ? this.parent : this)
                                            .$root.$options.shadowRoot
                                    );
                                }
                              : o),
                    a)
                )
                    if (f.functional) {
                        f._injectStyles = a;
                        var s = f.render;
                        f.render = function (t, e) {
                            return a.call(e), s(t, e);
                        };
                    } else {
                        var l = f.beforeCreate;
                        f.beforeCreate = l ? [].concat(l, a) : [a];
                    }
                return { exports: t, options: f };
            }
            var v = h(d, s, l, !1, null, null, null),
                b = v.exports,
                g = {
                    props: {
                        thumbnail: { type: String, default: null },
                        images: { type: Array },
                        openAtIndex: { type: Number, default: 0 },
                        alternateText: { type: String, default: "" },
                        value: { type: Boolean, default: !1 },
                    },
                    components: { LightboxDefaultLoader: b },
                    data: function () {
                        return {
                            visible: this.value,
                            index: this.openAtIndex,
                            displayImage: !0,
                        };
                    },
                    computed: {
                        hasNext: function () {
                            return this.index + 1 < this.images.length;
                        },
                        hasPrevious: function () {
                            return this.index - 1 >= 0;
                        },
                    },
                    watch: {
                        value: function (t) {
                            this.visible = t;
                        },
                        visible: function (t) {
                            this.$emit("input", t);
                        },
                    },
                    methods: {
                        show: function () {
                            (this.visible = !0),
                                (this.index = this.openAtIndex);
                        },
                        hide: function () {
                            (this.visible = !1),
                                (this.index = this.openAtIndex);
                        },
                        previous: function () {
                            this.hasPrevious &&
                                ((this.index -= 1), this.tick());
                        },
                        next: function () {
                            this.hasNext && ((this.index += 1), this.tick());
                        },
                        tick: function () {
                            var t = this;
                            this.$slots.loader &&
                                ((this.displayImage = !1),
                                f.a.nextTick(function () {
                                    t.displayImage = !0;
                                }));
                        },
                        eventListener: function (t) {
                            if (this.visible)
                                switch (t.key) {
                                    case "ArrowRight":
                                        return this.next();
                                    case "ArrowLeft":
                                        return this.previous();
                                    case "ArrowDown":
                                    case "ArrowUp":
                                    case " ":
                                        return t.preventDefault();
                                    case "Escape":
                                        return this.hide();
                                }
                        },
                    },
                    mounted: function () {
                        window.addEventListener("keydown", this.eventListener);
                    },
                    destroyed: function () {
                        window.removeEventListener(
                            "keydown",
                            this.eventListener
                        );
                    },
                },
                x = g,
                y = (n("623a"), h(x, c, u, !1, null, null, null)),
                m = y.exports;
            e["default"] = m;
        },
        fc6a: function (t, e, n) {
            var r = n("44ad"),
                o = n("1d80");
            t.exports = function (t) {
                return r(o(t));
            };
        },
        fe1d: function (t, e, n) {},
    })["default"];
});
//# sourceMappingURL=VuePureLightbox.umd.min.js.map
