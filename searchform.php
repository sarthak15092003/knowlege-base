<?php
add_filter('get_search_form', function($form) {
    $value   = get_search_query() ? get_search_query() : '';
    $action  = esc_url(home_url('/'));
    $placeholder = esc_attr__('Search or ask...', 'docy');

    $form  = '<form role="search" method="get" action="' . $action . '" class="stylish-search">';
    $form .= '  <div class="stylish-search__shell">';
    $form .= '    <div class="stylish-search__body">';
    $form .= '      <span class="stylish-search__sparkle" aria-hidden="true">';
    $form .= '        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">';
    $form .= '          <defs><linearGradient id="searchSparkleGradient" x1="0" y1="0" x2="18" y2="18" gradientUnits="userSpaceOnUse"><stop stop-color="#4F8CFF"/><stop offset="1" stop-color="#3DDC97"/></linearGradient></defs>';
    $form .= '          <path d="M9.999 2.5l1.3 3.7 3.7 1.3-3.7 1.3-1.3 3.7-1.3-3.7-3.7-1.3 3.7-1.3 1.3-3.7z" fill="url(#searchSparkleGradient)"/>';
    $form .= '        </svg>';
    $form .= '      </span>';
    $form .= '      <input type="search" name="s" class="stylish-search__input" value="' . esc_attr($value) . '" placeholder="' . $placeholder . '" />';
    $form .= '    </div>';
    $form .= '    <button type="submit" class="stylish-search__submit" aria-label="' . esc_attr__('Search', 'docy') . '">';
    $form .= '      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">';
    $form .= '        <path d="M14.707 13.293a1 1 0 0 1 1.32-.083l.094.083 2.5 2.5a1 1 0 0 1-1.32 1.497l-.094-.083-2.5-2.5a1 1 0 0 1 0-1.414z" fill="currentColor"/>';
    $form .= '        <path d="M9 2a7 7 0 1 1 0 14A7 7 0 0 1 9 2zm0 2a5 5 0 1 0 0 10A5 5 0 0 0 9 4z" fill="currentColor"/>';
    $form .= '      </svg>';
    $form .= '    </button>';
    $form .= '  </div>';
    $form .= '</form>';

    return $form;
});