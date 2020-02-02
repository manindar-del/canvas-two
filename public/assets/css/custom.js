$(document).ready(function () {
    $("select.currency").niceSelect();
    
    
    
    
    //lightbox
     $('.hotelPicture a').magnificPopup({type:'image'});
    
    //datepicker    
    $(".datepicker").datepicker({
        dateFormat: 'MM dd , yy'
    });
    
    $(".descriptionList li").each(function(){        
        //$(this).find("a").click(function(e){
        //    e.preventDefault();
        //});
        
        $(this).click(function(){            
            $(this).addClass("activeSection").siblings().removeClass("activeSection");
        });
    });
    
    
    
    //sort by rating
    
    $(".searchByRating .form-group").each(function(){
        $(this).click(function(){
            
            $(this).addClass("selectedRating").siblings().removeClass("selectedRating");
            
        });
    });
    
    $("#slider").slider({
        min: 0,
        max: 2500,
        step: 1,
        values: [0, 2500],
        slide: function(event, ui) {
            for (var i = 0; i < ui.values.length; ++i) {
                $("input.sliderValue[data-index=" + i + "]").val(ui.values[i]);
            }
            
            
            $(".rangeSlider .form-group").each(function(){
                var a = $(this).find("input").val();
                $(this).find("span").text(a);
            });
            
        }
    });

    $("input.sliderValue").change(function() {
        var $this = $(this);
        $("#slider").slider("values", $this.data("index"), "$"+$this.val());
    });
    
    
    

    
    
    $(".currency").change(function(){        
        var cur = $(this).val();
        console.log(cur);        
        $(".bookingDropForm").submit();
    });


    
    /*var divs = $(".hotelDescriptions > div");
    for(var i = 0; i < divs.length; i+=2) {
      divs.slice(i, i+2).wrapAll("<div class='paging'></div>");
    }
    
    $(".paging").first().show().siblings().hide();
    */
    
    var a = 10;
    
    $('#pagination-1').paginate({
        items_per_page: a
    });
    
    $(".pagination__controls ul").addClass("paginate");
    $(".pagination__controls ul li a").click(function(e){
        e.preventDefault();
    });
    
    $(".numberOfPage").change(function(){
        a = $(this).val();
        console.log(a);
        
        $('#pagination-1').paginate({
            items_per_page: a
        });
    });
    
    
    function pagination(){
        $('#pagination-1').paginate({
            items_per_page: a
        });
        
        $(".pagination__controls ul").addClass("paginate");
        $(".pagination__controls ul li a").click(function(e){
            e.preventDefault();
        });
    }

   //pagination();
    
    
    
    
    
   var hotelDetails=[];        
   $(".hotelDescriptions > .row").each(function(index){
       var Name = $(this).find('.hotelName').text();       
       var hotelPrice = $(this).find('.confirmHotelBooking span').text();       
       var star = $(this).find('.hotelRating i.fa-star').length;
       
       if(star==5){
          $(this).addClass("five");
       }
       else if(star==4){
          $(this).addClass("four"); 
        }
       else if(star==3){
          $(this).addClass("three"); 
        }
       else if(star==2){
          $(this).addClass("two"); 
        }
       else if(star==1){
          $(this).addClass("one"); 
        }
       
       var numr = index;
       
       //$(this).attr("id", "page"+numr);
       
       //$(this).addClass("page");
       
       hotelDetails.push(
           {
               'name':Name,
               'price':hotelPrice,
               'rating':star,
               'index':numr
           }
       );       
   });
   //console.log(hotelDetails); 
    
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
    
    let itemsToBeSorted = $(".hotelDescriptions > .row");
    let finalSort = []
    /*
    hotelDetails.map(function(item)  {
        finalSort.push(itemsToBeSorted[item.index])
    })
    */    

    $(".sortParam").change(function(){ 
        var sortVal = $(this).val();        
        //console.log(sortVal);
        
        if(sortVal=='NameAs'){
           hotelDetails.sort(function(a, b){
               if(a.name > b.name) return 1;
            })
           //console.log(hotelDetails);  
            

            hotelDetails.map(function(item)  {
                finalSort.push(itemsToBeSorted[item.index])
            })
            
            $(".hotelDescriptions").html(finalSort);
            //console.log(finalSort);
            
            pagination();
        }
        if(sortVal=='NameDes'){
           hotelDetails.sort(function(a, b){
               if(b.name > a.name) return 1;
            })
            //console.log(hotelDetails);  
            
            hotelDetails.map(function(item)  {
                finalSort.push(itemsToBeSorted[item.index])
            })
            
            $(".hotelDescriptions").html(finalSort);
            //console.log(finalSort);
            
            pagination();
        }
                
        else if(sortVal=='PriceAs'){
           hotelDetails.sort(function(a, b){
                return a.price-b.price
            })
            //console.log(hotelDetails);  
            
            hotelDetails.map(function(item)  {
                finalSort.push(itemsToBeSorted[item.index])
            })
            
            $(".hotelDescriptions").html(finalSort);
            //console.log(finalSort);
            
            pagination();
        }
        else if(sortVal=='PriceDes'){
           hotelDetails.sort(function(a, b){
                return b.price-a.price
            })
            //console.log(hotelDetails);     
            
            hotelDetails.map(function(item)  {
                finalSort.push(itemsToBeSorted[item.index])
            })
            
            $(".hotelDescriptions").html(finalSort);
            //console.log(finalSort);
            
            pagination();
        }
                
        else if(sortVal=='RatingAs'){            
           hotelDetails.sort(function(a, b){
                return a.rating-b.rating
            })
            //console.log(hotelDetails);   
            
            hotelDetails.map(function(item)  {
                finalSort.push(itemsToBeSorted[item.index])
            })
            
            $(".hotelDescriptions").html(finalSort);
            //console.log(finalSort);
            
            pagination();

        }
        else if(sortVal=='RatingDes'){            
           hotelDetails.sort(function(a, b){
                return b.rating-a.rating
            })
            //console.log(hotelDetails);   
            
            hotelDetails.map(function(item)  {
                finalSort.push(itemsToBeSorted[item.index])
            })
            
            $(".hotelDescriptions").html(finalSort);
            //console.log(finalSort);   
            
            pagination();
        }

        
       // _.sortBy('hotelDetails', [sortVal]);        
       // console.log(hotelDetails); 
        
        
    });
    
    
    let toShow = $(".hotelDescriptions > .row");
    let filterShow=[];
    
    $(".hotelSearchByRating form .form-group").each(function(){
        $(this).click(function(){
            $(".hotelDescriptions").html(toShow);
            var selector = $(this).attr("id");
            
            $(".hotelDescriptions").find('>.row').hide();
            $(".hotelDescriptions").find('>.' + selector).show();

            
            filterShow = $(".hotelDescriptions").find('>.' + selector); 
            console.log(filterShow);
            $(".hotelDescriptions").html(filterShow);
            pagination();
            
        });
    });  
    
    //control the views
    
    $(".grid").click(function(e){
        e.preventDefault();
        $(".hotelDescriptions > .row").addClass("gridView");
    });
    
    $(".list").click(function(e){
        e.preventDefault();
        $(".hotelDescriptions > .row").removeClass("gridView");
        $(".gridView").css("display","inline-block");
    });
    
});

/*

let toBESortedLike = [ { index: 2, name: 'a' }, { index: 0, name: 'C'  }, { index: 1, name: 'B' } ]


let itemsToBeSorted = [ 'aa', 'bb', 'cc' ]


let sortedItems = []

toBESortedLike.map(function(item)  {
	sortedItems.push(itemsToBeSorted[item.index])
})


*/

