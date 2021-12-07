
$(document).ready(function () 
{
    $("#unfollow").click(function () 
    {
        var user = $("#unfollow").val();
        console.log(user);
        $.post("requests.php/unfollow",
        {
            name: "unfollow",
            userid: user
        },
        function(data, status){
            console.log("unfollowed");
        });
    });



});