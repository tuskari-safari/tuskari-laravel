import { ref } from 'vue';
import axios from 'axios';

const userCurrency = ref(null);
const exchangeRates = ref({});
const isLoading = ref(false);

export const useCurrency = () => {
    const getUserCurrency = async () => {
        if (userCurrency.value) return userCurrency.value;

        try {
            const res = await fetch('https://ipapi.co/json');
            // const res = await fetch('https://api.allorigins.win/raw?url=https://ipapi.co/json/');

            const data = await res.json();
            userCurrency.value = {
                country: data.country_name,
                currency: data.currency,
                code: data.country_code
            };
            return userCurrency.value;
        } catch (error) {
            console.error('Failed to get user currency:', error);
            return { currency: 'USD', country: 'United States', code: 'US' };
        }
    };

    const getExchangeRates = async () => {
        if (Object.keys(exchangeRates.value).length > 0) return exchangeRates.value;

        try {
            const res = await axios.get('/exchange-rates');
            exchangeRates.value = res.data;
            return exchangeRates.value;
        } catch (error) {
            console.error('Failed to get exchange rates:', error);
            return {};
        }
    };

    const formatLocalPrice = (amountUSD, currency = null, rates = null) => {
        if (isLoading.value) {
            return '...';
        }

        try {
            const targetCurrency = currency || userCurrency.value?.currency || 'USD';
            const currentRates = rates || exchangeRates.value;
            const rate = currentRates[targetCurrency] || 1;

            // Convert properly and round up if decimal >= 0.5
            const converted = amountUSD * rate;
            const rounded = converted % 1 >= 0.5 ? Math.ceil(converted) : Math.floor(converted);

            return new Intl.NumberFormat('en', {
                style: 'currency',
                currency: targetCurrency,
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
                useGrouping: true,
            }).format(rounded);
        } catch (error) {
            const roundedFallback = amountUSD % 1 >= 0.5 ? Math.ceil(amountUSD) : Math.floor(amountUSD);

            return new Intl.NumberFormat('en', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
                useGrouping: true,
            }).format(roundedFallback);
        }
    };

    const initializeCurrency = async () => {
        if (isLoading.value) return;

        isLoading.value = true;
        try {
            await Promise.all([getUserCurrency(), getExchangeRates()]);
        } finally {
            isLoading.value = false;
        }
    };

    return {
        userCurrency,
        exchangeRates,
        isLoading,
        getUserCurrency,
        getExchangeRates,
        formatLocalPrice,
        initializeCurrency
    };
};