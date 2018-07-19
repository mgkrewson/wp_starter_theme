$(document).foundation();


$(document).ready(function () {
    let siteURL = $('#site-link').attr('href');
    let windowWidth = window.innerWidth;
    state.size = windowWidth > 640 ? 'large' : 'small';

    /** Scroll to section initially if url has a hashURL */

    function scrollToPosition() {
        let urlHash = window.location.href.split("#")[1];

        if (urlHash) {
            var hashBase = urlHash.split(':')[0];
            let openTag = urlHash.split(':')[1];

            // console.log({hashBase, openTag});

            if (openTag && openTag == "open") {
                openDropdown(hashBase + '-dropdown');
            }

            $('html,body').animate({
                scrollTop: $('#' + hashBase).offset().top
            }, 500);
        }
    }
    scrollToPosition();

    /** ------------------------- */


    /** Smooth Scroll when menu links are clicked */

    $(".hash-link").on('click', function (e) {
        e.preventDefault();
        // console.log('clicked on menu');
        // target element id
        var id = $(this).attr('href');

        // target element
        var $id = $(id);
        if ($id.length === 0) {
            return;
        }
        // top position relative to the document
        var pos = $id.offset().top;

        // animated top scrolling
        $('body, html').animate({
            scrollTop: pos
        }, 500, "swing");

        window.location.replace(siteURL + '/' + id);
    });
    /** ------------------------- */


    /** Open and Close Dropdowns */

    $('.hero-button').on('click', (e) => {
        e.preventDefault();
        let parentID = e.toElement.parentElement.id;
        let dropID = parentID + '-dropdown';

        // console.log({parentID, dropID});

        let height = $('#' + dropID).height();
        if (height == 0) {
            openDropdown(dropID);
        } else {
            closeDropdown(dropID);
        }
    });

    $('.close-button').on('click', (e) => {
        e.preventDefault();
        let el = e.target;
        let dropItem = getParentByClass(el, 'drop-item');
        let dropID = dropItem.id;
        closeDropdown(dropID);
    });

    function getParentByClass(el, className) {
        do {
            if (el.classList.contains(className)) {
                return el;
            } else {
                el = el.parentNode;
            }
        } while (el && el.parentNode)
    }

    function openDropdown(dropID) {
        let parentID = getParentID(dropID);
        let fullHeight = $('#' + dropID).children().first().outerHeight();

        $('#' + dropID).find('.close-button').css('transform', 'rotate(-90deg)');
        $('#' + dropID).css('height', fullHeight + 'px');
        setTimeout(() => {
            height = $('#' + dropID).height();
            $('#' + dropID).css('height', height + 'px');
            window.location.replace(siteURL + '/#' + parentID + ':open');
        }, 250);
    }

    function closeDropdown(dropID) {
        let parentID = getParentID(dropID);
        let pos = $('#' + parentID).offset().top;

        $('#' + dropID).css('height', '0px');
        $('#' + dropID).find('.close-button').css('transform', 'rotate(90deg)');
        $('body, html').animate({
            scrollTop: pos
        }, 250, "swing");
        window.location.replace(siteURL + '/#' + parentID);
    }

    function getParentID(dropID) {
        let endInd = dropID.lastIndexOf("-");
        let parentID = dropID.slice(0, endInd);
        return parentID;
    }

    /** ------------------------- */
});


/** Resize top logo on scroll */
let state = {
    size: 'small',
    ticking: false,
    scrollPosition: 0,
};

function handleCloseButton(position) {
    let margin = {
        right: {
            small: 16,
            large: 34,
        },
    };
    let minRight = margin.right[state.size];

    if (window.location.href.search(':open') > -1) {
        let openSection = window.location.href.split("#")[1].split(':')[0];
        let dropdown = $('#' + openSection + '-dropdown');
        let sectionTopPos = dropdown.offset().top;
        let sectionBottomPos = sectionTopPos + dropdown.height();
        let bottomBuffer = 70;
        let button = dropdown.find('.close-button');

        let right = ((window.innerWidth - dropdown.children().first().width()) / 2) + minRight;

        if (position > sectionTopPos && position < (sectionBottomPos - bottomBuffer)) {
            button.addClass('button-float');
            button.css('right', right + 'px');
        } else {
            button.removeClass('button-float');
            button.css('right', minRight + 'px');
        }
    } else {
        $('.close-button').removeClass('button-float');
        $('.close-button').css('right', minRight + 'px');
    }
}

$(window).scroll(function (e) {
    state.scrollPosition = window.scrollY;
    if (!state.ticking) {
        window.requestAnimationFrame(function () {
            handleCloseButton(state.scrollPosition);
            state.ticking = false;
        });
        state.ticking = true;
    }
});
/** ------------------------- */