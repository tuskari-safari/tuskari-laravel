import "./bootstrap";
import { createApp, h } from "vue";
import { createPinia } from "pinia";
const pinia = createPinia();
import { createInertiaApp, Link, Head, router, usePage } from "@inertiajs/vue3";
import VueApexCharts from "vue3-apexcharts";
import SocialSharing from 'vue-social-sharing';

import PerfectScrollbar from "vue3-perfect-scrollbar";
import "vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css";

import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/index";
import { defineAsyncComponent } from "vue";

import Toaster from "./helpers/Toaster"; //--for 'globally' use
window.toaster = Toaster;

import Service from "./helpers/service"; //--for 'globally' use
window.service = Service;

import SweetAlert from "./helpers/SweetAlert"; //--for 'globally' use
window.sw = SweetAlert;

import emitter from "tiny-emitter/instance";
window.emit = emitter;


const AdminLayout = defineAsyncComponent(() =>
    import("./Layout/Admin/Layout.vue")
);
const FrontendLayout = defineAsyncComponent(() =>
    import("./Layout/Frontend/Layout.vue")
);
const FrontendAuthLayout = defineAsyncComponent(() =>
    import("./Layout/Frontend/Auth/AuthLayout.vue")
);

import { autoAnimatePlugin } from "@formkit/auto-animate/vue";

import { Icon } from "@iconify/vue"; // https://iconify.design/docs/icon-components/vue/

import PrimeVue from 'primevue/config';                  // PrimeIcons
import "primeicons/primeicons.css";
import Aura from '@primeuix/themes/aura';
import { useOnlineUsersStore } from "./Stores/onlineUsers";

router.on("finish", () => {
    if (usePage().props.flash.success) {
        toaster.success(usePage().props.flash.success);
    }

    if (usePage().props.flash.error) {
        toaster.error(usePage().props.flash.error);
    }

    if (usePage().props.flash.warning) {
        toaster.warning(usePage().props.flash.warning);
    }

    if (usePage().props.flash.info) {
        toaster.info(usePage().props.flash.info);
    }
});

router.on("navigate", (event) => {
    const to = event.detail.page.url;
    const safeRoutes = [
        "/checkout",
        "/login",
        "/register",
    ];
    const isBookingPage = to.startsWith("/traveller-safari-details") || to.startsWith("/safari-details");
    const isSafe = isBookingPage || safeRoutes.includes(to);
    if (!isSafe) {
        localStorage.removeItem("safariBookingData");
    }
});

createInertiaApp({
    progress: false,

    title: (title) => {
        const appName = usePage().props.appName || "Your App Name";
        return `${title ? title + ' | ' : ''}${appName}`;
    },

    resolve: async (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: false });
        let page = await pages[`./Pages/${name}.vue`]();

        if (name.startsWith("Admin/")) {
            page.default.layout = AdminLayout;
        } else if (name.startsWith("Frontend/")) {
            if (name.startsWith("Frontend/Auth")) {
                page.default.layout = FrontendAuthLayout;
            }
            else {
                page.default.layout = FrontendLayout;
            }
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });
        vueApp.use(plugin)
            .use(pinia)
            .use(autoAnimatePlugin)
            .use(PerfectScrollbar)
            .use(ZiggyVue, Ziggy)
            .use(SocialSharing)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        darkModeSelector: "light",
                    },
                },
            })
            .component("apexchart", VueApexCharts)
            .component("Link", Link)
            .component("Head", Head)
            .component("Icon", Icon)
            .mount(el);

        const store = useOnlineUsersStore();
        Echo.join("online.users")
            .here((users) => {
                store.setOnlineUsers(users);
            })
            .joining((user) => {
                store.addUser(user);
            })
            .leaving((user) => {
                store.removeUser(user);
                axios.post(route('frontend.last-seen-submit'), { user_id: user.id });
            });

        // 🔹 GLOBAL MESSAGE NOTIFICATION LISTENER
        if (props.initialPage.props.auth?.user) {
            const userId = props.initialPage.props.auth.user.id;
            Echo.private('message-notification.' + userId)
                .listen('MessageSend', (e) => {
                    if (e.user.id == userId) {
                        const audio = new Audio('/receive_message.mp3');
                        audio.currentTime = 0;
                        audio.play().catch(err => console.warn("Audio play blocked:", err));
                    }
                });
        }
    },
});
