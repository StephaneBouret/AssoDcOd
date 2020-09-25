$(document).ready(function(){

    $(document).on('mouseleave','#left-panel',function(e){
        $('.inner-tabs-navigation').attr('data-active', 'menu');
    });

   // Display panel with css
   $(document).on('click', '#left-panel .onglets', function() {
       $(this).parent().attr('data-active', $(this).attr('data-onglet'));
   });

   // Replacement of radio button for graphic upgrade
   $(document).on('click', '#left-panel .my-param-content [data-click="radio-replace"]', function(e){
      var item = '#left-panel '+$(this).attr('data-input');
      $(item).prop( "checked", true );
   });

  $(document).on('click',"#left-panel .black-curtain", function(e){
    if( $.browser.msie && $.browser.majorVersion === 9 ){
          toastr.info($('.oldBrowser-alert').attr('data-message'),$('.oldBrowser-alert').attr('data-title'));
    } else {
      $('input[type="file"][name="avatar"]').click();
    }
  });

   $(document).on('change', '#left-panel input[type="file"][name="avatar"]',function(e){
    var img_name = $(this).val().replace(/\\/g,"\/" ).split("/").filter(function(e){return e.indexOf(".") > -1}).toString();
    var type = img_name.split('.');
    type = type[type.length - 1];
    var authorized = ["png", "jpg", "jpeg", "gif"];
    var file = this.files[0];
    if (file && file.size !== undefined) {
        if (authorized.indexOf(type) > -1 && file.size <= 2 * 1024 * 1024) {
            $("#left-panel .black-curtain span").text(img_name);
            $("#left-panel .black-curtain").css('background-color', 'rgba(0,0,0,0.5)');
            readURL(this, function (e) {
                $('#left-panel .hide-on-menu').css('background-image', 'url(' + e.target.result + ')');
            });
        }
    }
   });

   // Detect change on input by keyup
   $('#left-panel .my-param-content input').keyup(function(e){
      var who = $(e.target).attr('data-name');
      var rules = $(e.target).attr('data-rule');
      var label = $('#left-panel .my-param-content label[for="'+who+'"]');
      if( !$(this).ruleChecker() ){
         label.addClass('false');
      }else{
         label.removeClass('false');
      }
      label.addClass('changed');
   });

   // Detect change of replacement element
   $(document).on('click', '#left-panel .my-param-content [data-input]', function(e){
      var target = $(this).attr('data-input');
      var who = $(target).attr('data-name');
      var label = $('#left-panel .my-param-content label[for="'+who+'"]');
      label.addClass('changed');
   });

   // Detect change on select, radio and checkboxes
   $(document).on('change', '#left-panel .my-param-content select,input:radio,input[type="checkbox"]', function(e){
      var who = $(e.target).attr('data-name');
      var label = $('#left-panel .my-param-content label[for="'+who+'"]');
      label.addClass('changed');
   });

   // Validate form before submitting it
    $('#left-panel form .save-and-quit').click(function(e){
        $('#left-panel form').find('input,textarea,select').each(function(e){
            if( !$(this).ruleChecker() ){
                return false;
            }
        });
        $('#left-panel form').submit();
    });
});
