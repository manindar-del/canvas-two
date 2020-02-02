// $(document).ready(function () {
$("select.currency").niceSelect();

//lightbox
$('.hotelPicture a').magnificPopup({ type: 'image' });

//datepicker
$(".datepicker").datepicker({
    dateFormat: 'MM dd , yy'
});

$(".descriptionList li").each(function() {
    //$(this).find("a").click(function(e){
    //    e.preventDefault();
    //});

    $(this).click(function() {
        $(this).addClass("activeSection").siblings().removeClass("activeSection");
    });
});

//sort by rating

$(".searchByRating .form-group").each(function() {
    $(this).click(function() {
        var $this = $(this);
        if ($this.find('[type="checkbox"]').prop('checked')) {
            $this.addClass('selectedRating')
        } else {
            $this.removeClass('selectedRating')
        }
        // $(this).addClass("selectedRating").siblings().removeClass("selectedRating");
    });
});

$(".currency").change(function() {
    var cur = $(this).val();
    //console.log(cur);
    $(".bookingDropForm").submit();
});

/*var divs = $(".hotelDescriptions > div");
for(var i = 0; i < divs.length; i+=2) {
  divs.slice(i, i+2).wrapAll("<div class='paging'></div>");
}

$(".paging").first().show().siblings().hide();
*/

var a = 10;

/* $('#pagination-1').paginate({
     items_per_page: a
 });
 */

$(".pagination__controls ul").addClass("paginate");
$(".pagination__controls ul li a").click(function(e) {
    e.preventDefault();
});

function pagination() {
    //$(".pagination__controls").remove();
    $('#pagination-1').paginate({
        items_per_page: a
    });

    $(".pagination__controls ul").addClass("paginate");
    $(".pagination__controls ul li a").click(function(e) {
        e.preventDefault();
    });
}

pagination();

$(".numberOfPage").change(function() {
    $(".pagination__controls").remove();
    a = $(this).val();
    //console.log(a);

    $('#pagination-1').paginate({
        items_per_page: a
    });

    $(".pagination__controls ul").addClass("paginate");
    $(".pagination__controls ul li a").click(function(e) {
        e.preventDefault();
    });
});

//pagination();

var hotelDetails = [];
var hotelPriceChart = [];
var aa;
$(".hotelDescriptions > .row").each(function(index) {
    var Name = $(this).find('.hotelName').text().toLowerCase();
    var hotelPrice = $(this).find('.confirmHotelBooking span').text();
    var star = $(this).find('.hotelRating i.fa-star').length;
    var address = $(this).find(".hotelAddress").text().toLowerCase();
    $(this).addClass(address.replace(/\s/g, ""));
    $(this).addClass(Name.replace(/\s/g, ""));

    //$(this).addClass(hotelPrice);

    if (star == 5) {
        $(this).addClass("five");
    } else if (star == 4) {
        $(this).addClass("four");
    } else if (star == 3) {
        $(this).addClass("three");
    } else if (star == 2) {
        $(this).addClass("two");
    } else if (star == 1) {
        $(this).addClass("one");
    }

    var numr = index;

    //$(this).attr("id", "page"+numr);

    //$(this).addClass("page");

    hotelDetails.push({
        'name': Name,
        'price': hotelPrice,
        'rating': star,
        'index': numr
    });


});

var minPrice = hotelDetails.sort(function(a, b) {
    return a.price - b.price
});

var minPriceFinal = 0;
if (minPrice && minPrice.length) {
    minPriceFinal = minPrice[0].price;
}
var minn = parseInt(minPriceFinal);

var maxPrice = hotelDetails.sort(function(a, b) {
    return b.price - a.price
});

var maxPriceFinal = 0;
if (maxPrice && maxPrice.length) {
    maxPriceFinal = maxPrice[0].price;
}
var maxx = parseInt(maxPriceFinal);

