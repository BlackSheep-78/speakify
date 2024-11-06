var app = 
{
    playing : false,

    init: function(document)
    {  
        var that = this;

        $( "#play" ).on( "click", function() 
        {
            if(that.playing)
            {
                that.playing = false;
                console.log("pause");
                $("#play .icon").attr("src","icons/play.png");
            }
            else
            {
                that.playing = true;
                console.log("play");
                $("#play .icon").attr("src","icons/pause.png");
            }
        } );
    }
}


$( document ).ready(function() 
{
    app.init(document);
});