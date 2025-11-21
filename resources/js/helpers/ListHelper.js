import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import moment from 'moment';
moment.calendar = {
    lastDay: '[Yesterday at] LT',
    sameDay: '[Today at] LT',
    nextDay: '[Tomorrow at] LT',
    lastWeek: '[last] dddd [at] LT',
    nextWeek: 'dddd [at] LT',
    sameElse: 'L'
};

const shortBy = ref(false);
class ListHelper {
    sortBy(column) {
        shortBy.value = !shortBy.value;
        let shortByy = shortBy.value ? 'asc' : 'desc';
        router.reload({
            method: 'get',
            data: { fieldName: column, shortBy: shortByy },
            replace: true,
        });
    }

    setPerPage(perPage) {
        router.reload({
            method: 'get',
            data: { perPage: perPage, page: 1 },
            replace: false,
        });
    }

    setPageNum(page) {
        router.reload({
            method: 'get',
            data: { page: page },
            replace: true,
        });
    }

    getRandomVal = () => {
        let colors = ["success", "info", "warning", "dark", "primary"];
        let cla = 'kt-badge--' + colors[Math.floor(Math.random() * colors.length)];
        let obj = { [cla]: true }
        return obj;
    }

    dateFormat(date, format) {
        return moment(date).format(format)
    }

    calendarFormat(date) {
        return moment(date).calendar()
    }

    fancyDateFormat(dateString) {
        const date = new Date(dateString);
        const today = new Date();

        if (date.getDate() === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear()) {
            return "Today";
        }

        const day = date.getDate();
        const daySuffix = day % 10 === 1 && day !== 11 ? "st" : day % 10 === 2 && day !== 12 ? "nd" : day % 10 === 3 && day !== 13 ? "rd" : "th";
        const month = date.toLocaleString("default", { month: "long" });
        const year = date.getFullYear();

        return `${day}${daySuffix} ${month} ${year}`;
    }

    fancyDateFormatShort(dateString) {
        const date = new Date(dateString);
        const today = new Date();

        if (date.getDate() === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear()) {
            return "Today";
        }

        const day = date.getDate();
        const suffix = day % 10 === 1 && day !== 11 ? "st" : day % 10 === 2 && day !== 12 ? "nd" : day % 10 === 3 && day !== 13 ? "rd" : "th";
        const month = date.toLocaleString("default", { month: "short" });
        const year = date.getFullYear();

        return `${day}${suffix} ${month} ${year}`;
    }
}

export default ListHelper = new ListHelper()