console.log("min is " + minn + "and max is " + maxPriceFinal);
$("#minPrice").val(minn);
$("#maxPrice").val(maxx);
$(".minPriceSpn").text(minn);
$(".maxPriceSpn").text(maxx);
//filter by price
$("#slider").slider({
    min: minn,
    max: maxx,
    step: 1,
    values: [minn, maxx],
    slide: function(event, ui) {

        for (var i = 0; i < ui.values.length; ++i) {
            $("input.sliderValue[data-index=" + i + "]").val(ui.values[i]);
        }

        $(".rangeSlider .form-group").each(function() {
            var a = $(this).find("input").val();
            $(this).find("span").text(a);
        });
        //filterShowByPrice = $(".hotelDescriptions > .push");
        //console.log(filterShowByPrice);
        //$(".hotelDescriptions").html(filterShowByPrice);


    }
});

var fiveStar = $(".hotelDescriptions .five").length;
var fourstarStar = $(".hotelDescriptions .four").length;
var threeStar = $(".hotelDescriptions .three").length;
var twoStar = $(".hotelDescriptions .two").length;
var oneStar = $(".hotelDescriptions .one").length;

$(".oneStar p span").text(oneStar);
$(".twoStar p span").text(twoStar);
$(".threeStar p span").text(threeStar);
$(".fourstarStar p span").text(fourstarStar);
$(".fiveStar p span").text(fiveStar);

/*$(".hotelSearchByRating form .form-group").each(function(){

    $(this).click(function(){
        var selector = $(this).attr("id");
        //$(".hotelDescriptions").find("."+selector).addClass("showIt").removeClass("hideIt").siblings().addClass("hideIt");
        $(".hotelDescriptions")
       console.log("."+selector);

    });
});*/

//sorting initials

var itemsToBeSorted = $(".hotelDescriptions > .row");
var finalSort = []
    /*
    hotelDetails.map(function(item)  {
        finalSort.push(itemsToBeSorted[item.index])
    })
    */

$(".sortParam").change(function() {
    var sortVal = $(this).val();
    //console.log(sortVal);

    if (sortVal == 'NameAs') {
        hotelDetails.sort(function(a, b) {
                if (a.name > b.name) return 1;
            })
            //console.log(hotelDetails);


        hotelDetails.map(function(item) {
            finalSort.push(itemsToBeSorted[item.index])
        })

        $(".hotelDescriptions").html(finalSort);
        //console.log(finalSort);

        pagination();
    }
    if (sortVal == 'NameDes') {
        hotelDetails.sort(function(a, b) {
                if (b.name > a.name) return 1;
            })
            //console.log(hotelDetails);

        hotelDetails.map(function(item) {
            finalSort.push(itemsToBeSorted[item.index])
        })

        $(".hotelDescriptions").html(finalSort);
        //console.log(finalSort);

        pagination();
    } else if (sortVal == 'PriceAs') {
        hotelDetails.sort(function(a, b) {
                return a.price - b.price
            })
            //console.log(hotelDetails);

        hotelDetails.map(function(item) {
            finalSort.push(itemsToBeSorted[item.index])
        })

        $(".hotelDescriptions").html(finalSort);
        //console.log(finalSort);

        pagination();
    } else if (sortVal == 'PriceDes') {
        hotelDetails.sort(function(a, b) {
                return b.price - a.price
            })
            //console.log(hotelDetails);

        hotelDetails.map(function(item) {
            finalSort.push(itemsToBeSorted[item.index])
        })

        $(".hotelDescriptions").html(finalSort);
        //console.log(finalSort);

        pagination();
    } else if (sortVal == 'RatingAs') {
        hotelDetails.sort(function(a, b) {
                return a.rating - b.rating
            })
            //console.log(hotelDetails);

        hotelDetails.map(function(item) {
            finalSort.push(itemsToBeSorted[item.index])
        })

        $(".hotelDescriptions").html(finalSort);
        //console.log(finalSort);

        pagination();

    } else if (sortVal == 'RatingDes') {
        hotelDetails.sort(function(a, b) {
                return b.rating - a.rating
            })
            //console.log(hotelDetails);

        hotelDetails.map(function(item) {
            finalSort.push(itemsToBeSorted[item.index])
        })

        $(".hotelDescriptions").html(finalSort);
        //console.log(finalSort);

        pagination();
    } else if (sortVal == 'default') {
        $(".hotelDescriptions").html(itemsToBeSorted);
    }

    // _.sortBy('hotelDetails', [sortVal]);
    // console.log(hotelDetails);

});

