<template>
    <div class="safari-details-container">
        <div class="safari-content">
            <!-- Header Actions -->
            <div class="header-actions sticky-header">
                <Link :href="route('admin.safari.index')" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back
                </Link>
            </div>

            <!-- Hero Section -->
            <div class="hero-card">
                <div class="hero-header" @click="toggle('basic')">
                    <div class="hero-title-section">
                        <div class="hero-accordion-toggle">
                            <i :class="basicToggle ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="hero-toggle-icon"></i>
                        </div>
                        <div class="title-content">
                            <h1 class="safari-title">{{ safari?.title ?? "NA" }}</h1>
                            <div class="safari-meta">
                                <span class="operator-badge">
                                    <i class="fas fa-user-tie"></i>
                                    By {{ safari?.user?.full_name ?? "NA" }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="basicToggle" class="hero-content">
                    <div class="hero-main-content">
                        <div class="hero-left" v-if="safari?.full_thumbnail_url">
                            <div class="hero-thumbnail">
                                <img :src="safari.full_thumbnail_url" alt="Safari thumbnail"
                                    class="hero-thumbnail-image" />
                            </div>
                        </div>
                        <div class="hero-right">
                            <div class="description-section">
                                <div class="description-text" v-html="safari?.description ?? 'NA'"></div>
                            </div>
                        </div>
                    </div>
                    <div class="special-trip-section" v-if="safari?.short_description">
                        <h4 class="special-trip-title"><i class="fas fa-star"></i>What makes this trip special:</h4>
                        <div class="special-trip-content" v-html="safari?.short_description"></div>
                    </div>
                    <!-- Map Section -->
                    <div v-if="safari?.location && safari?.lat && safari?.long" class="map-section">
                        <div class="map-container">
                            <MapBoxView :location="location" />
                        </div>
                    </div>
                    <div v-else class="no-location-container">
                        <div class="no-location-content">
                            <i class="fas fa-map-marked-alt"></i>
                            <p>No location data available</p>
                        </div>
                    </div>
                    <!-- Safari Details Grid -->
                    <div class="safari-details-grid">
                        <!-- Duration & Location Card -->
                        <div class="detail-card">
                            <div class="detail-header">
                                <i class="fas fa-calendar-alt"></i>
                                <h4>Duration & Location</h4>
                            </div>
                            <div class="detail-content">
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-clock"></i> Duration</span>
                                    <span class="value">{{ safari?.day_duration ?? 'NA' }} days, {{
                                        safari?.night_duration }} nights</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-flag"></i> Country</span>
                                    <span class="value">{{ safari?.country?.name ?? "NA" }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-globe-africa"></i> Region</span>
                                    <span class="value">{{ safari?.country?.region?.name ?? "NA" }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-map-marker-alt"></i> Location</span>
                                    <span class="value">{{ safari?.location ?? "NA" }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Seasons Card -->
                        <div class="detail-card pricing-seasons-card">
                            <div class="detail-header">
                                <i class="fas fa-dollar-sign"></i>
                                <h4>Pricing & Seasons</h4>
                            </div>
                            <div class="detail-content">
                                <div class="seasons-pricing-grid">
                                    <div class="season-pricing-item low-season">
                                        <div class="season-header">
                                            <i class="fas fa-leaf"></i>
                                            <span>Low Season</span>
                                        </div>
                                        <div class="date-range">
                                            <span class="date-display"> {{
                                                formatDateWithMonth(safari?.low_season_start_date) }}</span>
                                            <i class="fas fa-arrow-right"></i>
                                            <span class="date-display">{{
                                                formatDateWithMonth(safari?.low_season_end_date) }}</span>
                                        </div>
                                        <div class="pricing-row">
                                            <div class="price-detail">
                                                <span class="price-type">Adult</span>
                                                <span class="price-amount highlighted-price">${{
                                                    safari?.low_season_adult_price ?? "NA" }}</span>
                                            </div>
                                            <div class="price-detail">
                                                <span class="price-type">Child</span>
                                                <span class="price-amount highlighted-price">${{
                                                    safari?.low_season_child_price ?? "NA" }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="season-pricing-item high-season">
                                        <div class="season-header">
                                            <i class="fas fa-sun"></i>
                                            <span>High Season</span>
                                        </div>
                                        <div class="date-range">
                                            <span class="date-display">{{
                                                formatDateWithMonth(safari?.high_season_start_date) }}</span>
                                            <i class="fas fa-arrow-right"></i>
                                            <span class="date-display">{{
                                                formatDateWithMonth(safari?.high_season_end_date) }}</span>
                                        </div>
                                        <div class="pricing-row">
                                            <div class="price-detail">
                                                <span class="price-type">Adult</span>
                                                <span class="price-amount highlighted-price">${{
                                                    safari?.high_season_adult_price ?? "NA" }}</span>
                                            </div>
                                            <div class="price-detail">
                                                <span class="price-type">Child</span>
                                                <span class="price-amount highlighted-price">${{
                                                    safari?.high_season_child_price ?? "NA" }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activities, Environment & Types Card -->
                        <div class="detail-card activities-card full-width-card">
                            <div class="detail-header">
                                <i class="fas fa-seedling"></i>
                                <h4>Activities, Environment & Types</h4>
                            </div>
                            <div class="detail-content">
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-binoculars"></i> Activities</span>
                                    <span class="value comma-separated">
                                        {{safari?.activity?.map(activity => activity.title).join(', ') || 'NA'}}
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-leaf"></i> Environment</span>
                                    <span class="value comma-separated">
                                        {{ safari?.environment ? JSON.parse(safari.environment).join(', ') : 'NA' }}
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-tags"></i> Safari Types</span>
                                    <span class="value comma-separated">
                                        {{safari?.create_safari_type?.map(type => type.type?.title).join(', ') || 'NA'
                                        }}
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-tachometer-alt"></i> Activity Level</span>
                                    <span class="value activity-level">{{ safari?.activity_level ?? 'NA' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Group Capacity Card -->
                        <div class="detail-card capacity-card">
                            <div class="detail-header">
                                <i class="fas fa-users"></i>
                                <h4>Group & Capacity</h4>
                            </div>
                            <div class="detail-content">
                                <div class="capacity-grid">
                                    <div class="capacity-item">
                                        <div class="capacity-number">{{ safari?.no_of_adult ?? 'NA' }}</div>
                                        <div class="capacity-label">Max Adults</div>
                                    </div>
                                    <div class="capacity-item">
                                        <div class="capacity-number">{{ safari?.no_of_child ?? 'NA' }}</div>
                                        <div class="capacity-label">Max Children</div>
                                    </div>
                                    <div class="capacity-item">
                                        <div class="capacity-number">{{ safari?.number_of_groups ?? 'NA' }}</div>
                                        <div class="capacity-label">Total Groups</div>
                                    </div>
                                    <div class="capacity-item full-width">
                                        <div class="capacity-label">Group Type</div>
                                        <div class="group-type-badge">{{ safari?.group_type ?? 'NA' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Card -->
                        <div class="detail-card availability-card">
                            <div class="detail-header">
                                <i class="fas fa-calendar-alt"></i>
                                <h4>Availability</h4>
                            </div>
                            <div class="detail-content">
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-users-cog"></i> Group Limit per Date</span>
                                    <span class="value availability-value">{{ safari?.per_date_group_limit ?? 'NA'
                                    }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-clipboard-list"></i> Total Slots per
                                        Date</span>
                                    <span class="value availability-value">{{ safari?.total_slots ?? 'NA' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label"><i class="fas fa-tags"></i> Availability Tags</span>
                                    <span class="value availability-tag">{{ safari?.availability_tag ?? 'NA' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Safari Available Dates -->
            <div class="section-card">
                <div class="section-header" @click="toggle('availability')">
                    <div class="section-title">
                        <div class="accordion-toggle">
                            <i :class="availabilityToggle ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="toggle-icon"></i>
                        </div>
                        <h2><i class="fas fa-calendar-check section-icon"></i>Safari Available Dates</h2>
                        <div class="section-badge">{{ actualAvailableDays }} available days</div>
                    </div>
                </div>

                <div v-if="availabilityToggle" class="section-content">
                    <div class="simple-legend">
                        <span class="legend-item">
                            <span class="color-box available"></span>
                            Available
                        </span>
                        <span class="legend-item">
                            <span class="color-box blocked"></span>
                            Blocked
                        </span>
                    </div>

                    <div class="simple-date-picker">
                        <DatePicker :minDate="minAvailableDate" :maxDate="maxAvailableDate"
                            :startDate="minAvailableDate" inline fluid="" :disabledDates="disabledDates" />
                    </div>
                </div>
            </div>

            <!-- Gallery Section -->
            <div class="section-card">
                <div class="section-header" @click="toggle('gallery')">
                    <div class="section-title">
                        <div class="accordion-toggle">
                            <i :class="galleryToggle ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="toggle-icon"></i>
                        </div>
                        <h2><i class="fas fa-images section-icon"></i>Safari Gallery</h2>
                        <div class="section-badge">{{ safari?.gallery?.length || 0 }} images</div>
                    </div>
                </div>

                <div v-if="galleryToggle" class="section-content">
                    <div v-if="safari?.gallery && safari.gallery.length > 0" class="gallery-grid">
                        <div v-for="(image, index) in safari.gallery" :key="index" class="gallery-item">
                            <img :src="image.full_image_url" alt="safari image" class="gallery-image" />
                            <div class="gallery-overlay">
                                <i class="fas fa-expand-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div v-else class="empty-state">
                        <i class="fas fa-image"></i>
                        <p>No images available for this safari</p>
                    </div>
                </div>
            </div>

            <!-- Journey Section -->
            <div class="section-card">
                <div class="section-header" @click="toggle('journey')">
                    <div class="section-title">
                        <div class="accordion-toggle">
                            <i :class="journeyToggle ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="toggle-icon"></i>
                        </div>
                        <h2><i class="fas fa-route section-icon"></i>Day by Day Journey</h2>
                        <div class="section-badge">{{ safari?.journeys?.length || 0 }} days</div>
                    </div>
                </div>

                <div v-if="journeyToggle" class="section-content">
                    <div class="journey-timeline">
                        <div v-for="(day, index) in safari?.journeys" :key="index" class="journey-day">
                            <div class="day-number">
                                <span>{{ index + 1 }}</span>
                            </div>

                            <div class="day-content">
                                
                                <div class="day-header">
                                    <h3 class="day-title">{{ day?.heading ?? "NA" }}</h3>
                                </div>

                                <div class="day-details">
                                    <p class="day-description">{{ day?.subtext ?? "NA" }}</p>
                                </div>

                                <div class="day-features">
                                    <div class="feature-section" v-if="day?.today_highlights">
                                    <h4><i class="fas fa-sun"></i>Today's highlights:</h4>
                                    <div class="feature-content" v-html="day?.today_highlights"></div>
                                </div>
                                    <div class="feature-section">
                                        <h4><i class="fas fa-utensils"></i>Meals & Drinks</h4>
                                        <div class="feature-content" v-if="day?.meal" v-html="day?.meal"></div>
                                        <div class="feature-content" v-else>No meals specified</div>
                                    </div>

                                    <div class="feature-section"
                                        v-if="Array.isArray(day.wildlife_highlights) && day.wildlife_highlights.length">
                                        <h4><i class="fas fa-paw"></i>Wildlife Highlights {{ day.wildlife_location ? 'in '+day.wildlife_location:'' }}</h4>
                                        <div class="wildlife-grid">
                                            <div v-for="(wildlife, wIndex) in day.wildlife_highlights" :key="wIndex"
                                                class="wildlife-card">
                                                <div class="wildlife-image">
                                                    <img :src="wildlife?.image" :alt="wildlife.animal_name" />
                                                </div>
                                                <div class="wildlife-info">
                                                    <h5>{{ wildlife.animal_name ?? '' }}</h5>
                                                    <p>{{ wildlife.description ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="feature-section" v-if="day?.journey_images?.length > 0">
                                        <h4><i class="fas fa-camera"></i>{{ day?.accommodation }} Images</h4>
                                        <div class="day-images">
                                            <img v-for="(image, key) in day.journey_images" :key="key"
                                                :src="image.full_photo_url" alt="Day image" class="day-image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <!-- What's Included & Not Included Section -->
            <div class="section-card">
                <div class="section-header" @click="toggle('included')">
                    <div class="section-title">
                        <div class="accordion-toggle">
                            <i :class="includedToggle ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="toggle-icon"></i>
                        </div>
                        <h2><i class="fas fa-check-circle section-icon"></i>What's Included & Not Included</h2>
                    </div>
                </div>

                <div v-if="includedToggle" class="section-content">
                    <div class="included-grid">
                        <div class="included-card">
                            <h4 class="included-title"><i class="fas fa-check text-success"></i>What's Included</h4>
                            <div class="included-content" v-if="safari?.safari_included" v-html="safari?.safari_included"></div>
                            <div class="included-content" v-else>No included items specified</div>
                        </div>
                        <div class="included-card">
                            <h4 class="included-title"><i class="fas fa-times text-danger"></i>What's Not Included</h4>
                            <div class="included-content" v-if="safari?.safari_not_included" v-html="safari?.safari_not_included"></div>
                            <div class="included-content" v-else>No excluded items specified</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Things to Know Section -->
            <div class="section-card">
                <div class="section-header" @click="toggle('thingsToKnow')">
                    <div class="section-title">
                        <div class="accordion-toggle">
                            <i :class="thingstoknowToggle ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="toggle-icon"></i>
                        </div>
                        <h2><i class="fas fa-info-circle section-icon"></i>Things to Know</h2>
                        <div class="section-badge">{{ safari?.things_to_know?.length || 0 }} items</div>
                    </div>
                </div>

                <div v-if="thingstoknowToggle" class="section-content">
                    <div v-if="safari?.things_to_know?.length > 0" class="info-grid">
                        <div v-for="(things, index) in safari?.things_to_know" :key="index" class="info-card">
                            <h4 class="info-title">{{ things.heading ?? "NA" }}</h4>
                            <div class="info-content" v-html="things.description"></div>
                        </div>
                    </div>
                    <div v-else class="empty-state">
                        <i class="fas fa-info-circle"></i>
                        <p>No additional information available</p>
                    </div>
                </div>
            </div>

            <!-- Operator Section -->
             <div class="section-card">
                <div class="section-header" @click="toggle('operator')">
                    <div class="section-title">
                        <div class="accordion-toggle">
                            <i :class="operatorToggle ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="toggle-icon"></i>
                        </div>
                        <h2><i class="fas fa-user-tie section-icon"></i>Meet Your Safari Operator</h2>
                        <div class="verified-badge">Verified by Tuskari</div>
                    </div>
                </div>

                <div v-if="operatorToggle" class="section-content">
                    <div v-if="safari?.user" class="operator-compact">
                        <div class="operator-card">
                            <div class="operator-main">
                                <h3 class="operator-title">{{ safari?.user?.business_name ?? "NA" }}</h3>
                                <div class="operator-badges">
                                    <span class="badge verified" v-if="safari?.user?.is_approved"><i class="fas fa-check"></i>Verified</span>
                                    <span class="badge location" v-if="safari?.user?.address"><i class="fas fa-map-marker-alt"></i>Based in {{ safari?.user?.address ?? "NA" }}</span>
                                    <span class="badge staff" v-if="safari?.user?.no_of_employees"><i class="fas fa-users"></i>{{ safari?.user?.no_of_employees ?? "NA" }} Staff</span>
                                    <span class="badge verified" v-if="safari?.user?.business_years"><i class="fas fa-calendar-alt"></i>{{ safari?.user?.business_years ?? "NA" }} Years In Business</span>
                                </div>
                            </div>
                            <div class="operator-desc" v-if="safari?.user?.about_operation">
                                <p>{{ safari?.user?.about_operation }}</p>
                                 <p v-if="safari?.user?.why_choose_us" v-html="safari?.user?.why_choose_us"></p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="empty-state">
                        <i class="fas fa-user-times"></i>
                        <p>No operator information available</p>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="section-card">
                <div class="section-header" @click="toggle('review')">
                    <div class="section-title">
                        <div class="accordion-toggle">
                            <i :class="reviewToggle ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="toggle-icon"></i>
                        </div>
                        <h2><i class="fas fa-star section-icon"></i>Safari Reviews</h2>
                        <div class="section-badges">
                            <div class="section-badge">{{ safariReviews?.total || 0 }} reviews</div>
                            <button type="button" class="write-review-btn" @click.stop="visible = true">
                                <i class="fas fa-pen"></i> Write a Review
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="reviewToggle" class="section-content">
                    <div v-if="safariReviews && safariReviews.data.length > 0" class="reviews-grid">
                        <div v-for="(review, index) in safariReviews.data" :key="index" class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        <img :src="review.user?.profile_photo_url || review.user_full_image_url"
                                            :alt="review.user?.full_name || review.username" class="avatar-image" />
                                    </div>
                                    <div class="reviewer-details">
                                        <h4 class="reviewer-name">
                                            {{ review.user?.full_name || review.username }}
                                            <span v-if="!review.user" class="admin-badge">Admin Review</span>
                                        </h4>
                                        <p class="review-date">
                                            {{ new Date(review.created_at).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric'
                                            }) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="rating-badge" :class="{
                                    'rating-excellent': review.rating >= 4.5,
                                    'rating-good': review.rating >= 3.5 && review.rating < 4.5,
                                    'rating-average': review.rating >= 2.5 && review.rating < 3.5,
                                    'rating-poor': review.rating < 2.5
                                }">
                                    <i class="fas fa-star"></i>
                                    <span>{{ review.rating || "N/A" }}</span>
                                </div>
                            </div>

                            <div class="review-content">
                                <h5 class="review-heading">{{ review.heading || "No title" }}</h5>
                                <div class="review-text" v-html="review.details || 'No review content'"></div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="empty-state">
                        <i class="fas fa-star"></i>
                        <p>No reviews available for this safari</p>
                    </div>

                    <div class="row mt-3" v-if="safariReviews.total > safariReviews.per_page">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" role="status" aria-live="polite">
                                Showing {{ safariReviews.from }} to {{ safariReviews.to }} of
                                {{ safariReviews.total }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="float-right">
                                <Bootstrap4Pagination :data="safariReviews" :limit="2"
                                    @pagination-change-page="ListHelper.setPageNum" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Dialog v-model:visible="visible" modal header="Write your review" :style="{ width: '45vw', maxHeight: '90vh' }"
        :breakpoints="{ '1199px': '80vw', '575px': '95vw' }" :draggable="false" :resizable="false"
        class="stylish-review-dialog">
        <div class="popup_form_outer">
            <div class="form_box add_card_popup">
                <form class="write-rvw-popup" @submit.prevent="handleClickListingRating">
                    <div class="row form_row">
                        <div class="col-12 form_col">
                            <div class="rating-outer-pop">
                                <div class="left">Rate your trip</div>
                                <div class="right_strs">
                                    <Rating v-model="form.rating">
                                        <template #onicon>
                                            <Icon icon="tabler:star-filled" width="25" height="25" />
                                        </template>
                                        <template #officon>
                                            <Icon icon="tabler:star" width="25" height="25" />
                                        </template>
                                    </Rating>
                                    <span class="text-danger" v-if="form.errors.rating">{{ form.errors.rating }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form_col">
                            <label>User Name</label>
                            <div class="input_hldr">
                                <InputText type="text" v-model="form.username" placeholder="Write review username" />
                                <span class="text-danger" v-if="form.errors.username">{{ form.errors.username }}</span>
                            </div>
                        </div>

                        <div class="col-12 form_col">
                            <label>User Image</label>
                            <div class="input_hldr">
                                <file-upload @input="form.image = $event.target.files[0]" :imageurl="imageUrl" />
                                <span class="text-danger" v-if="form.errors.image">{{ form.errors.image }}</span>
                            </div>
                        </div>

                        <div class="col-12 form_col">
                            <label>Write your review</label>
                            <div class="input_hldr">
                                <InputText type="text" v-model="form.heading" placeholder="Write review heading" />
                                <span class="text-danger" v-if="form.errors.heading">{{ form.errors.heading }}</span>
                            </div>
                        </div>

                        <div class="col-12 form_col">
                            <div class="input_hldr">
                                <Textarea v-model="form.details" placeholder="Write your thoughts" rows="6" autoResize
                                    fluid="" />
                                <span class="text-danger" v-if="form.errors.details">{{ form.errors.details }}</span>
                            </div>
                        </div>

                        <div class="col-12 form_col submit-btn-container">
                            <button type="submit" class="stylish-submit-btn" :disabled="form.processing">
                                <i class="fas fa-paper-plane"></i>
                                {{ form.processing ? 'Submitting...' : 'Submit Review' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </Dialog>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import MapBoxView from '@/components/Frontend/MapBoxView.vue';
import { useForm } from "@inertiajs/vue3";
import FileUpload from "../../../components/FileUpload.vue";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import ListHelper from "../../../helpers/ListHelper";

const progressValue = ref(50);
const visible = ref(false);
const props = defineProps({
    safari: Object,
    product: Object,
    uniqueSizes: String,
    safariImages: Array,
    safariTiming: Array,
    animalSighting: Object,
    thingsToKnow: Array,
    safariReviews: Object,
});

const form = useForm({
    safari_id: props?.safari?.id,
    rating: '',
    username: '',
    heading: '',
    details: '',
    image: null
});

const location = ref({
    name: props.safari?.location || 'Unknown Location',
    lat: props.safari?.lat || 0,
    lng: props.safari?.long || 0,
    description: props.safari?.location || 'No description available.'
});

const basicToggle = ref(true);
const availabilityToggle = ref(false);
const galleryToggle = ref(false);
const journeyToggle = ref(false);
const animalSightToggle = ref(false);
const accodomationToggle = ref(false);
const includedToggle = ref(false);
const thingstoknowToggle = ref(false);
const operatorToggle = ref(false);
const reviewToggle = ref(false);

const toggle = (slug) => {
    if (slug === "basic") {
        basicToggle.value = !basicToggle.value;
    }
    if (slug === "availability") {
        availabilityToggle.value = !availabilityToggle.value;
    }
    if (slug === "gallery") {
        galleryToggle.value = !galleryToggle.value;
    }
    if (slug === "journey") {
        journeyToggle.value = !journeyToggle.value;
    }
    if (slug === "animalSight") {
        animalSightToggle.value = !animalSightToggle.value;
    }
    if (slug === "accomodation") {
        accodomationToggle.value = !accodomationToggle.value;
    }
    if (slug === "included") {
        includedToggle.value = !includedToggle.value;
    }
    if (slug === "thingsToKnow") {
        thingstoknowToggle.value = !thingstoknowToggle.value;
    }
    if (slug === "operator") {
        operatorToggle.value = !operatorToggle.value;
    }
    if (slug === "review") {
        reviewToggle.value = !reviewToggle.value;
    }
};

const formattedKeyExperiences = computed(() => {
    return props.safari.key_experiences?.map((exp) => exp.title).join(", ") || "NA";
});

const formatMeals = (meals) => {
    try {
        const parsedMeals = JSON.parse(meals.replace(/'/g, '"'));
        return parsedMeals.join(", ");
    } catch (error) {
        return "N/A";
    }
};

const parseTimings = (safariTiming) => {
    try {
        return JSON.parse(safariTiming.replace(/'/g, '"'));
    } catch (error) {
        return [];
    }
};

const parseAnimal = (animalSighting) => {
    try {
        return JSON.parse(animalSighting.replace(/'/g, '"'));
    } catch (error) {
        return [];
    }
};

onMounted(() => {
    const pageTitle = "Safari/Listing Details";
    emit.emit("pageName", "Safari/Listing Management", [
        {
            title: "Safari/Listing List",
            routeName: "admin.safari.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

const formatDateWithMonth = (dateString) => {
    if (!dateString || dateString === 'NA') return 'NA';

    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    } catch (error) {
        return dateString;
    }
};

const handleClickListingRating = (e) => {
    form.post(route("admin.safari-review.create"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            visible.value = false;
        }
    });
};

const minAvailableDate = computed(() => {
    if (!props.safari?.date_range?.length) {
        return new Date();
    }


    const earliest = props.safari.date_range
        .map(r => new Date(r.available_start_date))
        .sort((a, b) => a - b)[0];

    return earliest;
});

const maxAvailableDate = computed(() => {
    if (!props.safari?.date_range?.length) {
        return new Date();
    }

    return props.safari.date_range
        .map(r => new Date(r.available_end_date))
        .sort((a, b) => b - a)[0]; // latest end
});
const getDatesBetween = (start, end) => {
    const dates = [];
    let current = new Date(start);
    const last = new Date(end);

    while (current <= last) {
        dates.push(new Date(current));
        current.setDate(current.getDate() + 1);
    }
    return dates;
}

const availableDates = computed(() => {
    if (!props.safari?.date_range?.length) return [];

    let allDates = [];

    props.safari.date_range.forEach(range => {
        if (range.available_start_date && range.available_end_date) {
            allDates = [
                ...allDates,
                ...getDatesBetween(range.available_start_date, range.available_end_date)
            ];
        }
    });

    return allDates;
});

const disabledDates = computed(() => {
    const availableSet = new Set(availableDates.value.map(d => d.toDateString()));

    // 1. Blocked ranges from backend
    let blocked = [];
    props.safari.date_range.forEach(range => {
        if (range.blocked_start_date && range.blocked_end_date) {
            blocked.push(...getDatesBetween(range.blocked_start_date, range.blocked_end_date));
        }
    });

    // 2. Dates outside available ranges
    const min = minAvailableDate.value;
    const max = maxAvailableDate.value;
    let allBetween = getDatesBetween(min, max);
    let notAvailable = allBetween.filter(d => !availableSet.has(d.toDateString()));

    // 3. Fully booked dates (already array of strings like "2025-08-25")
    let fullyBooked = props.fullyBookedDates?.map(d => new Date(d)) || [];

    return [...blocked, ...notAvailable, ...fullyBooked];
});

const actualAvailableDays = computed(() => {
    const disabledSet = new Set(disabledDates.value.map(d => d.toDateString()));
    return availableDates.value.filter(date => !disabledSet.has(date.toDateString())).length;
});

</script>

<style scoped>
.safari-details-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    min-height: 100vh;
    padding: 2rem;
}

.safari-content {
    max-width: 1400px;
    margin: 0 auto;
}

/* Hero Section */
.hero-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.hero-card:hover {
    transform: translateY(-2px);
}

.hero-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #c8cacc 0%, #e9ecef 100%);
    cursor: pointer;
    border-bottom: 1px solid #dee2e6;
    transition: all 0.3s ease;
    position: relative;
}

.hero-header:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.hero-title-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.hero-accordion-toggle {
    width: 40px;
    height: 40px;
    min-width: 40px;
    min-height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
    flex-shrink: 0;
}

.hero-accordion-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(47, 58, 47, 0.4);
}

.hero-toggle-icon {
    color: white;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.safari-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    color: #495057;
}

.operator-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    margin-top: 1rem;
    box-shadow: 0 2px 6px rgba(47, 58, 47, 0.3);
}

.hero-content {
    padding: 2rem;
}

.hero-main-content {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
    min-height: 250px;
}

.hero-left {
    display: flex;
    align-items: stretch;
}

.hero-right {
    display: flex;
    align-items: stretch;
}

.description-section {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.description-text {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    flex: 1;
    display: flex;
    align-items: center;
    color: #495057;
    border-left: 4px solid #2f3a2f;
}

.hero-thumbnail {
    width: 100%;
    height: 100%;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 2px solid #dee2e6;
}

.hero-thumbnail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.hero-thumbnail-image:hover {
    transform: scale(1.05);
}

/* Safari Details Grid */
.safari-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.full-width-card {
    grid-column: 1 / -1;
}

.detail-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 15px;
    border: 2px solid #e9ecef;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.detail-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    border-color: #dee2e6;
}

.detail-header {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.detail-header i {
    font-size: 1.2rem;
}

.detail-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.detail-content {
    padding: 1.5rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item .label {
    font-weight: 600;
    color: #495057;
    font-size: 1 rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-item .label i {
    color: #6c757d;
    width: 16px;
}

.detail-item .value {
    text-align: right;
    max-width: 60%;
    word-wrap: break-word;
    color: #333639;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
}

.comma-separated {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    line-height: 1.4;
}


.tag-item {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.tag-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: flex-end;
    max-width: 60%;
}

.item-tag {
    background: #2f3a2f;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 3px 8px rgba(47, 58, 47, 0.4);
    border: 1px solid rgba(74, 90, 74, 0.3);
}

.activities-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
}

.activities-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    width: 100%;
}

.activity-tag {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
    transition: all 0.3s ease;
}

.activity-tag:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(23, 162, 184, 0.4);
}

/* Map Section */
.map-section {
    margin-bottom: 2rem;
}

.map-container {
    height: 400px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.no-location-container {
    height: 390px;
    border-radius: 15px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-location-content {
    text-align: center;
    color: #6c757d;
}

.no-location-content i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-location-content p {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 500;
}

.highlight-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 1.8rem;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 1.2rem;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    position: relative;
    overflow: hidden;
}

.highlight-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2f3a2f 0%, #4a5a4a 100%);
}

.pricing-card::before {
    background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%) !important;
}

.season-card::before {
    background: linear-gradient(90deg, #28a745 0%, #20c997 100%) !important;
}

.activities-card::before {
    background: linear-gradient(90deg, #17a2b8 0%, #20c997 100%) !important;
}

.type-card::before {
    background: linear-gradient(90deg, #6f42c1 0%, #8b5cf6 100%) !important;
}

.capacity-card::before {
    background: linear-gradient(90deg, #fd7e14 0%, #ff8f00 100%) !important;
}

.availability-card::before {
    background: linear-gradient(90deg, #e83e8c 0%, #f06292 100%) !important;
}

.highlight-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    border-color: #dee2e6;
}

.highlight-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    box-shadow: 0 8px 25px rgba(47, 58, 47, 0.4);
    flex-shrink: 0;
}

.price-icon {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
}

.highlight-card:nth-child(3) .highlight-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

.activities-card .highlight-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    box-shadow: 0 8px 25px rgba(23, 162, 184, 0.4);
}

.type-card .highlight-icon {
    background: linear-gradient(135deg, #6f42c1 0%, #8b5cf6 100%);
    box-shadow: 0 8px 25px rgba(111, 66, 193, 0.4);
}

.capacity-card .highlight-icon {
    background: linear-gradient(135deg, #fd7e14 0%, #ff8f00 100%);
    box-shadow: 0 8px 25px rgba(253, 126, 20, 0.4);
}

.availability-card .highlight-icon {
    background: linear-gradient(135deg, #e83e8c 0%, #f06292 100%);
    box-shadow: 0 8px 25px rgba(232, 62, 140, 0.4);
}

.season-card .highlight-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

.highlight-content {
    flex: 1;
}

.highlight-content h4 {
    margin: 0 0 0.8rem 0;
    font-size: 1.2rem;
    font-weight: 700;
    color: #2c3e50;
    letter-spacing: -0.5px;
}

.highlight-content p {
    margin: 0;
    color: #5a6c7d;
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.4;
}

.price-text {
    font-size: 1.6rem;
    font-weight: 800;
    color: #2563eb;
}

/* Card Type Specific Styles - Using Theme Colors */
.detail-header {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%) !important;
}

.pricing-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 1rem;
}

.price-item {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.price-item:hover {
    border-color: #2563eb;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1);
}

.price-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.price-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2563eb;
}

/* Pricing & Seasons Card Styles */
.pricing-seasons-card {
    overflow: visible;
}

.pricing-seasons-card .detail-header {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
}

.pricing-seasons-card .detail-content {
    overflow: visible;
    min-width: 0;
}

.seasons-pricing-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-top: 1rem;
    width: 100%;
    box-sizing: border-box;
}

.season-pricing-item {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    min-width: 0;
    box-sizing: border-box;
}

.season-pricing-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.season-pricing-item.low-season {
    border-left: 4px solid #28a745;
}

.season-pricing-item.high-season {
    border-left: 4px solid #ffc107;
}

.pricing-row {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.price-detail {
    flex: 1;
    background: #f8f9fa;
    padding: 10px 0;
    border-radius: 12px;
    text-align: center;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.price-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.price-type {
    display: block;
    font-size: 12px;
    color: #4b4f52;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.price-amount {
    display: block;
    font-size: 1.4rem;
    font-weight: 700;
    color: #2563eb;
}

.season-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
}

.season-item {
    background: white;
    padding: 1.2rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.season-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.low-season {
    border-left: 3px solid #28a745;
}

.high-season {
    border-left: 3px solid #ffc107;
}

.season-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.8rem;
    font-weight: 600;
    color: #495057;
}

.season-header i {
    font-size: 1.1rem;
}

.low-season .season-header i {
    color: #28a745;
}

.high-season .season-header i {
    color: #ffc107;
}

.date-range {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 0.95rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 1rem;
}

.date-range i {
    color: #495057;
    font-size: 0.8rem;
}

.date-display {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);

    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    color: #495057;
    border: 1px solid #dee2e6;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
}

.highlighted-price {
    color: #333639 !important;
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    padding: 0.3rem 0.6rem !important;
    border-radius: 6px !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    letter-spacing: 0.2px;

}

/* Activities Card Styles */
.activities-card {
    border-left: 4px solid #17a2b8 !important;
}

.activity-section {
    margin-top: 1rem;
}

.info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.8rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
    min-width: 140px;
}

.info-label i {
    color: #17a2b8;
    width: 16px;
}

.info-value {
    color: #6c757d;
    font-weight: 500;
    text-align: right;
    flex: 1;
}

.activity-level {
    background: linear-gradient(135deg, #2f3a2f, #4a5a4a);
    color: white !important;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

/* Type Card Styles */
.type-card {
    border-left: 4px solid #6f42c1 !important;
}

.type-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}

.type-tag {
    background: linear-gradient(135deg, #2f3a2f, #4a5a4a);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(47, 58, 47, 0.3);
    transition: all 0.3s ease;
}

.type-tag:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(47, 58, 47, 0.4);
}

/* Capacity Card Styles */
.capacity-card {
    border-left: 4px solid #fd7e14 !important;
}

.capacity-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}

.capacity-item {
    background: white;
    padding: 1.2rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    text-align: center;
    transition: all 0.3s ease;
}

.capacity-item:hover {
    border-color: #fd7e14;
    box-shadow: 0 2px 8px rgba(253, 126, 20, 0.1);
}

.capacity-item.full-width {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-align: left;
}

.capacity-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2f3a2f;
    margin-bottom: 0.5rem;
}

.capacity-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.group-type-badge {
    background: linear-gradient(135deg, #2f3a2f, #4a5a4a);
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Availability Card Styles */
.availability-card {
    border-left: 4px solid #e83e8c !important;
}

.availability-info {
    margin-top: 1rem;
}

.availability-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.8rem 1rem;
    background: white;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    border: 1px solid #f1f3f4;
    transition: all 0.3s ease;
}

.availability-row:hover {
    border-color: #e83e8c;
    box-shadow: 0 2px 6px rgba(232, 62, 140, 0.1);
}

.availability-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
}

.availability-value {
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
}

.availability-tag {
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    box-shadow: 0 2px 6px rgba(47, 58, 47, 0.3);
}

/* Section Cards */
.section-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.section-card:hover {
    transform: translateY(-2px);
}

.section-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #e2e4e6 0%, #ccd0d4 100%);
    cursor: pointer;
    border-bottom: 1px solid #dee2e6;
    transition: all 0.3s ease;
    position: relative;
}

.section-header:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}



.section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    gap: 1rem;
}

.section-title h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.section-badges {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.write-review-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
}

.write-review-btn:hover {
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4) !important;
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
}

.section-icon {
    color: #2f3a2f;
    font-size: 1.3rem;
}

.accordion-toggle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
}

.accordion-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(47, 58, 47, 0.4);
}

.toggle-icon {
    color: white;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.section-badge {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(47, 58, 47, 0.3);
}

.section-content {
    padding: 2rem;
}

/* Gallery */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.gallery-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 1;
    cursor: pointer;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: white;
    font-size: 1.5rem;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.1);
}

/* Journey Timeline */
.journey-timeline {
    position: relative;
}

.journey-day {
    display: flex;
    gap: 2rem;
    margin-bottom: 3rem;
    position: relative;
}

.journey-day:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 30px;
    top: 60px;
    bottom: -3rem;
    width: 2px;
    background: linear-gradient(to bottom, #2f3a2f, #4a5a4a);
}

.day-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    flex-shrink: 0;
    box-shadow: 0 5px 15px rgba(47, 58, 47, 0.4);
}

.day-content {
    flex: 1;
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
}

.day-title {
    margin: 0 0 1rem 0;
    color: #495057;
    font-size: 1.3rem;
    font-weight: 600;
}

.day-description {
    color: #6c757d;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.day-features {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.feature-section h4 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0 0 1rem 0;
    color: #495057;
    font-weight: 600;
}

.feature-content {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    border-left: 4px solid #2f3a2f;
}

.wildlife-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
}

.wildlife-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    gap: 1rem;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.wildlife-image img {
    width: 60px;
    height: 60px;
    border-radius: 10px;
}

.wildlife-info h5 {
    margin: 0 0 0.5rem 0;
    color: #495057;
    font-weight: 600;
}

.wildlife-info p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.day-images {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
}

.day-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #2f3a2f;
}

.info-title {
    margin: 0 0 1rem 0;
    color: #495057;
    font-weight: 600;
}

.info-content {
    color: #6c757d;
    line-height: 1.6;
}

/* Operator Section */

.verified-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.verified-badge::before {
    content: '\f058';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 0.9rem;
}

.operator-info {
    display: flex;
    flex-direction: column;
}

.operator-detail-compact {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 15px;
}

.detail-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.detail-content-compact {
    flex: 1;
}

.operator-name {
    margin: 0 0 0.75rem 0;
    color: #495057;
    font-size: 1.6rem;
    font-weight: 600;
}

.operator-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 1.1rem;
}

.meta-item i {
    color: #2f3a2f;
    width: 16px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Header Actions */
.header-actions {
    margin-bottom: 15px;
    display: flex;
    justify-content: flex-end;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    color: #495057;
    text-decoration: none;
    font-weight: 600;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 0.6rem 1.1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 1rem;
}

.back-button:hover {
    color: #2f3a2f;
    background: #e9ecef;
    border-color: #2f3a2f;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Reviews Section */
.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
}

.review-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    border-left: 4px solid #2f3a2f;
    transition: transform 0.3s ease;
}

.review-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.reviewer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #dee2e6;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.reviewer-details {
    flex: 1;
}

.reviewer-name {
    margin: 0 0 0.25rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
}

.review-date {
    margin: 0;
    font-size: 0.9rem;
    color: #6c757d;
}

.rating-badge {
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.rating-excellent {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.rating-good {
    background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.3);
}

.rating-average {
    background: linear-gradient(135deg, #fd7e14 0%, #e55a4e 100%);
    box-shadow: 0 2px 6px rgba(253, 126, 20, 0.3);
}

.rating-poor {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
}

.admin-badge {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
    margin-left: 0.5rem;
    display: inline-block;
}

.review-content {
    background: white;
    padding: 1.25rem;
    border-radius: 10px;
    border: 1px solid #dee2e6;
}

.review-heading {
    margin: 0 0 0.75rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
}

.review-text {
    color: #6c757d;
    line-height: 1.6;
    margin: 0;
}

/* Stylish Review Dialog */
:deep(.stylish-review-dialog) {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
}

:deep(.stylish-review-dialog .p-dialog-header) {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: none;
}

:deep(.stylish-review-dialog .p-dialog-title) {
    font-size: 1.4rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

:deep(.stylish-review-dialog .p-dialog-title::before) {
    content: '\f005';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: #ffc107;
}

:deep(.stylish-review-dialog .p-dialog-header-close) {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    width: 35px;
    height: 35px;
    transition: all 0.3s ease;
}

:deep(.stylish-review-dialog .p-dialog-header-close:hover) {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

:deep(.stylish-review-dialog .p-dialog-content) {
    padding: 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.popup_form_outer {
    padding: 2rem;
}

.form_box {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.form_col {
    margin-bottom: 1.5rem;
}

.form_col label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
    font-size: 1rem;
}

.rating-outer-pop {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    border-left: 4px solid #2f3a2f;
}

.rating-outer-pop .left {
    font-weight: 600;
    color: #495057;
    font-size: 1.1rem;
}

.input_hldr {
    position: relative;
}

:deep(.input_hldr .p-inputtext) {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

:deep(.input_hldr .p-inputtext:focus) {
    border-color: #2f3a2f;
    box-shadow: 0 0 0 3px rgba(47, 58, 47, 0.1);
    outline: none;
}

:deep(.input_hldr .p-inputtextarea) {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid #e9ecef;

    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    resize: vertical;
    min-height: 100px;
}

:deep(.input_hldr .p-inputtextarea:focus) {
    border-color: #2f3a2f;
    box-shadow: 0 0 0 3px rgba(47, 58, 47, 0.1);
    outline: none;
}

.text-danger {
    color: #dc3545 !important;
    font-weight: 600;
    font-size: 0.90rem;
    margin-top: 0.25rem;
    display: block;
}

:deep(.p-rating .p-rating-icon) {
    color: #ffc107;
    font-size: 1.5rem;
    margin: 0 0.2rem;
    transition: all 0.3s ease;
}

:deep(.p-rating .p-rating-icon:hover) {
    transform: scale(1.2);
}

.submit-btn-container {
    display: flex;
    justify-content: flex-end;
}

.stylish-submit-btn {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    box-shadow: 0 3px 10px rgba(47, 58, 47, 0.3);
}

.stylish-submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(47, 58, 47, 0.4);
    background: linear-gradient(135deg, #4a5a4a 0%, #2f3a2f 100%);
}

.stylish-submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Responsive Styles for New Components */
@media (max-width: 1200px) {
    .pricing-seasons-card .detail-content {
        overflow-x: auto;
        padding: 1rem;
    }

    .seasons-pricing-grid {
        min-width: 600px;
    }
}

@media (max-width: 768px) {
    .pricing-grid {
        grid-template-columns: 1fr;
    }

    .seasons-pricing-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        min-width: auto;
    }

    .pricing-row {
        flex-direction: column;
        gap: 0.8rem;
    }

    .date-range {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .date-display {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }

    .highlighted-price {
        font-size: 1rem !important;
        padding: 0.25rem 0.5rem !important;
    }

    .capacity-grid {
        grid-template-columns: 1fr 1fr;
    }

    .capacity-item.full-width {
        grid-column: 1 / -1;
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .info-row,
    .availability-row {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .info-label {
        min-width: auto;
    }

    .type-tags {
        justify-content: center;
    }

    .pricing-seasons-card .detail-content {
        overflow-x: visible;
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .capacity-grid {
        grid-template-columns: 1fr;
    }

    .season-grid {
        gap: 0.8rem;
    }

    .seasons-pricing-grid {
        gap: 0.8rem;
        min-width: auto;
    }

    .season-pricing-item {
        padding: 1rem;
    }

    .price-detail {
        padding: 1rem;
    }

    .date-display {
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
    }

    .highlighted-price {
        font-size: 0.9rem !important;
        padding: 0.2rem 0.4rem !important;
    }

    .price-type {
        font-size: 0.75rem;
    }
}

/* Simple Date Picker Styles */
.simple-legend {
    display: flex;
    gap: 2rem;
    margin-bottom: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.simple-legend .legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: #495057;
}

.color-box {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}

.color-box.available {
    background: #74da8b;
    border: 1px solid #c3e6cb;
}

.color-box.blocked {
    background: #d67981;
    border: 1px solid #f5c6cb;
}

.simple-date-picker {
    max-width: 400px;
    margin: 0 auto;
}

:deep(.simple-date-picker .p-datepicker) {
    width: 100% !important;
    border-radius: 8px !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
    border: 1px solid #ffc107 !important;
}

:deep(.p-datepicker .p-datepicker-select-year option),
:deep(.p-datepicker .p-datepicker-select-month option) {
    background: #2f3a2f !important;
    color: #ffc107 !important;
}

:deep(.p-button.p-component.p-button-icon-only.p-button-secondary.p-button-rounded.p-button-text.p-datepicker-next-button),
:deep(.p-button.p-component.p-button-icon-only.p-button-secondary.p-button-rounded.p-button-text.p-datepicker-prev-button) {
    background: rgba(255, 193, 7, 0.2) !important;
    color: white !important;
    border: 1px solid #ffc107 !important;
    width: 36px !important;
    height: 36px !important;
}

:deep(.p-button.p-component.p-button-icon-only.p-button-secondary.p-button-rounded.p-button-text.p-datepicker-next-button:hover),
:deep(.p-button.p-component.p-button-icon-only.p-button-secondary.p-button-rounded.p-button-text.p-datepicker-prev-button:hover) {
    background: #445344 !important;
    color: #f8fff8 !important;
    transform: scale(1.1) !important;
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.4) !important;
}

:deep(.p-datepicker .p-datepicker-header) {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%) !important;
    color: white !important;
    padding: 1.2rem !important;
    border-radius: 8px 8px 0 0 !important;
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3) !important;
    border-bottom: 2px solid #ffc107 !important;
}

:deep(.p-datepicker .p-datepicker-title) {
    color: #ffc107 !important;
    font-weight: 700 !important;
    font-size: 1.2rem !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
    letter-spacing: 0.5px !important;
}

:deep(.p-datepicker .p-datepicker-select-year),
:deep(.p-datepicker .p-datepicker-select-month) {
    background: #2f3a2f !important;
    color: #ffc107 !important;
    border: 1px solid #ffc107 !important;
    border-radius: 4px !important;
    padding: 0.25rem 0.5rem !important;
    font-weight: 600 !important;
}

:deep(.p-datepicker .p-datepicker-select-year:hover),
:deep(.p-datepicker .p-datepicker-select-month:hover) {
    background: #ffc107 !important;
    color: #2f3a2f !important;
}

:deep(.p-datepicker .p-datepicker-calendar thead th) {
    background: #f8f9fa !important;
    color: #495057 !important;
    font-weight: 600 !important;
    padding: 0.75rem !important;
}

:deep(.p-datepicker .p-datepicker-calendar td span:not(.p-disabled)) {
    background: #74d38a !important;
    color: #155724 !important;
    border-radius: 6px !important;
    font-weight: 600 !important;
    border: 1px solid #c3e6cb !important;
}

:deep(.p-datepicker .p-datepicker-calendar td span:not(.p-disabled):hover) {
    background: #b8daff !important;
    color: #004085 !important;
    border-color: #7db3ff !important;
    transform: scale(1.05) !important;
}

:deep(.p-datepicker .p-datepicker-calendar td span.p-disabled) {
    background: #f66c77 !important;
    color: #271a1b !important;
    opacity: 0.8 !important;
    cursor: not-allowed !important;
    border-radius: 6px !important;
    border: 1px solid #f5c6cb !important;
}

:deep(.p-datepicker .p-datepicker-calendar td span.p-highlight:not(.p-disabled)) {
    background: #2f3a2f !important;
    color: white !important;
    border: 2px solid #ffc107 !important;
}

:deep(.p-datepicker .p-datepicker-calendar td span) {
    width: 36px !important;
    height: 36px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 auto !important;
}

@media (max-width: 480px) {
    :deep(.p-datepicker .p-datepicker-calendar td span) {
        width: 28px !important;
        height: 28px !important;
        font-size: 0.8rem !important;
    }
}

/* Special Trip Section */
.special-trip-section {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-radius: 15px;
    padding: 1.5rem;
    margin: 2rem 0 3rem 0;
    border-left: 4px solid #ffc107;
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.2);
    transition: all 0.3s ease;
    width: 100%;
    grid-column: 1 / -1;
}

.special-trip-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
}

.special-trip-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0 0 1rem 0;
    font-size: 1.3rem;
    font-weight: 700;
    color: #856404;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid rgba(255, 193, 7, 0.3);
}

.special-trip-title i {
    color: #ffc107;
    font-size: 1.4rem;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.special-trip-content {
    color: #6c5700;
    line-height: 1.6;
    font-size: 1rem;
    font-weight: 500;
}

/* Operator Section Enhancements */
.operator-compact {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.operator-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.operator-main {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    padding: 1.5rem;
    position: relative;
}

.operator-main::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #ffc107 0%, #ff8f00 100%);
}

.operator-title {
    margin: 0 0 1rem 0;
    font-size: 1.4rem;
    font-weight: 700;
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.operator-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}

.badge i {
    font-size: 1.4rem;
    flex-shrink: 0;
}

.badge.verified {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.badge.location {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    color: white;
}

.badge.staff {
    background: linear-gradient(135deg, #fd7e14 0%, #ff8f00 100%);
    color: white;
}

.badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.operator-desc {
    padding: 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.operator-desc p {
    margin: 0;
    color: #6c757d;
    line-height: 1.6;
    font-size: 1rem;
}

/* What's Included & Not Included Section */
.included-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.included-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 15px;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.included-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    border-color: #dee2e6;
}

.included-card:first-child {
    border-left: 4px solid #28a745;
}

.included-card:last-child {
    border-left: 4px solid #dc3545;
}

.included-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0 0 1rem 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: #495057;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f3f4;
}

.included-title .text-success {
    color: #28a745 !important;
    font-size: 1.3rem;
}

.included-title .text-danger {
    color: #dc3545 !important;
    font-size: 1.3rem;
}

.included-content {
    color: #6c757d;
    line-height: 1.6;
    font-size: 1rem;
}

.included-content ul {
    margin: 0;
    padding-left: 1.5rem;
}

.included-content li {
    margin-bottom: 0.5rem;
    position: relative;
}

.included-card:first-child .included-content li::marker {
    color: #28a745;
}

.included-card:last-child .included-content li::marker {
    color: #dc3545;
}

/* Responsive fixes for 1440px to 992px */
@media (max-width: 1440px) and (min-width: 993px) {
    .seasons-pricing-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        width: 100%;
    }

    .pricing-row {
        flex-direction: column;
        gap: 0.8rem;
    }

    .price-detail {
        padding: 1rem 0.8rem;
        font-size: 0.9rem;
        width: 100%;
    }

    .price-amount {
        font-size: 1.2rem;
    }

    .date-range {
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
    }

    .date-display {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        min-width: 80px;
        text-align: center;
        flex-shrink: 0;
    }

    .season-pricing-item {
        padding: 1.2rem;
        width: 100%;
        box-sizing: border-box;
    }

    .safari-details-grid {
        gap: 1.2rem;
    }

    .detail-content {
        padding: 1.2rem;
    }

    .pricing-seasons-card .detail-content {
        overflow: visible;
        padding: 1.2rem;
    }

    .pricing-seasons-card {
        overflow: visible;
        width: 100%;
    }
}

@media (max-width: 992px) {
    .seasons-pricing-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .pricing-row {
        flex-direction: column;
        gap: 0.8rem;
    }

    .date-range {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .safari-details-grid {
        grid-template-columns: 1fr;
    }

    .included-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

/* Original Responsive */
@media (max-width: 768px) {
    :deep(.stylish-review-dialog) {
        margin: 1rem;
    }

    .popup_form_outer {
        padding: 1rem;
    }

    .form_box {
        padding: 1.5rem;
    }

    .section-badges {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }

    .write-review-btn {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
    }

    .safari-details-container {
        padding: 1rem;
    }

    .safari-title {
        font-size: 2rem;
    }

    .hero-main-content {
        grid-template-columns: 1fr;
        gap: 1rem;
        min-height: auto;
    }

    .hero-left {
        order: 2;
    }

    .hero-right {
        order: 1;
    }

    .hero-thumbnail {
        height: 200px;
        margin: 0 auto;
    }

    .safari-details-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .seasons-pricing-grid {
        grid-template-columns: 1fr;
        min-width: auto;
    }

    .pricing-seasons-card {
        overflow: visible;
    }

    .pricing-seasons-card .detail-content {
        overflow-x: visible;
    }

    .activities-tags {
        justify-content: center;
    }

    .detail-item {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .detail-item .value {
        max-width: 100%;
        text-align: center;
    }

    .journey-day {
        flex-direction: column;
        gap: 1rem;
    }

    .journey-day:not(:last-child)::after {
        display: none;
    }

    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .reviews-grid {
        grid-template-columns: 1fr;
    }

    .review-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .rating-badge {
        align-self: flex-end;
    }

    .simple-legend {
        flex-direction: column !important;
        gap: 1rem !important;
    }

    .simple-date-picker {
        max-width: 100% !important;
    }

    :deep(.p-datepicker .p-datepicker-calendar td span) {
        width: 32px !important;
        height: 32px !important;
        font-size: 0.85rem !important;
    }

    .included-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .included-card {
        padding: 1.2rem;
    }

    .operator-badges {
        flex-direction: column;
        gap: 0.5rem;
    }

    .badge {
        justify-content: center;
    }
}
</style>