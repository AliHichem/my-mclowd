$(function(){
  $('#tag-line-input').keyup(function(){
    if ($(this).val() == '') { //Check to see if there is any text entered
      //If there is no text within the input then disable the button
      $('#tag-line-submit').prop('disabled', true);
    } else {
      //If there is text in the input, then enable the button
      $('#tag-line-submit').prop('disabled', false);
    }
  });
});

$(".account-type-radio").click(function() {
  $("#account-type-submit").removeAttr("disabled");
});

$('.service-checkbox').change(function() {
  if ($('.service-checkbox:checked').length) {
    $('#service-offered-submit').removeAttr('disabled');
  } else {
    $('#service-offered-submit').attr('disabled', 'disabled');
  }
});

$('.price-checkbox').change(function() {
  if ($('.price-checkbox:checked').length) {
    $('#pricing-submit').removeAttr('disabled');
  } else {
    $('#pricing-submit').attr('disabled', 'disabled');
  }
});

var $unique = $('input.price-checkbox');
$unique.click(function() {
    $unique.filter(':checked').not(this).removeAttr('checked');
});      

$('#minimum-per-hour-checkbox').change(function() {
  if (this.checked){
    $('.minimum-per-hour-input').prop('disabled', false);
  } else {
    $('.minimum-per-hour-input').prop('disabled', true);
  }
});

$('#task-pricing-checkbox').change(function() {
  if (this.checked){
    $('.task-input').prop('disabled', false);
    $('.task-price-input').prop('disabled', false);
  } else {
    $('.task-input').prop('disabled', true);
    $('.task-price-input').prop('disabled', true);
  }
});

$(function(){
  $('#location-input').keyup(function(){
    if ($(this).val() == '') { //Check to see if there is any text entered
      //If there is no text within the input then disable the button
      $('#location-submit').prop('disabled', true);
    } else {
      //If there is text in the input, then enable the button
      $('#location-submit').prop('disabled', false);
    }
  });
});

$(function(){
  $('.overview-input').keyup(function(){
    if ($(this).val() == '') { //Check to see if there is any text entered
      //If there is no text within the input then disable the button
      $('#overview-submit').prop('disabled', true);
    } else {
      //If there is text in the input, then enable the button
      $('#overview-submit').prop('disabled', false);
    }
  });
});

$(function(){
  $('#skill-input-1').keyup(function(){
    if ($(this).val() == '') { //Check to see if there is any text entered
      //If there is no text within the input then disable the button
      $('#skill-description-input-1').prop('disabled', true);
      $('#skill-submit').prop('disabled', true);
    } else {
      //If there is text in the input, then enable the button
      $('#skill-description-input-1').prop('disabled', false);
      $('#skill-submit').prop('disabled', false);
    }
  });
});

$(function(){
  $('#education-input-1').keyup(function(){
    if ($(this).val() == '') { //Check to see if there is any text entered
      //If there is no text within the input then disable the button
      $('#education-description-input-1').prop('disabled', true);
      $('#dpd1').prop('disabled', true);
      $('#dpd2').prop('disabled', true);
      $('#education-submit').prop('disabled', true);
    } else {
      //If there is text in the input, then enable the button
      $('#education-description-input-1').prop('disabled', false);
      $('#dpd1').prop('disabled', false);
      $('#dpd2').prop('disabled', false);
      $('#education-submit').prop('disabled', false);
    }
  });
});

$.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}

$(function(){
  $('#license-name-input-1').keyup(function(){
    if ($(this).val() == '') { //Check to see if there is any text entered
      //If there is no text within the input then disable the button
      $('#license-location-input-1').prop('disabled', true);
      $('#license-received-date').prop('disabled', true);
      $('#license-submit').prop('disabled', true);
    } else {
      //If there is text in the input, then enable the button
      $('#license-location-input-1').prop('disabled', false);
      $('#license-received-date').prop('disabled', false);
      $('#license-submit').prop('disabled', false);
    }
  });
});

$(function(){
  $('#company-name-input-1').keyup(function(){
    if ($(this).val().length == 0) { //Check to see if there is any text entered
      //If there is no text within the input then disable the button
      $('#company-location-input-1').prop('disabled', true);
      $('#job-title-1').prop('disabled', true);
      $('#job-start-1').prop('disabled', true);
      $('#job-end-1').prop('disabled', true);
      $('#job-role-description-input-1').prop('disabled', true);
      $('#employment-submit').prop('disabled', true);
    } else {
      //If there is text in the input, then enable the button
      $('#company-location-input-1').prop('disabled', false);
      $('#job-title-1').prop('disabled', false);
      $('#job-start-1').prop('disabled', false);
      $('#job-end-1').prop('disabled', false);
      $('#job-role-description-input-1').prop('disabled', false);
      $('#employment-submit').prop('disabled', false);
    }
  });
});