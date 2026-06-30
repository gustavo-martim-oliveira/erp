import axios from 'axios';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import flasher from '@flasher/flasher';

Alpine.plugin(persist);

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Alpine = Alpine;
Alpine.start();

window.flasher = flasher;