(() => {
  var t;
  (t = jQuery)(document).ready(function () {
    t(document).on("click", ".h5vp_front_shortcode input", function (e) {
      e.preventDefault();
      let o = t(this).parent().find("input")[0];
      o.select(),
        o.setSelectionRange(0, 30),
        document.execCommand("copy"),
        t(this).parent().find(".htooltip").text("Copied Successfully!");
    }),
      t(document).on("mouseout", ".h5vp_front_shortcode input", function () {
        t(this).parent().find(".htooltip").text("Copy To Clipboard");
      });
  });
})();
