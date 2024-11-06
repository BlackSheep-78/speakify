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
                $(this).html("<center>&#10074;</center>");


            }
            else
            {
                that.playing = true;
                console.log("play");
                $(this).html("<center>&#9658;</center>");
            }
        } );
    }
}


$( document ).ready(function() 
{
    app.init(document);
});