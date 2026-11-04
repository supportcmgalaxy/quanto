<?php
/**
 * @Packge     : Quanto
 * @Version    : 1.0
 * @Author     : Mirrortheme
 * @Author URI : https://mirrortheme.com/
 *
 */

    // Block direct access
    if( !defined( 'ABSPATH' ) ){
        exit();
    }
?>
<style>
.cmr-navbar-search-form {
    display: flex;
    align-items: center;
    border: 1px solid #6241ca; /* Gradient-like border in design, using solid purple for now */
    border-radius: 50px;
    padding: 6px;
    background: #fff;
    width: 100%;
    max-width: 800px;
    box-shadow: 0 4px 20px rgba(124, 58, 237, 0.05);
}
.cmr-navbar-search-form .cat-icon {
    padding-left: 20px;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    font-size: 15px;
    white-space: nowrap;
}
.cmr-navbar-search-form .cat-icon img, 
.cmr-navbar-search-form .cat-icon i {
    width: 20px;
    height: 20px;
    color: #6241ca;
}
.cmr-navbar-search-form input {
    border: none;
    outline: none;
    flex-grow: 1;
    padding: 10px 20px;
    font-size: 16px;
    background: transparent;
    box-shadow: none;
    color: #333;
}
.cmr-navbar-search-form input:focus {
    box-shadow: none;
    outline: none;
}
.cmr-navbar-search-form .clear-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    font-size: 18px;
    cursor: pointer;
    padding: 0 10px;
    display: flex;
    align-items: center;
}
.cmr-navbar-search-form .results-badge {
    background: #ede9fe;
    color: #6241ca;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
    margin: 0 10px;
    white-space: nowrap;
}
.cmr-navbar-search-form .submit-btn {
    background: #6241ca;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.3s ease;
}
.cmr-navbar-search-form .submit-btn:hover {
    background: #6241ca;
    transform: scale(1.05);
}
</style>

<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="cmr-navbar-search-form">
    <div class="cat-icon">
        <!-- Logo SVG -->
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#6241ca" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 17L12 22L22 17" stroke="#6241ca" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 12L12 17L22 12" stroke="#6241ca" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Vehicle</span>
    </div>
    <input name="s" required value="<?php echo esc_html( get_search_query() ); ?>" type="search" placeholder="Type here...">
    
    <button type="button" class="clear-btn" aria-label="<?php esc_attr_e( 'Clear Search Input', 'quanto' ); ?>" onclick="this.previousElementSibling.value=''"><i class="ri-close-line"></i></button>
    <div class="results-badge">17 Results</div>
    <button type="submit" class="submit-btn" aria-label="<?php esc_attr_e( 'Submit Search', 'quanto' ); ?>"><i class="ri-search-line"></i></button>
</form>
