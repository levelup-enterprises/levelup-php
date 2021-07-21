// Title: Form Validation (ie edition)
// Desc: Handles validation for form data
// Author: Tommy Wheeler
// Version: 1.4
// Created: 8/16/19

/**
 *HOW IT WORKS
 *The validator is called during form submission and returns a value of FALSE if there are any non-valid entries.
 *First - the validator takes the provided form ID and checks if all required inputs are not empty, if they are empty it updates their placeholders.
 *Second - the validator checks if unique inputs are valid (phone, email, radio, signature).
 *             To validate without requiring, only add unique data attribute (data-email).
 *Third - if all pass then a value of TRUE is returned.
 *
 * SETUP
 * Add dataText (data-required) to all empty fields that require validation.
 * For each type that requires unique validation add an additional data field (data-email, data-phone...).
 * Create a custom css tag for styling non-valid inputs (.not-valid is the default tag)
 *
 * RADIO
 * Radio requires a wrapper (.required-radio is the default tag) be placed around the entire group that needs to be validated.
 *
 * SIGNATURE
 * Signature requires a wrapper to be placed around the capture element.
 *
 * MODIFICATIONS
 * The validator is designed to be easily modified for different applications
 *
 * @param {Validator} form
 */
// Form ID is passed to validator
function formValidate() {
  var formID =
    arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
  // Apply to specific form
  formID ? (id = "#" + formID + " ") : (id = ""); // Input data attributes defaults

  var dataText = id + "*[validate-required]",
    // For required inputs
    dataPhone = id + "*[validate-phone]",
    // For required phone inputs
    dataRadio = id + "*[validate-radio]",
    // For required radio inputs
    dataCheckbox = id + "*[validate-checkbox]",
    // For required checkbox input
    dataEmail = id + "*[validate-email]",
    // For required email inputs
    dataSign = id + "*[validate-signature]",
    // For required signature inputs
    // Identifier classes and wrappers
    style = "not-valid",
    // Custom class for form errors
    radioWrapper = $(".required-radio"),
    // Radio group wrapper
    // Error display
    requiredText = " required",
    requiredPhone = "Valid phone number required",
    requiredEmail = "Valid email address required",
    requiredSign = "Valid signature required",
    // Regex
    phoneRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im,
    emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/; // Clear existing validation style

  $("." + style).removeClass(style); // Clear existing alerts

  clearAlerts();

  function validatePhone() {
    var phone = $(dataPhone);

    if (phone.length > 0) {
      if (phone.attr("data-required") !== undefined || phone.val() !== "") {
        if (phoneRegex.test(phone.val()) === false) {
          phone.val(""),
            addAlert(phone.attr("id"), requiredPhone),
            phone.focus();
          return false;
        }

        return true;
      }
    }
  }

  function validateEmail() {
    var email = $(dataEmail);

    if (email.length > 0) {
      if (email.attr("data-required") !== undefined || email.val() !== "") {
        if (emailRegex.test(email.val()) === false) {
          email.val(""),
            addAlert(email.attr("id"), requiredEmail),
            email.focus();
          return false;
        }

        return true;
      }
    }
  }

  function validateRadio() {
    var radio = $(dataRadio);

    if (radio.length > 0) {
      var group = radio.attr("name"),
        checked = $("input:radio[name=" + group + "]:checked").val();

      if (checked === undefined) {
        addAlert(radio.attr("name")), radioWrapper.focus();
        return false;
      }

      return true;
    }
  }

  function validateCheckbox() {
    var box = $(dataCheckbox);

    if (box.length > 0) {
      var group = box.attr("name"),
        checked = $("input:checkbox[name=" + group + "]:checked").val();

      if (checked === undefined) {
        console.log(box);
        addAlert(box.attr("name")), box.focus();
        return false;
      }

      return true;
    }
  }

  function validateSign() {
    var sign = $(dataSign);

    if (sign.length > 0) {
      if (sign.val() === "") {
        addAlert(sign.attr("id"), requiredSign), sign.focus();
        return false;
      }

      return true;
    }
  }

  function clearAlerts() {
    try {
      $(".validator-holder").remove();
    } catch (e) {
      console.log(e);
    }
  }

  function addAlert(field) {
    var message =
      arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null; // Check if jquery of js

    typeof field === "string" ? (field = field) : (field = field.id); // Get label and append validator holder

    try {
      var label = $("label[for='" + field + "']");
      message === null && (message = requiredText);
      label.after(
        "<span class='validator-holder " +
          style +
          "'>&nbsp; " +
          message +
          "</span>"
      );
    } catch (e) {
      console.log(e);
    }
  }

  function ieCheck() {
    var msie = window.navigator.userAgent.indexOf("MSIE") || false,
      trident = window.navigator.userAgent.indexOf("Trident") || false,
      isIE = false;
    msie > 0 && (isIE = true);
    trident > 0 && (isIE = true);
    return isIE;
  }

  var fields = $(dataText); //  Validate all data-required fields

  try {
    var validate = function validate(fields) {
      var valid = {
          empty: true,
          email: true,
          phone: true,
          radio: true,
          box: true,
          sign: true,
        },
        focus = [];

      for (var i = 0; i < fields.length; i++) {
        fields[i].value === "" &&
          (focus.push(fields[i]), addAlert(fields[i]), fields[i].focus());
      }

      focus.length !== 0 && (focus[0].focus(), (valid.empty = false));
      valid.empty && (valid.phone = validatePhone());
      valid.empty && (valid.email = validateEmail());
      valid.empty && (valid.radio = validateRadio());
      valid.empty && (valid.box = validateCheckbox());
      valid.empty && (valid.sign = validateSign());
      return valid;
    };
  } catch (e) {
    console.log(e);
  } //  Check for any false values in 'valid' array

  try {
    // For ie support
    var obj = validate(fields);

    if (
      Object.keys(obj)
        .map(function (e) {
          return obj[e];
        })
        .indexOf(false) < 0
    ) {
      return true;
    } else {
      return false;
    }
  } catch (e) {
    console.log(e);
  }
}
