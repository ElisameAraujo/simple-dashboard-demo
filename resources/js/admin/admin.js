import { themeChange } from 'theme-change';
import { toggleSubmenu } from './submenu';
import { toggleMenu } from './mobile-menu';

/**
 * Axios HTTP
 */
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * FontAwesome
 */
import "@fortawesome/fontawesome-free/js/all.js";
import "@fortawesome/fontawesome-free/css/all.css";

/**
 * Theme Change
 */
themeChange()

/**
 * Global
 */
toggleSubmenu();
toggleMenu();
