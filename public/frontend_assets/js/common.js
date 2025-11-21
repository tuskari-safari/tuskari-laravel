jQuery(document).ready(function ($) {
    // document start


    // Navbar
    $("<span class='clickD'></span>").insertAfter(".navbar-nav li.menu-item-has-children > a");
    $('.navbar-nav li .clickD').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.next().hasClass('show')) {
            $this.next().removeClass('show');
            $this.removeClass('toggled');
        }
        else {
            $this.parent().parent().find('.sub-menu').removeClass('show');
            $this.parent().parent().find('.toggled').removeClass('toggled');
            $this.next().toggleClass('show');
            $this.toggleClass('toggled');
        }
    });

    $(window).on('resize', function () {
        if ($(this).width() < 1025) {
            $('html').click(function () {
                $('.navbar-nav li .clickD').removeClass('toggled');
                $('.toggled').removeClass('toggled');
                $('.sub-menu').removeClass('show');
            });
            $(document).click(function () {
                $('.navbar-nav li .clickD').removeClass('toggled');
                $('.toggled').removeClass('toggled');
                $('.sub-menu').removeClass('show');
            });
            $('.navbar-nav').click(function (e) {
                e.stopPropagation();
            });
        }
    });
    // Navbar end



    /* ===== For menu animation === */
    $(".navbar-toggler").click(function () {
        $(".navbar-toggler").toggleClass("open");
        $(".navbar-toggler .stick").toggleClass("open");
        $('body,html').toggleClass("open-nav");
    });

    // Navbar end


    // Sticky Nav
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll > 0) {
            $(".main-head").addClass("fixed");
        }
        else {
            $(".main-head").removeClass("fixed");
        }
    })


    // back to top
    if ($("#scroll").length) {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 200) {
                $('#scroll').fadeIn(200);
            } else {
                $('#scroll').fadeOut(200);
            }
        });
        $('#scroll').click(function () {
            $("html, body").animate({ scrollTop: 0 }, 500);
            return false;
        });
    }


   

    $(document).ready(function () {
        // Initialize Slick slider
        $('.slider-testimorial').slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: false,
            slidesToShow: 3,
            slidesToScroll: 1,
            centerMode: true,
            centerPadding: '0px',
            responsive: [
                {
                    breakpoint: 1601,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });

        // Function to update next/prev custom classes
        function updateCustomClasses() {
            const $slider = $('.slider-testimorial');
            $slider.find('.sl_next, .sl_prev').removeClass('sl_next sl_prev'); // Clear old
            $slider.find('.slick-center').next().addClass('sl_next');
            $slider.find('.slick-center').prev().addClass('sl_prev');
        }

        // Initial class set
        updateCustomClasses();

        // On change, update custom classes
        $('.slider-testimorial').on('beforeChange', function () {
            $('.slider-testimorial').find('.sl_next, .sl_prev').removeClass('sl_next sl_prev');
        });

        $('.slider-testimorial').on('afterChange', function () {
            updateCustomClasses();
        });

        // Custom Prev/Next buttons
        $(document).on('click', '.prev', function () {
            $('.slider-testimorial').slick('slickPrev');
        });

        $(document).on('click', '.next', function () {
            $('.slider-testimorial').slick('slickNext');
        });
    });


    if ($(".custmsliderwrpr").length) {
        function initProgress($slider) {
            var $progressBar = $slider.closest(".custmsliderwrpr").find(".featrprgrsafter");
            $slider.on("beforeChange", function (e, slick, cur, next) {
                var pct = (next / (slick.slideCount - 1)) * 100;
                $progressBar.find(".featrprgrsfill").css("width", pct + "%");
            });
        }

        function initMobileSlider($wrapper) {
            var $mb = $wrapper.find(".mb-tuskarislidr");

            if ($(window).width() < 767) {
                $mb.each(function () {
                    var $this = $(this);
                    if (!$this.hasClass("slick-initialized")) {
                        $this.slick({
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            arrows: true,
                            speed: 400,
                            dots: false,
                            autoplay: false,
                            prevArrow: $wrapper.find(".ftslickarw-prev"),
                            nextArrow: $wrapper.find(".ftslickarw-next"),
                        });
                        initProgress($this);
                    }
                });
            } else {
                $mb.filter(".slick-initialized").slick("unslick");
            }
        }

        $(".custmsliderwrpr").each(function () {
            var $wrapper = $(this);

            function safeInit(selector, options) {
                $wrapper.find(selector).each(function () {
                    var $this = $(this);
                    if (!$this.hasClass("slick-initialized")) {
                        $this.slick(options);
                        initProgress($this);
                    }
                });
            }

            // Feature Slider
            safeInit(".feat-exp-slidr", {
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                speed: 400,
                dots: false,
                autoplay: false,
                prevArrow: $wrapper.find(".ftslickarw-prev"),
                nextArrow: $wrapper.find(".ftslickarw-next"),
                responsive: [
                    { breakpoint: 1199, settings: { slidesToShow: 3 } },
                    { breakpoint: 991, settings: { slidesToShow: 2 } },
                    { breakpoint: 575, settings: { slidesToShow: 2 } }
                ]
            });

            // Private Reserve 1
            safeInit(".prvt-reserve", {
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: true,
                speed: 400,
                dots: false,
                autoplay: false,
                prevArrow: $wrapper.find(".ftslickarw-prev"),
                nextArrow: $wrapper.find(".ftslickarw-next"),
                responsive: [
                    { breakpoint: 1199, settings: { slidesToShow: 3 } },
                    { breakpoint: 991, settings: { slidesToShow: 2 } },
                    { breakpoint: 575, settings: { slidesToShow: 1 } }
                ]
            });

            // Private Reserve 2
            safeInit(".prvt-reserve-mini", {
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                speed: 400,
                dots: false,
                autoplay: false,
                prevArrow: $wrapper.find(".ftslickarw-prev"),
                nextArrow: $wrapper.find(".ftslickarw-next"),
                responsive: [
                    { breakpoint: 1199, settings: { slidesToShow: 3 } },
                    { breakpoint: 991, settings: { slidesToShow: 2 } },
                    { breakpoint: 575, settings: { slidesToShow: 1 } }
                ]
            });

            // Safa Detail Slider
            safeInit(".safa-dtl-slidr", {
                slidesToShow: 8,
                slidesToScroll: 1,
                arrows: true,
                speed: 400,
                dots: false,
                autoplay: false,
                prevArrow: $wrapper.find(".ftslickarw-prev"),
                nextArrow: $wrapper.find(".ftslickarw-next"),
                responsive: [
                    { breakpoint: 1199, settings: { slidesToShow: 6 } },
                    { breakpoint: 991, settings: { slidesToShow: 4 } },
                    { breakpoint: 575, settings: { slidesToShow: 2 } },
                    { breakpoint: 360, settings: { slidesToShow: 1 } }
                ]
            });

            // Handle Mobile-Only Slider
            $(window).off("resize.initMobileSlider").on("resize.initMobileSlider", function () {
                initMobileSlider($wrapper);
            }).trigger("resize");
        });
    }



    if ($('.datepicker').length) {
        $('.datepicker').each(function () {
            $(this).datepicker({
                format: 'MM dd, yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });
    }


    // Indrajit 20-05-2025 start
    // faq  js
    $(".faqoutwrpr").each(function () {
        const $wrapper = $(this);

        $wrapper.find(".faq-container .anwers").hide();

        $wrapper.find(".faq-container").first().addClass("is-open").find(".anwers").slideDown();
        $wrapper.find(".sfdtls-row").first().addClass("row-open");

        $wrapper.find(".faq-container .qution").click(function () {
            const $faqItem = $(this).parent();
            const $faqRow = $faqItem.closest('.sfdtls-row');

            if ($faqItem.hasClass("is-open")) {
                $faqItem.removeClass("is-open").find(".anwers").slideUp();
                $faqRow.removeClass("row-open");
            } else {
                $wrapper.find(".faq-container.is-open .anwers").slideUp();
                $wrapper.find(".faq-container.is-open").removeClass("is-open");
                $wrapper.find(".sfdtls-row.row-open").removeClass("row-open");

                $faqItem.addClass("is-open").find(".anwers").slideDown();
                $faqRow.addClass("row-open");
            }

            return false;
        });
    });
    // Indrajit 20-05-2025 end


    if ($(".js-range-slider").length) {
        //range slider
        var $range = $(".js-range-slider"),
            $inputFrom = $(".js-input-from"),
            $inputTo = $(".js-input-to"),
            min = +$range.data("min"),
            max = +$range.data("max"),
            instance;

        $range.ionRangeSlider({
            skin: "round",
            type: "double",
            min: min,
            max: max,
            from: min,
            to: max,
            onStart: updateInputs,
            onChange: updateInputs
        });

        instance = $range.data("ionRangeSlider");


        function updateInputs(data) {
            $inputFrom.val('£' + data.from);
            $inputTo.val('£' + data.to);
        }


        $inputFrom.on("input", function () {
            var val = parseInt($(this).val().replace(/\$/g, '')) || min;
            if (val < min) val = min;
            if (val > instance.result.to) val = instance.result.to;
            instance.update({ from: val });
        });


        $inputTo.on("input", function () {
            var val = parseInt($(this).val().replace(/\$/g, '')) || max;
            if (val < instance.result.from) val = instance.result.from;
            if (val > max) val = max;
            instance.update({ to: val });
        });


        $inputFrom.on("change", function () {
            var val = parseInt($(this).val().replace(/\$/g, '')) || min;
            $inputFrom.val('£' + val);
        });

        $inputTo.on("change", function () {
            var val = parseInt($(this).val().replace(/\$/g, '')) || max;
            $inputTo.val('£' + val);
        });

    }

    //filter popup
    document.querySelectorAll(".sf-filterbutn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const poptarget = btn.getAttribute("data-target");
            document.body.classList.add("no-scroll");
            document.querySelectorAll(`.filtr-rgtsdepnl[data-popup="${poptarget}"]`).forEach(function (panel) {
                panel.classList.add("popopen");
            });
            document.querySelectorAll(`.filtr-backdrop[data-popup="${poptarget}"]`).forEach(function (backdrop) {
                backdrop.classList.add("open");
            });
        });
    });
    document.querySelectorAll(".fltrpop-closebutn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            document.body.classList.remove("no-scroll");
            document.querySelectorAll(".filtr-rgtsdepnl").forEach(function (panel) {
                panel.classList.remove("popopen");
            });
            document.querySelectorAll(".filtr-backdrop").forEach(function (backdrop) {
                backdrop.classList.remove("open");
            });
        });
    });

    document.querySelectorAll(".filtr-backdrop").forEach(function (backdrop) {
        backdrop.addEventListener("click", function () {
            if (backdrop.classList.contains("open")) {
                document.body.classList.remove("no-scroll");
                document.querySelectorAll(".filtr-rgtsdepnl").forEach(function (panel) {
                    panel.classList.remove("popopen");
                });
                backdrop.classList.remove("open");
            }
        });
    });



    //updates js 22nd May

    // Body Gap & Inner Header Sticky
    const bodyGap = () => {
        const innerHeader = document.querySelector('.main-head.dashboard_header');
        if (innerHeader !== null) {
            const headerHeight = innerHeader.clientHeight;
            document.body.style.paddingTop = `${headerHeight + 20}px`;
        }
    }
    bodyGap();

    // Body Gap & Dashboard Right Sticky
    window.addEventListener('load', bodyGap);
    window.addEventListener('resize', bodyGap);

    let bodyGap2 = () => {
        const innerHeader = document?.querySelector('.rght_panel_header');
        if (innerHeader) {
            if (innerHeader !== null) {
                const headerHeight = innerHeader.clientHeight;
                document.body.style.paddingTop = `${headerHeight}px`;
            }
        }
    }
    bodyGap2();
    window.addEventListener('load', bodyGap2);
    window.addEventListener('resize', bodyGap2);

    const innerHeader = document?.querySelector('.rght_panel_header');
    if (innerHeader) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 0) {
                innerHeader.classList.add('dashboard_sticky')
            } else {
                innerHeader.classList.remove('dashboard_sticky')
            }
        })
    }

    // Password Show/Hide
    const inptHlder = document.querySelectorAll(".input_hldr");

    inptHlder.forEach(function (ele) {
        const passInputs = ele.querySelectorAll(".form_box input[type='password'], .form_box input[type='text']");
        const toggleButtons = ele.querySelectorAll(".pass_shwHde_bttn");

        toggleButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                this.classList.toggle("eye_active");
                passInputs.forEach(function (input) {
                    input.type = input.type === "password" ? "text" : "password";
                });
            });
        });
    });

    // Otp Verification
    function OTPInput() {
        const inputs = document.querySelectorAll(".otp_input_row .otp_input");
        inputs.forEach((input, idx) => {
            input.addEventListener("keydown", (event) => {
                if (event.key === "Backspace" || event.key === "Delete") {
                    input.value = "";
                    if (idx > 0) inputs[idx - 1].focus();
                    event.preventDefault();
                    return;
                }
                if (/^[0-9A-Za-z]$/.test(event.key)) {
                    if (idx === inputs.length - 1 && input.value !== "") {
                        event.preventDefault();
                        return;
                    }
                    input.value = event.key.toUpperCase();
                    if (idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                    }
                    event.preventDefault();
                }
            });

            input.addEventListener("paste", (event) => {
                event.preventDefault();
                const paste = (event.clipboardData || window.clipboardData).getData("text");
                const chars = paste.trim().split("");
                chars.forEach((ch, i) => {
                    if (i + idx < inputs.length && /^[0-9A-Za-z]$/.test(ch)) {
                        inputs[idx + i].value = ch.toUpperCase();
                    }
                });
                const last = Math.min(inputs.length - 1, idx + chars.length - 1);
                inputs[last].focus();
            });
        });
    }

    OTPInput();

    // Dashboard Toggle Menu
    const toggleMenu = () => {
        document.querySelector('body').classList.toggle('menu_open');
        document.querySelector('html').classList.toggle('menu_open');
        document.querySelector('.nav_panel').classList.toggle('menu_show');
    };

    const closeMenu = () => {
        document.querySelector('body').classList.remove('menu_open');
        document.querySelector('html').classList.remove('menu_open');
        document.querySelector('.nav_panel').classList.remove('menu_show');
    };

    // Toggle menu
    const hamButton = document.querySelector('.ham_bttn');
    if (hamButton) {
        hamButton.addEventListener('click', toggleMenu);
    }

    // Close menu
    const hamButtonClose = document.querySelector('.ham_bttn_close');
    if (hamButtonClose) {
        hamButtonClose.addEventListener('click', closeMenu);
    }

    // Close menu by overlay click
    const menuOverlay = document.querySelector('.menu_ovrly');
    if (menuOverlay) {
        menuOverlay.addEventListener('click', closeMenu);
    }

    // Search overlay toggle
    const searchOverlay = document?.querySelector('.srch_ovrlay');
    const searchButton = document?.querySelector('.srch_bttn');
    const searchCloseButton = document?.querySelector('.srch_close_bttn');

    if (searchOverlay && searchButton && searchCloseButton) {
        searchButton.addEventListener('click', () => {
            searchOverlay.classList.add('srch_active');
        });

        searchCloseButton.addEventListener('click', () => {
            searchOverlay.classList.remove('srch_active');
        });
    }

    // Meassage Board Sticky Equal Height
    function stickyPanel() {
        document.querySelectorAll('.each_equal_cntnr').forEach(el => {
            if (window.innerWidth < 1200) return;
            const totalH = el.querySelector('.equal_height').clientHeight;
            el.querySelector('.target_height').style.height = `${totalH}px`;

            const headerH = el.querySelector('.stcky_part').clientHeight;
            const bottom = el.querySelector('.mssg_bttm_pnl');
            const style = getComputedStyle(bottom);
            const padTop = parseFloat(style.paddingTop);
            const padBot = parseFloat(style.paddingBottom);
            const scrollH = totalH - headerH - padTop - padBot;

            el.querySelector('.scroll_area').style.height = `${scrollH}px`;
        });
    }

    // Hook it up:
    window.addEventListener('load', stickyPanel);
    window.addEventListener('resize', stickyPanel);
    window.addEventListener('orientationchange', stickyPanel);

    const ro = new ResizeObserver(stickyPanel);
    document.querySelectorAll('.each_equal_cntnr')
        .forEach(el => ro.observe(el));

    // Tom Select
    const customSelectElements = document.querySelectorAll('.cstm_slect');

    if (customSelectElements.length > 0) {
        customSelectElements.forEach(function (el) {
            new TomSelect(el, {
                create: false,
                sortField: 'text',
                controlInput: false
            });
        });
    }

    // update profile picture

    const inputFile = document.querySelector("#user-profile-icon");
    const pictureImage = document.querySelector("#user_profile");

    if (inputFile && pictureImage) {
        inputFile.addEventListener("change", function (e) {
            const inputTarget = e.target;
            const file = inputTarget.files[0];

            if (file) {
                const reader = new FileReader();

                reader.addEventListener("load", function (e) {
                    const readerTarget = e.target;
                    pictureImage.src = readerTarget.result;
                });
                reader.readAsDataURL(file);
            }
        });
    }

    document.querySelectorAll(".mssg_invdl_lnk").forEach(function (mssgbutn) {
        mssgbutn.addEventListener("click", function () {
            document.querySelectorAll('.mssg_chat_box').forEach(function (box) {
                box.classList.add("on");
            });
        });
    });
    document.querySelectorAll(".chatbackarw").forEach(function (mssgbutn) {
        mssgbutn.addEventListener("click", function () {
            document.querySelectorAll('.mssg_chat_box').forEach(function (box) {
                box.classList.remove("on");
            });
        });
    });

    if (document.querySelectorAll('[data-fancybox="sfgallery"]').length) {
        Fancybox.bind('[data-fancybox="sfgallery"]', {
            // options
        });
    }
    const div = document.querySelector('div.mmsge_area');
    if (div) {
        document.body.classList.add("scrolloff");
        document.documentElement.classList.add("scrolloff");
    }

    $('.multiselct').select2();
    // document end
})

//select
if (document.querySelector('.bnrfrm-select')) {
    const selectVallue = document.querySelector('.bnrfrm-select');
    selectVallue.addEventListener('change', function () {
        if (selectVallue.value === "") {
            selectVallue.classList.remove("bnrselect");
        } else {
            selectVallue.classList.add("bnrselect");
        }
    });
}

//Quantity
const qtnMinusBtns = document.querySelectorAll(".quntybtn-minus");
const qtnPlusBtns = document.querySelectorAll(".quntybtn-plus");

qtnMinusBtns.forEach(mbtn => {
    mbtn.addEventListener('click', function (e) {
        e.preventDefault();
        const input = this.parentElement.querySelector('input');
        let value = parseInt(input.value) || 0;
        if (value > 0) {
            input.value = value - 1;
        }
    });
});

qtnPlusBtns.forEach(pbtn => {
    pbtn.addEventListener('click', function (e) {
        e.preventDefault();
        const input = this.parentElement.querySelector('input');
        let value = parseInt(input.value) || 0;
        input.value = value + 1;
    });
});



