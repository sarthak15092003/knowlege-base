<?php
if ( docy_opt('is_dark_switcher') == '1' ) :
    ?>
    <div class="px-2 darkmode-btn" title="<?php esc_attr_e( 'Toggle dark mode', 'docy' ); ?>">
        <label for="something" class="tab-btn tab-btns">
            <i class="fa fa-moon-o"></i>
        </label>
        <label for="something" class="tab-btn">
            <i class="fa fa-sun-o"></i>
        </label>
        <label id="ball" class="ball" for="something"></label>
        <input type="checkbox" name="something" id="something" class="dark_mode_switcher something">
    </div>
    <?php
endif;