var filterShowByPrice = [];


$(".priceSearch").click(function(e) {
    e.preventDefault();
});


var min = $("#minPrice").val();
var max = $("#maxPrice").val();
//var itemsToBeSorted = $(".hotelDescriptions > .row");

$(".priceSearch").click(function() {
    var altered = [];
    $(".hotelDescriptions > .row").removeClass("pushPrice");
    var min = parseInt($("#minPrice").val());
    var max = parseInt($("#maxPrice").val());
    //console.log("min-"+min+" max-"+max);

    //let items = $('.hotelDescriptions  > .row');

    var result = itemsToBeSorted.filter(function(index) {
        var $this = itemsToBeSorted.eq(index);
        var price = $this.find('.confirmHotelBooking  h4 span').text();
        return price >= min && price <= max;
    });

    $(".hotelDescriptions").html(result);
    //console.log(result);
    pagination();
});

$("input.sliderValue").change(function() {
    var $this = $(this);
    $("#slider").slider("values", $this.data("index"), "$" + $this.val());
});

var toShow = $(".hotelDescriptions > .row");
var filterShow = [];

$(".hotelSearchByRating form .form-group").each(function() {
    $(this).click(function() {

        $(".hotelDescriptions").html(toShow);
        $(".hotelDescriptions").find('>.row').hide();

        var selectors = [];

        $(".hotelSearchByRating form .form-group").each(function() {
            if ($(this).find('[type="checkbox"]').prop('checked')) {
                var selector = $(this).attr("id");
                selectors.push('>.' + selector);
            }
        });

        console.log(selectors)

        var finalSelector = selectors.join(',');
        $(".hotelDescriptions").find(finalSelector).show();
        filterShow = $(".hotelDescriptions").find(finalSelector);
        $(".hotelDescriptions").html(filterShow);
        pagination();

        // $(".hotelDescriptions").html(toShow);
        // var selector = $(this).attr("id");

        // $(".hotelDescriptions").find('>.row').hide();
        // $(".hotelDescriptions").find('>.' + selector).show();


        // filterShow = $(".hotelDescriptions").find('>.' + selector);
        // //console.log(filterShow);
        // $(".hotelDescriptions").html(filterShow);
        // pagination();
    });
});

//control the views

$(".grid").click(function(e) {
    e.preventDefault();
    $(".hotelDescriptions > .row").addClass("gridView");
});

$(".list").click(function(e) {
    e.preventDefault();
    $(".hotelDescriptions > .row").removeClass("gridView");
    $(".gridView").css("display", "inline-block");
});

//search by name

$(".searchHotelByName form").submit(function(e) {
    var searched = $(".searchKey").val().replace(/\s/g, "").toLowerCase();
    e.preventDefault();
    console.log(searched);
    var toShowAfterType = $(".hotelDescriptions").find('>[class*="' + searched + '"]');
    $(".hotelDescriptions").html(toShowAfterType);
    console.log(toShowAfterType);
    pagination();
});

$(".resetSearch").click(function() {
    $(".hotelDescriptions").html(itemsToBeSorted);
    pagination();
});


//search by alphabet
for (var i = "a".charCodeAt(0); i <= "z".charCodeAt(0); i++) {
    $('.searchWithAlphabet ul').append("<li><a href='#'>" + String.fromCharCode(i) + "</a></li>");
}

