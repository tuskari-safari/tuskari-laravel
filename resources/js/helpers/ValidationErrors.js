import { computed } from 'vue'

export default function useValidationErrors(form) {
    const hasSafariOverviewErrors = computed(() => {
        return !!(
            form.errors.description ||
            // form.errors.safariIncluded ||
            // form.errors.safariNotIncluded ||
            // form.errors.addOns ||
            form.errors.special_description
        )
    });

    // const hasAnyAnimalErrors = computed(() => {
    //     return form.animals.some((_, index) =>
    //         Object.keys(form.errors).some(key =>
    //             key.startsWith(`animals.${index}`) && !!form.errors[key]
    //         )
    //     )
    // });

    const hasDayByDayErrors = computed(() => {
        return form.days.some((_, index) => {
            const generalErrors = !!(
                form.errors[`days.${index}.heading`] ||
                form.errors[`days.${index}.subtext`] ||
                form.errors[`days.${index}.meals`] ||
                form.errors[`days.${index}.accommodation`] ||
                form.errors[`days.${index}.highlights`] ||
                form.errors[`days.${index}.wildlife_location`] ||
                form.errors[`days.${index}.today_highlights`]
            );
            const imageErrors = Object.keys(form.errors).some(key =>
                key.startsWith(`days.${index}.image.`) && key.endsWith('.file')
            );

            return generalErrors || imageErrors;
        });
    });

    const hasActivityErrors = computed(() => {
        return form.errors.activities;
    });

    const hasLandscapeErrors = computed(() => {
        return form.errors.environment;
    });

    const hasActivityLevelErrors = computed(() => {
        return form.errors.activityLevel;
    });

    const hasIncludedNotIncludedErrors = computed(() => {
        return !!(
            form.errors.safariIncluded ||
            form.errors.safariNotIncluded
        )
    })
    const hasTravellerGroupErrors = computed(() => {
        return !!(
            form.errors.numberOfAdults ||
            form.errors.numberOfChildren ||
            form.errors.numberOfGroups ||
            form.errors.groupType
        )
    });

    const hasAvailabilityErrors = computed(() => {
        return !!(
            form.errors.perDateGroupLimit ||
            form.errors.availabilityTag ||
            form.errors.totalSlots ||
            form.dateRange?.some((_, index) =>
                form.errors[`dateRange.${index}.availableDates`] ||
                form.errors[`dateRange.${index}.blockedDates`] ||
                form.errors[`dateRange.${index}.lowSeasonAvailableDates`] ||
                form.errors[`dateRange.${index}.lowSeasonBlockedDates`] ||
                form.errors[`dateRange.${index}.highSeasonAvailableDates`] ||
                form.errors[`dateRange.${index}.highSeasonBlockedDates`] ||
                form.errors[`dateRange.${index}.lowSeasonAdultPrice`] ||
                form.errors[`dateRange.${index}.highSeasonAdultPrice`] ||
                form.errors[`dateRange.${index}.lowSeasonChildPrice`] ||
                form.errors[`dateRange.${index}.highSeasonChildPrice`]
            )
        )
    });


    const hasPricingErrors = computed(() => {
        return !!(
            form.errors.lowSeasonDateRange ||
            form.errors.highSeasonDateRange ||
            form.errors.lowSeasonAdultPrice ||
            form.errors.highSeasonAdultPrice ||
            form.errors.lowSeasonChildPrice ||
            form.errors.highSeasonChildPrice ||

            form.priceBands.some((_, index) =>
                form.errors[`priceBands.${index}.min`] ||
                form.errors[`priceBands.${index}.max`] ||
                form.errors[`priceBands.${index}.pp`]
            ) ||

            form.discounts.some((_, index) =>
                form.errors[`discounts.${index}.person`] ||
                form.errors[`discounts.${index}.count`] ||
                form.errors[`discounts.${index}.discountType`] ||
                form.errors[`discounts.${index}.discount`]
            )
        )
    });

    const hasMapLocationErrors = computed(() => {
        return !!(
            form.errors.location_type ||
            form.errors.national_park_id ||
            form.errors.location
        )
    });

    const hasGalleryErrors = computed(() => {
        return Object.entries(form.errors)
            .filter(([key]) => key.startsWith('gallery_images'))
            .map(([, value]) => value)
            .length > 0;
    });

    const hasThingsToKnowErrors = computed(() => {
        return form.thingsToKnows.some((_, index) =>
            form.errors[`thingsToKnows.${index}.title`] ||
            form.errors[`thingsToKnows.${index}.description`]
        )
    });

    const hasOperatorInfoErrors = computed(() => {
        return !!(
            form.errors.operator_name ||
            form.errors.operator_description ||
            form.errors.operator_team_size ||
            form.errors.operator_type ||
            form.errors.operator_image ||
            form.errors.why_choose_us
        )
    });

    return {
        hasSafariOverviewErrors,
        // hasAnyAnimalErrors,
        hasDayByDayErrors,
        hasActivityErrors,
        hasLandscapeErrors,
        hasActivityLevelErrors,
        hasTravellerGroupErrors,
        hasAvailabilityErrors,
        hasPricingErrors,
        hasMapLocationErrors,
        hasGalleryErrors,
        hasThingsToKnowErrors,
        hasOperatorInfoErrors,
        hasIncludedNotIncludedErrors
    }
}
