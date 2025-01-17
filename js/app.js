var app = 
{
    playing : false,
    playlist : [],

    init: function(document)
    {  
        let that = this;

        $("#b1").on('click',function()
        {
            that.ajax("json=true&get=sentences",function(data)
            {
                for(let i = 0; i < data.length; i++)
                {
                    that.playlist.push(data[i]);
                } 
            });
        });

        $("#b2").on('click',function()
        {
            console.log(that.playlist.length);
        });

    },

    f1: function()
    {
        let that = this;
        console.log(that.playlist);



    },

    clone : function(selector)
    {
        let sample = $($("template1").clone()[0]);
    },

    run: function()
    {
        var schema = [{language:1,quantity:4},{language:2,quantity:3},{language:3,quantity:2},{language:2,quantity:2}];

        this.loop(schema);
    },

    loop: function(schema,list,iteration)
    {
        var that = this;

        

        if(typeof(iteration) === 'undefined') { iteration = 0; }

        var elem    = that.queue[0];
        var content = $("#content");

        

        if(iteration === 0)
        {
            //console.log("iteration 0 : preparing stuff");

            $(content).empty();

            var list = [];
            var tmp  = {};

            for(var i = 0; i < elem.length; i++) 
            {
                tmp[elem[i].language] = elem[i];
            }

            for(var i = 0; i < schema.length; i++) 
            {

                if(typeof schema[i].events === 'undefined') { schema[i].events = {}; }

                schema[i].block = $("<div class='textA'>" + tmp[schema[i].language].text + "</div>");


                if(tmp[schema[i].language].id)
                {
                    schema[i].audio = $("<audio src='audio/" + tmp[schema[i].language].id + ".wav'></audio>");
                }

                schema[i].progress = $("<div class='progress'></div>");

                for(var j = 0; j < schema[i].quantity; j++) 
                {
                    list.push({block:i});
                }

                $(schema[i].block).hide().fadeIn().appendTo(content);

                if(schema[i].audio)
                {
                    $(schema[i].audio).hide().fadeIn().appendTo(content);
                }

                $(schema[i].progress).hide().fadeIn().appendTo(content);
            };

            console.log(list);

        }

        var block = list[iteration].block;

        if(!('ended' in schema[block].events))
        {
            schema[block].events['ended'] = true;

            schema[block].audio[0].addEventListener('ended', function() 
            {
                iteration = iteration + 1;

                that.loop(schema,list,iteration);
            }, false);
        }

        /*
            schema[block].audio[0].addEventListener("canplay",function()
            {
                console.log("Duration:" + audioA[0].duration + " seconds");
                console.log("Source:" + audioA[0].src);
            });
        */

        schema[block].progressInitialLenght = $(schema[block].progress).width();
        schema[block].progressFinalLenght   = $(schema[block].block).outerWidth() - schema[block].progressInitialLenght;
        

        if(!('timeupdate' in schema[block].events))
        {
            schema[block].events['timeupdate'] = true;

            schema[block].audio[0].addEventListener("timeupdate",function()
            {
                var currentWidth = schema[block].progressInitialLenght + ( schema[block].progressFinalLenght * (schema[block].audio[0].currentTime / schema[block].audio[0].duration));
                $(schema[block].progress).width(currentWidth + "px");
            });
        }


        console.log("iteration",iteration);
        console.log("block",block);
        schema[block].audio[0].play();

        //that.queue.shift();
        //that.queue.push(elem);
    },

    ajax : function(args,callback)
    {
        var that = this;

        $.get( "http://localhost/translate/?" + args, function( data ) 
        {
            if(callback)
            {
                callback(JSON.parse(data))
            }

        });
    }
}

$(document).ready(function()
{
    app.init(document);
});


