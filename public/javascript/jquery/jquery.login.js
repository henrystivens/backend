$(document).mouseup(function() {
    $("#loginform").mouseup(function() {
        return false
    });

    $("a.close").click(function(e){
        e.preventDefault();
        $("#loginform").hide();
        $(".lock").fadeIn();
    });

    if ($("#loginform").is(":hidden"))
    {
        $(".lock").fadeOut();
    } else {
        $(".lock").fadeIn();
    }
    $("#loginform").toggle();
});


// I dont want this form be submitted
$("form#signin").submit(function() {
    return true;
});

// This is example of other button
$("input#cancel_submit").click(function(e) {
    $("#loginform").hide();
    $(".lock").fadeIn();
});