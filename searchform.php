<form class="search-form" method="get" action="<?php echo home_url(); ?>">
    <input type="search"
           id="ajax-search"
           placeholder="<?php _e('ابحث عن منتجك', 'saint'); ?>"
           name="s"
           autocomplete="off"
           required>
    <button type="submit" class="search-submit" aria-label="بحث">
        <i class="fas fa-search"></i>
    </button>
    <ul id="search-results" class="results"></ul>
</form>
