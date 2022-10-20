require('./bootstrap');
require('bootstrap');
require('./theme/looper/javascript/theme');
import { createApp, defineAsyncComponent, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import VioPlugin from 'vio/plugin/VioPlugin';

const LayoutDefault = defineAsyncComponent(() => import('@/Layouts/LayoutDefault'));

createInertiaApp({
    resolve: name => import(`./Pages/${name}`)
        .then(({ default: page }) => {
            if (page.layout === undefined) {
                page.layout = LayoutDefault;
            }
            return page;
        }),
    setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(VioPlugin)
            .component('InertiaHead', Head)
            .component('InertiaLink', Link)
            .mount(el);

        InertiaProgress.init();
    },
});