$('.searchWithAlphabet ul').prepend("<li><a href='#'>All</a></li>");
$('.searchWithAlphabet ul li').first().nextAll().addClass("alphabet");
$('.searchWithAlphabet ul li').first().addClass("reset");
$('.searchWithAlphabet ul li a').click(function(e) {
    e.preventDefault();
});

var alphabeticalSort = [];
var resetList = $(".hotelDescriptions > .row");

$(".alphabet").each(function() {
    $(this).click(function() {

        $(".hotelDescriptions").html(resetList);
        var alphabeticalSort = [];
        $(".hotelDescriptions > .row").removeClass("push");

        var alpha = $(this).find("a").text();
        // console.log(alpha);
        //console.log(alphabeticalSort);

        $(".hotelDescriptions > .row").each(function() {
            var searched = $(this).find(".hotelName").text().toLowerCase().replace(/\s/g, "");
            if (searched.startsWith(alpha)) {
                $(this).addClass("push");
            }
        });

        alphabeticalSort = $(".hotelDescriptions > .push");
        // console.log(alphabeticalSort);
        $(".hotelDescriptions").html(alphabeticalSort);
        pagination();
    });
});

$(".reset").click(function() {
    $(".hotelDescriptions").html(resetList);
    $(".hotelDescriptions > .row").removeClass("push");
    pagination();
});

//search by city

var atlanta = $(".hotelDescriptions > .atlanta").length;
var losangeles = $(".hotelDescriptions > .losangeles").length;
var sanfransisco = $(".hotelDescriptions > .sanfransisco").length;
var manhattan = $(".hotelDescriptions > .manhattan").length;
var ohio = $(".hotelDescriptions > .ohio").length;
var newyork = $(".hotelDescriptions > .newyork").length;
var newjersey = $(".hotelDescriptions > .newjersey").length;

$("#atlanta p span").text(atlanta);
$("#losangeles p span").text(losangeles);
$("#sanfransisco p span").text(sanfransisco);
$("#manhattan p span").text(manhattan);
$("#ohio p span").text(ohio);
$("#newyork p span").text(newyork);
$("#newjersey p span").text(newjersey);

$(".hotelSearchByArea .form-group").each(function() {
    var cityId = $(this).attr("id");
    $(this).click(function() {
        var sortedCity = [];
        $(".hotelDescriptions").html(resetList);
        //console.log(sortedCity);
        //console.log(cityId);

        sortedCity = $(".hotelDescriptions > ." + cityId)
            //console.log(sortedCity);

        $(".hotelDescriptions").html(sortedCity);
        pagination();

    });
});

$(".resetFilter").click(function() {
    $(".hotelDescriptions").html(resetList);
    $('.hotelSearchByRating [type="checkbox"]').prop('checked', false);
    $(".searchByRating .form-group").removeClass('selectedRating');
    pagination();
});


$(".hotelSearchByArea .form-group").each(function() {
    var cityId = $(this).find(".starRating h6").text().toLocaleLowerCase();
    $(this).click(function() {
        var hotelResult = [];
        $(".hotelDescriptions").html(itemsToBeSorted);

        hotelResult = itemsToBeSorted.filter(function(index) {
            var $this = itemsToBeSorted.eq(index);
            var hotelAddress = $this.find('.hotelAddress').text();
            return hotelAddress == cityId;
        });

        console.log(hotelResult);
        $(".hotelDescriptions").html(hotelResult);


        pagination();
    });
});

$(".resetFilter").click(function() {
    $(".hotelDescriptions").html(resetList);
    $('.hotelSearchByRating [type="checkbox"]').prop('checked', false);
    $(".searchByRating .form-group").removeClass('selectedRating');
    pagination();
});


// });

/*
let toBESortedLike = [ { index: 2, name: 'a' }, { index: 0, name: 'C'  }, { index: 1, name: 'B' } ]
let itemsToBeSorted = [ 'aa', 'bb', 'cc' ]
let sortedItems = []
toBESortedLike.map(function(item)  {
	sortedItems.push(itemsToBeSorted[item.index])
})
*/