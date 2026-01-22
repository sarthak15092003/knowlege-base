;(function($){
    'use strict';
    $(document).ready(function(){    
        $('.docy-notice .notice-dismiss').on('click', function(){ 
            $('.docy-notice').slideUp('fast');
        });    
    });
})(jQuery);