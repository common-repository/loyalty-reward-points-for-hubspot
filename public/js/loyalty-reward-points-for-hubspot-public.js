const customerId = mwb_app_details.mwb_app_customer_id;
const username = mwb_app_details.mwb_app_customer_username;
const customerEmail = mwb_app_details.mwb_app_customer_email;
const appApiKey = mwb_app_details.mwb_app_api_key;
jQuery(document).ready(function () {
  let position = "left";

  jQuery.ajax({
    url:
      "https://apps-home.martechapps.com/pointsandrewardhome/widget/getWidgetSettings?mwb_app_store_url=" +
      mwb_app_details.mwb_app_store_url,
    beforeSend: function(xhr){xhr.setRequestHeader('MWB-APP-API-KEY', appApiKey);},
    type: "GET",
    success: function (response) {
      var $iframe = jQuery("#bot-iframe");
      if (response.success == true) {
        position = response.data.widget_position;
        if (position === "left") {
          $iframe.parent().css("left", "0");
        } else {
          $iframe.parent().css("right", "0");
        }
      } else {
        $iframe.parent().css("left", "0");
      }
    },
  });

  window.addEventListener(
    "message",
    function (e) {
      var $iframe = jQuery("#bot-iframe");
      switch (e.data.type) {
        case "toggle":
          if (e.data.widgetToggle) {
            $iframe.parent().css("height", "550px");
            $iframe.parent().css("width", "400px");
            if (e.data.position === "left") {
              $iframe.parent().css("left", "0");
            } else {
              $iframe.parent().css("right", "0");
            }
          } else if (!jQuery(".par-ecom__container").hasClass("widget--open")) {
            setTimeout(() => {
              $iframe.parent().css("height", "90px");
              $iframe.parent().css("width", "90px");
            }, 150);
          }
          break;
        case "login":
          window.location.href = e.data.url;
        case "register":
          window.location.href = e.data.url;
          break;
        default:
          break;
      }
    },
    false
  );
});
